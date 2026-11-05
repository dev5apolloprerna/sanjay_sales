<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Catalogue;
use App\Models\Category;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Video;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                'categories' => Category::count(),
                'subcategories' => Subcategory::count(),
                'products' => Product::count(),
                'gallery' => Gallery::count(),
                'clients' => Client::count(),
                'videos' => Video::count(),
                'blogs' => Blog::count(),
                'catalogues' => Catalogue::count(),
                'inquiries' => Inquiry::count(),
            ],
            'recentProducts' => Product::with(['category', 'subcategory', 'images'])->latest()->take(5)->get(),
            'recentBlogs' => Blog::latest()->take(5)->get(),
        ]);
    }
}
