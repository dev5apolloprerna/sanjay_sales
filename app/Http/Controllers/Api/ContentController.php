<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Video;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /** Categories with their active subcategories (for menus). */
    public function categories()
    {
        return Category::active()->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->with(['subcategories' => fn ($q) => $q->active()])
            ->orderBy('sort_order')->orderBy('name')->get();
    }

    /** /api/subcategories?category=welding-machines */
    public function subcategories(Request $request)
    {
        return Subcategory::active()->with('category:id,name,slug')
            ->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->orderBy('sort_order')->orderBy('name')->get();
    }

    /** /api/categories/{category-slug}/{subcategory-slug} */
    public function subcategory(string $category, string $slug)
    {
        return Subcategory::active()->where('slug', $slug)
            ->whereHas('category', fn ($q) => $q->where('slug', $category))
            ->with(['category', 'products' => fn ($q) => $q->active()->with('images')->latest()])
            ->firstOrFail();
    }

    public function clients()
    {
        return Client::active()->orderBy('sort_order')->latest()->get();
    }

    public function category(string $slug)
    {
        return Category::active()->where('slug', $slug)
            ->with(['subcategories' => fn ($q) => $q->active(), 'products' => fn ($q) => $q->active()->with(['images', 'subcategory:id,name,slug'])->latest()])
            ->firstOrFail();
    }

    /** /api/products?category=welding-machines&subcategory=arc-welders&featured=1&q=esab */
    public function products(Request $request)
    {
        return Product::active()->with(['category:id,name,slug', 'subcategory:id,name,slug', 'images'])
            ->when($request->category, fn ($q, $c) => $q->whereHas('category', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->when($request->subcategory, fn ($q, $c) => $q->whereHas('subcategory', fn ($w) => $w->where('slug', $c)->orWhere('id', $c)))
            ->when($request->boolean('featured'), fn ($q) => $q->where('featured', true))
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()->get();
    }

    public function product(string $slug)
    {
        return Product::active()->where('slug', $slug)->with(['category', 'subcategory', 'images'])->firstOrFail();
    }

    public function gallery()
    {
        return Gallery::active()->orderBy('sort_order')->latest()->get();
    }

    public function videos()
    {
        return Video::active()->orderBy('sort_order')->latest()->get();
    }

    public function blogs()
    {
        return Blog::active()->orderByDesc('published_at')->get()->makeHidden('content');
    }

    public function blog(string $slug)
    {
        return Blog::active()->where('slug', $slug)->firstOrFail();
    }
}
