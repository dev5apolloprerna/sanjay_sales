<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Support\EditorInput;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $items = Subcategory::with('category')->withCount('products')
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->orderBy(Category::select('sort_order')->whereColumn('categories.id', 'subcategories.category_id'))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.subcategories.index', ['items' => $items, 'categories' => $this->categories()]);
    }

    public function create(Request $request)
    {
        return view('admin.subcategories.form', [
            'item' => new Subcategory(['status' => true, 'sort_order' => 0, 'category_id' => $request->category]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::store($request->file('image'), 'subcategories');
        Subcategory::create($data);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory added successfully.');
    }

    public function edit(Subcategory $subcategory)
    {
        return view('admin.subcategories.form', ['item' => $subcategory, 'categories' => $this->categories()]);
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $this->validated($request, $subcategory);
        $data['image'] = Upload::replace($request->file('image'), $subcategory->image, 'subcategories', $request->boolean('remove_image'));
        $subcategory->update($data);

        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
        if ($count = $subcategory->products()->count()) {
            return back()->with('error', "This subcategory has {$count} product(s). Move or delete them first.");
        }
        Upload::delete($subcategory->image);
        $subcategory->delete();

        return back()->with('success', 'Subcategory deleted.');
    }

    private function categories()
    {
        return Category::orderBy('sort_order')->orderBy('name')->pluck('name', 'id');
    }

    private function validated(Request $request, ?Subcategory $item = null): array
    {
        EditorInput::decode($request); // Head/Body scripts arrive base64-encoded (ModSecurity)

        $request->merge(['slug' => Str::slug($request->slug ?: $request->name)]);

        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'max:170',
                Rule::unique('subcategories')->where('category_id', $request->category_id)->ignore($item?->id)],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'image' => ['nullable', 'image', 'max:5120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'head' => ['nullable', 'string', 'max:65000'],
            'body' => ['nullable', 'string', 'max:65000'],
        ], [
            'category_id.required' => 'Please select a category.',
            'slug.unique' => 'This category already has a subcategory with this name/slug.',
        ]) + ['status' => $request->boolean('status'), 'sort_order' => (int) $request->sort_order];
    }
}
