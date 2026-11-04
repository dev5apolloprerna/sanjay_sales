<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Support\Upload;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $items = Gallery::when($request->q, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->orderBy('sort_order')->latest()->paginate(24)->withQueryString();

        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gallery.form', ['item' => new Gallery(['status' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image'] = Upload::store($request->file('image'), 'gallery');
        Gallery::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image added successfully.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.form', ['item' => $gallery]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validated($request, false);
        $data['image'] = Upload::replace($request->file('image'), $gallery->image, 'gallery');
        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        Upload::delete($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Gallery image deleted.');
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:5120'],
        ]) + ['status' => $request->boolean('status'), 'sort_order' => (int) $request->sort_order];
    }
}
