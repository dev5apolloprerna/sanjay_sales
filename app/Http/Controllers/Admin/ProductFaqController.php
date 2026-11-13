<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFaq;
use App\Support\EditorInput;
use App\Support\Html;
use Illuminate\Http\Request;

/** FAQs of one product:  /admin/products/{product}/faqs */
class ProductFaqController extends Controller
{
    public function index(Product $product)
    {
        $items = $product->faqs()->get();

        return view('admin.product-faqs.index', compact('product', 'items'));
    }

    public function create(Product $product)
    {
        $item = new ProductFaq(['status' => true, 'sort_order' => (int) $product->faqs()->max('sort_order') + 1]);

        return view('admin.product-faqs.form', compact('product', 'item'));
    }

    public function store(Request $request, Product $product)
    {
        $product->faqs()->create($this->validated($request));

        return redirect()->route('admin.products.faqs.index', $product)->with('success', 'FAQ added successfully.');
    }

    public function edit(Product $product, ProductFaq $faq)
    {
        return view('admin.product-faqs.form', ['product' => $product, 'item' => $faq]);
    }

    public function update(Request $request, Product $product, ProductFaq $faq)
    {
        $faq->update($this->validated($request));

        return redirect()->route('admin.products.faqs.index', $product)->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Product $product, ProductFaq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted.');
    }

    private function validated(Request $request): array
    {
        EditorInput::decode($request); // CKEditor HTML arrives base64-encoded (ModSecurity)

        $request->merge(['answer' => Html::clean($request->answer)]);

        return $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string', 'max:20000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], ['answer.required' => 'Please enter the answer.']) + [
            'status' => $request->boolean('status'),
            'sort_order' => (int) $request->sort_order,
        ];
    }
}
