<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    /**
     * POST /api/products   (all body fields optional, no pagination – returns all matching products)
     *   category     slug or id
     *   subcategory  slug or id
     *   featured     true|false
     *   q            searches name, brand, short description
     *   sort         latest|oldest|name_asc|name_desc
     */
    public function index(Request $request)
    {
        $request->validate([
            'sort' => ['nullable', 'in:latest,oldest,name_asc,name_desc'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Product::active()
            ->with(['category:id,name,slug', 'subcategory:id,name,slug', 'images'])
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->when($request->subcategory, fn ($q, $c) => $q->whereHas('subcategory', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->when($request->boolean('featured'), fn ($q) => $q->where('featured', true))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")
                ->orWhere('brand', 'like', "%{$s}%")
                ->orWhere('short_description', 'like', "%{$s}%")));

        match ($request->input('sort', 'latest')) {
            'oldest' => $query->oldest()->orderBy('id'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            default => $query->latest()->orderByDesc('id'),
        };

        return $this->ok(ProductCardResource::collection($query->get()));
    }

    /**
     * POST /api/products/detail   body: { "slug": "mig-350" }
     * Product + 4 related products (same subcategory first, then same category).
     */
    public function show(Request $request)
    {
        $slug = $request->validate(['slug' => ['required', 'string', 'max:220']])['slug'];

        $product = Product::active()->where('slug', $slug)
            ->with(['category:id,name,slug', 'subcategory:id,name,slug', 'images', 'specRows', 'faqs' => fn ($q) => $q->active()])
            ->firstOrFail();

        $related = Product::active()->whereKeyNot($product->id)
            ->where('category_id', $product->category_id)
            ->with(['images', 'category:id,name,slug'])
            ->when($product->subcategory_id, fn ($q) => $q->orderByRaw('subcategory_id = ? desc', [$product->subcategory_id]))
            ->latest()->take(4)->get();

        return $this->ok([
            'product' => (new ProductResource($product))->resolve(),
            'related_products' => ProductCardResource::collection($related)->resolve(),
        ]);
    }
}
