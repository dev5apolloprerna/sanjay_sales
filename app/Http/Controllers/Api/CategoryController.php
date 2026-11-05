<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /** GET /api/categories – all active categories with subcategories + product counts. */
    public function index()
    {
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->with(['subcategories' => fn ($q) => $q->active()->withCount(['products' => fn ($p) => $p->active()])])
            ->orderBy('sort_order')->orderBy('name')->get();

        return $this->ok(CategoryResource::collection($categories));
    }

    /** GET /api/categories/{slug} – one category with its subcategories. Products: /api/products?category={slug} */
    public function show(string $slug)
    {
        $category = Category::active()->where('slug', $slug)
            ->withCount(['products' => fn ($q) => $q->active()])
            ->with(['subcategories' => fn ($q) => $q->active()->withCount(['products' => fn ($p) => $p->active()])])
            ->firstOrFail();

        return $this->ok(new CategoryResource($category));
    }

    /** GET /api/subcategories?category={slug|id} */
    public function subcategories(Request $request)
    {
        $items = Subcategory::active()->with('category:id,name,slug')
            ->withCount(['products' => fn ($q) => $q->active()])
            ->whereHas('category', fn ($q) => $q->active())
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->orderBy('sort_order')->orderBy('name')->get();

        return $this->ok(SubcategoryResource::collection($items));
    }

    /** GET /api/categories/{category}/{slug} – one subcategory. Products: /api/products?subcategory={slug} */
    public function subcategory(string $category, string $slug)
    {
        $item = Subcategory::active()->where('slug', $slug)
            ->whereHas('category', fn ($q) => $q->active()->where('slug', $category))
            ->with('category:id,name,slug')
            ->withCount(['products' => fn ($q) => $q->active()])
            ->firstOrFail();

        return $this->ok(new SubcategoryResource($item));
    }
}
