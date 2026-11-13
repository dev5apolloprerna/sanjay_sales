<?php

namespace App\Http\Resources;

use App\Support\Html;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Short product shape for listing cards, related products, menus. */
class ProductCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand' => $this->brand,
            'price' => $this->price,
            'short_description' => $this->short_description,                         // HTML (CKEditor)
            'short_description_text' => Html::text($this->short_description),        // plain text for cards / meta
            'image_url' => $this->whenLoaded('images', fn () => $this->images->first()?->url),
            'featured' => $this->featured,
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id, 'name' => $this->category->name, 'slug' => $this->category->slug,
            ] : null),
            'subcategory' => $this->whenLoaded('subcategory', fn () => $this->subcategory ? [
                'id' => $this->subcategory->id, 'name' => $this->subcategory->name, 'slug' => $this->subcategory->slug,
            ] : null),
        ];
    }
}
