<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $items = Category::withCount(['products', 'subcategories'])
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('items'));
    }

    public function create()
    {
        return view('admin.categories.form', ['item' => new Category(['status' => true, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::store($request->file('image'), 'categories');
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', ['item' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);
        $data['image'] = Upload::replace($request->file('image'), $category->image, 'categories', $request->boolean('remove_image'));
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($count = $category->products()->count()) {
            return back()->with('error', "This category has {$count} product(s). Move or delete them first.");
        }
        if ($count = $category->subcategories()->count()) {
            return back()->with('error', "This category has {$count} subcategory(s). Delete them first.");
        }
        Upload::delete($category->image);
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $request->merge(['slug' => Str::slug($request->slug ?: $request->name)]);

        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'max:170', Rule::unique('categories')->ignore($category?->id)],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:5120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]) + ['status' => $request->boolean('status'), 'sort_order' => (int) $request->sort_order];
    }
}
