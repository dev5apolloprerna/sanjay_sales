<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Key Features, Applications and Technical Specifications are now edited with CKEditor,
 * so they are stored as HTML instead of JSON arrays. Existing data is converted:
 *   features / applications  ["a","b"]                        -> <ul><li>a</li><li>b</li></ul>
 *   specifications           [{"group":..,"items":[..]}]       -> <h4>Group</h4><table>…</table>
 *   faqs                     answers stay in JSON, plain text  -> <p>answer</p>
 */
return new class extends Migration
{
    public function up(): void
    {
        $old = DB::table('products')->get(['id', 'features', 'applications', 'specifications', 'faqs']);

        Schema::table('products', function (Blueprint $table) {
            $table->longText('features')->nullable()->change();
            $table->longText('applications')->nullable()->change();
            $table->longText('specifications')->nullable()->change();
        });

        $e = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

        foreach ($old as $row) {
            $list = function ($json) use ($e) {
                $items = json_decode((string) $json, true);
                if (! is_array($items) || ! $items) {
                    return null;
                }

                return '<ul>'.implode('', array_map(fn ($i) => '<li>'.$e($i).'</li>', $items)).'</ul>';
            };

            $specs = null;
            $groups = json_decode((string) $row->specifications, true);
            if (is_array($groups) && $groups) {
                $specs = '';
                foreach ($groups as $g) {
                    $rows = implode('', array_map(fn ($i) => '<tr><th>'.$e($i['label'] ?? '').'</th><td>'.$e($i['value'] ?? '').'</td></tr>', $g['items'] ?? []));
                    $specs .= (count($groups) > 1 ? '<h4>'.$e($g['group'] ?? 'General').'</h4>' : '')
                        .'<figure class="table"><table><tbody>'.$rows.'</tbody></table></figure>';
                }
            }

            $faqs = json_decode((string) $row->faqs, true);
            if (is_array($faqs)) {
                $faqs = array_map(fn ($f) => [
                    'question' => $f['question'] ?? '',
                    'answer' => str_starts_with(trim($f['answer'] ?? ''), '<') ? $f['answer'] : '<p>'.$e($f['answer'] ?? '').'</p>',
                ], $faqs);
            }

            DB::table('products')->where('id', $row->id)->update([
                'features' => $list($row->features),
                'applications' => $list($row->applications),
                'specifications' => $specs,
                'faqs' => $faqs ? json_encode($faqs, JSON_UNESCAPED_UNICODE) : null,
            ]);
        }
    }

    public function down(): void
    {
        // HTML can't be turned back into the old arrays reliably – clear it, then restore the JSON type.
        DB::table('products')->update(['features' => null, 'applications' => null, 'specifications' => null]);

        Schema::table('products', function (Blueprint $table) {
            $table->json('features')->nullable()->change();
            $table->json('applications')->nullable()->change();
            $table->json('specifications')->nullable()->change();
        });
    }
};
