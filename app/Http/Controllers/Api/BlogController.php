<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlogCardResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends ApiController
{
    /** POST /api/blogs   body: { "q": "welding" }  (optional, no pagination – returns all blogs) */
    public function index(Request $request)
    {
        $request->validate(['q' => ['nullable', 'string', 'max:100']]);

        $blogs = Blog::active()
            ->when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderByDesc('published_at')->latest()
            ->get();

        return $this->ok(BlogCardResource::collection($blogs));
    }

    /** POST /api/blogs/detail   body: { "slug": "welding-tips" } – post + 3 recent posts. */
    public function show(Request $request)
    {
        $slug = $request->validate(['slug' => ['required', 'string', 'max:270']])['slug'];

        $blog = Blog::active()->where('slug', $slug)->firstOrFail();

        $recent = Blog::active()->whereKeyNot($blog->id)
            ->orderByDesc('published_at')->latest()->take(3)->get();

        return $this->ok([
            'blog' => (new BlogResource($blog))->resolve(),
            'recent_posts' => BlogCardResource::collection($recent)->resolve(),
        ]);
    }
}
