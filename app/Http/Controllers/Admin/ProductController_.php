<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Support\LineList;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $items = Product::with(['category', 'subcategory', 'images'])
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('brand', 'like', "%{$s}%")))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->when($request->subcategory, fn ($q, $c) => $q->where('subcategory_id', $c))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', [
            'items' => $items,
            'categories' => Category::orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'subcategories' => Subcategory::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'category_id']),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', ['item' => new Product(['status' => true])] + $this->formData());
    }

    public function store(Request $request)
    {
        $product = Product::create($this->validated($request));
        $this->saveImages($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['item' => $product->load('images')] + $this->formData());
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request, $product));

        // images ticked for removal
        $remove = $product->images()->whereIn('id', (array) $request->input('remove_images', []))->get();
        foreach ($remove as $img) {
            Upload::delete($img->path);
            $img->delete();
        }
        $this->saveImages($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            Upload::delete($img->path);
        }
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    /** Categories + all subcategories (the form filters subcategories by the chosen category). */
    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
            'subcategories' => Subcategory::orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'category_id']),
        ];
    }

    private function saveImages(Request $request, Product $product): void
    {
        $order = (int) $product->images()->max('sort_order');
        foreach ($request->file('images', []) as $file) {
            $product->images()->create(['path' => Upload::store($file, 'products'), 'sort_order' => ++$order]);
        }
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $request->merge(['slug' => Str::slug($request->slug ?: $request->name)]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'max:220', Rule::unique('products')->ignore($product?->id)],
            'category_id' => ['required', 'exists:categories,id'],
            // subcategory must belong to the selected category
            'subcategory_id' => ['nullable', Rule::exists('subcategories', 'id')->where('category_id', $request->category_id)],
            'brand' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'string', 'max:50'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string', 'max:5000'],
            'applications' => ['nullable', 'string', 'max:5000'],
            'specifications' => ['nullable', 'string', 'max:10000'],
            'faqs' => ['nullable', 'string', 'max:10000'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'max:5120'],
        ], ['category_id.required' => 'Please select a category.', 'subcategory_id.exists' => 'This subcategory does not belong to the selected category.', 'images.*.image' => 'Each file must be an image.'])
            + ['status' => $request->boolean('status'), 'featured' => $request->boolean('featured')];

        // textareas -> JSON columns used by the website's product detail page
        return array_merge($data, [
            'features' => LineList::list($request->features),
            'applications' => LineList::list($request->applications),
            'specifications' => LineList::specs($request->specifications),
            'faqs' => LineList::faqs($request->faqs),
        ]);
    }
}
