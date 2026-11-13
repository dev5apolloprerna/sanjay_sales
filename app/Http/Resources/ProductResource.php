<?php

namespace App\Http\Resources;

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
            'specifications' => $this->specifications,
            // [{ "question": "...", "answer": "<p>HTML</p>" }] – empty array when there are no FAQs
            'faqs' => array_values($this->faqs ?? []),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
