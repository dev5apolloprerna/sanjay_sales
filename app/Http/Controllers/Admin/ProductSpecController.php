<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSpecification;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Technical specifications of one product:  /admin/products/{product}/specifications
 * All groups + rows are edited on one page and saved together.
 */
class ProductSpecController extends Controller
{
    public function edit(Product $product)
    {
        $groups = ProductSpecification::grouped($product->specRows()->get());
        $imported = false;

        // first time: pre-fill from the old rich-text "Technical Specifications" field, if any
        if (! $groups && $product->getRawOriginal('specifications')) {
            $groups = self::fromHtml($product->getRawOriginal('specifications'));
            $imported = (bool) $groups;
        }

        return view('admin.product-specs.form', [
            'product' => $product,
            'groups' => old('groups', $groups ?: [['group' => 'General', 'items' => [['label' => '', 'value' => '']]]]),
            'imported' => $imported,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // drop completely empty rows / groups before validating
        $groups = collect((array) $request->input('groups', []))
            ->map(fn ($g) => [
                'group' => trim((string) ($g['group'] ?? '')),
                'items' => collect((array) ($g['items'] ?? []))
                    ->map(fn ($i) => ['label' => trim((string) ($i['label'] ?? '')), 'value' => trim((string) ($i['value'] ?? ''))])
                    ->filter(fn ($i) => $i['label'] !== '' || $i['value'] !== '')
                    ->values()->all(),
            ])
            ->filter(fn ($g) => $g['group'] !== '' || $g['items'])
            ->values()->all();
        $request->merge(['groups' => $groups]);

        $request->validate([
            'groups' => ['array', 'max:30'],
            'groups.*.group' => ['required', 'string', 'max:150'],
            'groups.*.items' => ['required', 'array', 'max:100'],
            'groups.*.items.*.label' => ['required', 'string', 'max:200'],
            'groups.*.items.*.value' => ['required', 'string', 'max:2000'],
        ], [
            'groups.*.group.required' => 'Enter a group name (e.g. General).',
            'groups.*.items.required' => 'Add at least one row to this group, or remove the group.',
            'groups.*.items.*.label.required' => 'Enter the label.',
            'groups.*.items.*.value.required' => 'Enter the value.',
        ]);

        DB::transaction(function () use ($product, $groups) {
            $product->specRows()->delete();
            foreach ($groups as $g => $group) {
                foreach ($group['items'] as $i => $item) {
                    $product->specRows()->create([
                        'group_name' => $group['group'],
                        'group_order' => $g + 1,
                        'label' => $item['label'],
                        'value' => $item['value'],
                        'sort_order' => $i + 1,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.specifications.edit', $product)->with('success', 'Technical specifications saved.');
    }

    /** Best-effort reading of the old CKEditor HTML (headings + tables, or "bold label / value" paragraphs). */
    private static function fromHtml(string $html): array
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div>'.$html.'</div>');
        libxml_clear_errors();

        $groups = [];
        $current = 'General';
        $pending = null;
        $add = function ($label, $value) use (&$groups, &$current) {
            $label = trim(preg_replace('/\s+/u', ' ', $label));
            $value = trim(preg_replace('/\s+/u', ' ', $value));
            if ($label === '' || $value === '') {
                return;
            }
            $groups[$current] ??= [];
            $groups[$current][] = ['label' => $label, 'value' => $value];
        };

        $xp = new DOMXPath($doc);
        foreach ($xp->query('//h1|//h2|//h3|//h4|//h5|//h6|//tr|//p|//li') as $el) {
            $tag = strtolower($el->nodeName);
            if ($tag !== 'tr' && $xp->query('ancestor::tr|ancestor::li', $el)->length) {
                continue; // text inside a table cell / list item is handled by its row
            }
            $text = trim($el->textContent);
            if ($text === '') {
                continue;
            }
            if ($tag[0] === 'h') {
                $current = $text;
                $pending = null;
            } elseif ($tag === 'tr') {
                $cells = [];
                foreach ($el->childNodes as $c) {
                    if (in_array(strtolower($c->nodeName), ['th', 'td'])) {
                        $cells[] = trim($c->textContent);
                    }
                }
                if (count($cells) >= 2) {
                    $add($cells[0], implode(' ', array_slice($cells, 1)));
                } elseif (count($cells) === 1) {
                    $current = $cells[0];
                }
            } elseif ($tag === 'li' && $el->getElementsByTagName('tr')->length === 0) {
                if (preg_match('/^(.+?)\s*[:|]\s*(.+)$/u', $text, $m)) {
                    $add($m[1], $m[2]);
                }
            } elseif ($tag === 'p') {
                $bold = '';
                foreach (['strong', 'b'] as $t) {
                    foreach ($el->getElementsByTagName($t) as $b) {
                        $bold .= $b->textContent;
                    }
                }
                if ($bold !== '' && trim($bold) === $text) {
                    $pending = $text;                       // <p><strong>Label</strong></p>
                } elseif ($pending !== null) {
                    $add($pending, $text);                  // <p>Value</p>
                    $pending = null;
                } elseif (preg_match('/^(.+?)\s*[:|]\s*(.+)$/u', $text, $m)) {
                    $add($m[1], $m[2]);                     // <p>Label: Value</p>
                }
            }
        }

        return array_map(fn ($g, $items) => ['group' => $g, 'items' => $items], array_keys($groups), $groups);
    }
}
