<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlogCardResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ProductCardResource;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends ApiController
{
    /**
     * POST /api/home   body: {}
     * Everything the homepage needs in one request.
     */
    public function __invoke()
    {
        $stats = Setting::homeStats();

        return $this->ok([
            'hero_slides' => config('site.hero_slides'),
            'about' => config('site.about'),
            'categories' => CategoryResource::collection(
                Category::active()->withCount(['products' => fn ($q) => $q->active()])
                    ->orderBy('sort_order')->orderBy('name')->get()
            )->resolve(),
            'featured_products' => ProductCardResource::collection(
                Product::active()->where('featured', true)->with(['images', 'category:id,name,slug'])
                    ->latest()->take(8)->get()
            )->resolve(),
            // "Numbers That Define Us" – editable in Admin › Home Counters
            'stats_section' => ['title' => $stats['title'], 'subtitle' => $stats['subtitle']],
            'stats' => $stats['items'],
            'clients' => ClientResource::collection(Client::active()->orderBy('sort_order')->latest()->get())->resolve(),
            'latest_blogs' => BlogCardResource::collection(
                Blog::active()->orderByDesc('published_at')->latest()->take(3)->get()
            )->resolve(),
        ]);
    }
}
