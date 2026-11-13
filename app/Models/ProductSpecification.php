<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/** One "Label | Value" line of a product's technical specifications, inside a named group. */
class ProductSpecification extends Model
{
    protected $fillable = ['product_id', 'group_name', 'group_order', 'label', 'value', 'sort_order'];

    protected $casts = ['group_order' => 'integer', 'sort_order' => 'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Rows -> grouped shape used by the admin form and the API:
     * [ { "group": "General", "items": [ { "label": "...", "value": "..." } ] } ]
     */
    public static function grouped(Collection $rows): array
    {
        return $rows->sortBy([['group_order', 'asc'], ['sort_order', 'asc'], ['id', 'asc']])
            ->groupBy('group_order')
            ->map(fn ($items) => [
                'group' => $items->first()->group_name,
                'items' => $items->map(fn ($r) => ['label' => $r->label, 'value' => $r->value])->values()->all(),
            ])->values()->all();
    }
}
