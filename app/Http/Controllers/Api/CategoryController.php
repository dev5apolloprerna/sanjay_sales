<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /** POST /api/categories – all active categories with subcategories + product counts. */
    public function index()
    {
        $categories = Category::active()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->with(['subcategories' => fn ($q) => $q->active()->withCount(['products' => fn ($p) => $p->active()])])
            ->orderBy('sort_order')->orderBy('name')->get();

        return $this->ok(CategoryResource::collection($categories));
    }

    /** POST /api/categories/detail   body: { "slug": "welding-machines" } */
    public function show(Request $request)
    {
        $slug = $request->validate(['slug' => ['required', 'string', 'max:200']])['slug'];

        $category = Category::active()->where('slug', $slug)
            ->withCount(['products' => fn ($q) => $q->active()])
            ->with(['subcategories' => fn ($q) => $q->active()->withCount(['products' => fn ($p) => $p->active()])])
            ->firstOrFail();

        return $this->ok(new CategoryResource($category));
    }

    /** POST /api/subcategories   body: { "category": "welding-machines" }  (optional, slug or id) */
    public function subcategories(Request $request)
    {
        $items = Subcategory::active()->with('category:id,name,slug')
            ->withCount(['products' => fn ($q) => $q->active()])
            ->whereHas('category', fn ($q) => $q->active())
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->orderBy('sort_order')->orderBy('name')->get();

        return $this->ok(SubcategoryResource::collection($items));
    }

    /** POST /api/subcategories/detail   body: { "category": "welding-machines", "slug": "arc-welders" } */
    public function subcategory(Request $request)
    {
        $v = $request->validate([
            'category' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200'],
        ]);

        $item = Subcategory::active()->where('slug', $v['slug'])
            ->whereHas('category', fn ($q) => $q->active()->where('slug', $v['category']))
            ->with('category:id,name,slug')
            ->withCount(['products' => fn ($q) => $q->active()])
            ->firstOrFail();

        return $this->ok(new SubcategoryResource($item));
    }
}
