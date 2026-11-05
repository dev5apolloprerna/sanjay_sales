<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlogCardResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends ApiController
{
    /** GET /api/blogs?page=1&per_page=6&q=welding */
    public function index(Request $request)
    {
        $blogs = Blog::active()
            ->when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderByDesc('published_at')->latest()
            ->paginate($this->perPage($request, 6))->withQueryString();

        return $this->ok(BlogCardResource::collection($blogs));
    }

    /** GET /api/blogs/{slug} – post + 3 recent posts for the sidebar / "read next". */
    public function show(string $slug)
    {
        $blog = Blog::active()->where('slug', $slug)->firstOrFail();

        $recent = Blog::active()->whereKeyNot($blog->id)
            ->orderByDesc('published_at')->latest()->take(3)->get();

        return $this->ok([
            'blog' => (new BlogResource($blog))->resolve(),
            'recent_posts' => BlogCardResource::collection($recent)->resolve(),
        ]);
    }
}
