<?php

namespace App\Http\Resources;

use App\Models\ProductSpecification;
use Illuminate\Http\Request;

/** Full product shape for the product detail page. */
class ProductResource extends ProductCardResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'description' => $this->description,
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => [
                'id' => $img->id,
                'url' => $img->url,
            ])->values()),
            // HTML from the admin's CKEditor fields (null when empty – hide that section)
            'features' => $this->features,
            'applications' => $this->applications,
            // [{ "group": "General", "items": [{ "label": "Machine Type", "value": "CNC Plasma Cutting Machine" }] }] – [] when none
            'specifications' => $this->whenLoaded('specRows', fn () => ProductSpecification::grouped($this->specRows), []),
            // [{ "id": 1, "question": "...", "answer": "<p>HTML</p>" }] – active FAQs only, [] when none
            'faqs' => $this->whenLoaded('faqs', fn () => $this->faqs->map(fn ($f) => [
                'id' => $f->id,
                'question' => $f->question,
                'answer' => $f->answer,
            ])->values(), []),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
