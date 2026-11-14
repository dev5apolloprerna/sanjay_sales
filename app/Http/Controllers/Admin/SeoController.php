<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use Illuminate\Http\Request;

/** SEO Setup for the website's fixed pages (home, about, contact ...). */
class SeoController extends Controller
{
    public function index()
    {
        SeoPage::ensureDefaults();

        $order = array_keys(SeoPage::PAGES);
        $items = SeoPage::managed()->get()->sortBy(fn ($p) => array_search($p->page_key, $order))->values();

        return view('admin.seo.index', compact('items'));
    }

    public function edit(SeoPage $seo)
    {
        abort_unless(array_key_exists($seo->page_key, SeoPage::PAGES), 404);

        return view('admin.seo.form', ['item' => $seo]);
    }

    public function update(Request $request, SeoPage $seo)
    {
        abort_unless(array_key_exists($seo->page_key, SeoPage::PAGES), 404);
        
        $this->decodeHexInput($request);

        $data = $request->validate([
            'page_name' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'meta_description' => ['nullable', 'string', 'max:5000'],
            'head' => ['nullable', 'string', 'max:65000'],
            'body' => ['nullable', 'string', 'max:65000'],
        ]);

        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO updated successfully.');
    }
    
    private function decodeHexInput(Request $request): void
    {
        $fields = [
            'page_name',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'head',
            'body',
        ];
    
        $decoded = [];
    
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $decoded[$field] = $this->decodeHex($request->input($field));
            }
        }
    
        $request->merge($decoded);
    }

    private function decodeHex($value)
    {
        if (!is_string($value)) {
            return $value;
        }
    
        // editor may wrap plain text in <p>...</p>
        $plain = trim(strip_tags($value));
        if (str_starts_with($plain, 'hx') && ctype_xdigit(substr($plain, 2))) {
            $value = $plain;
        }
    
        while (str_starts_with($value, 'hx')) {
            $hex = substr($value, 2);
    
            if ($hex === '' || strlen($hex) % 2 !== 0 || !ctype_xdigit($hex)) {
                break;
            }
    
            $decoded = hex2bin($hex);
            if ($decoded === false) {
                break;
            }
    
            $value = $decoded;
        }
    
        return $value;
    }
}
