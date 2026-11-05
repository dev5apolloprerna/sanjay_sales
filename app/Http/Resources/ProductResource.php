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
            'features' => $this->features ?? [],
            'applications' => $this->applications ?? [],
            'specifications' => $this->specifications ?? [],
            'faqs' => $this->faqs ?? [],
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
