<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $items = Blog::when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderByDesc('published_at')->latest()->paginate(15)->withQueryString();

        return view('admin.blogs.index', compact('items'));
    }

    public function create(Request $request)
    {
        return view('admin.blogs.form', ['item' => new Blog([
            'status' => true, 'published_at' => now(), 'author' => $request->user('admin')->name,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::store($request->file('image'), 'blogs');
        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog added successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.form', ['item' => $blog]);
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $this->validated($request, $blog);
        $data['image'] = Upload::replace($request->file('image'), $blog->image, 'blogs', $request->boolean('remove_image'));
        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        Upload::delete($blog->image);
        $blog->delete();

        return back()->with('success', 'Blog deleted.');
    }

    private function validated(Request $request, ?Blog $blog = null): array
    {
        $request->merge(['slug' => Str::slug($request->slug ?: $request->title)]);
        // the editor leaves "<br>" behind when emptied
        if (trim(strip_tags((string) $request->content, '<img>')) === '') {
            $request->merge(['content' => null]);
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:250'],
            'slug' => ['nullable', 'max:270', Rule::unique('blogs')->ignore($blog?->id)],
            'author' => ['nullable', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]) + ['status' => $request->boolean('status')];
    }
}
