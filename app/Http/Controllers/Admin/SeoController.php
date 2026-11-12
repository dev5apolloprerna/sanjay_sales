<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** SEO settings for the website's fixed pages (home, about, contact ...). */
class SeoController extends Controller
{
    public function index()
    {
        SeoPage::ensureDefaults();

        $order = array_keys(SeoPage::PAGES);
        $items = SeoPage::all()->sortBy(fn ($p) => array_search($p->page_key, $order))->values();

        return view('admin.seo.index', compact('items'));
    }

    public function edit(SeoPage $seo)
    {
        return view('admin.seo.form', ['item' => $seo, 'robots' => SeoPage::ROBOTS]);
    }

    public function update(Request $request, SeoPage $seo)
    {
        $data = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'robots' => ['nullable', Rule::in(array_keys(SeoPage::ROBOTS))],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'max:5120'],
        ]);

        $data['robots'] = $data['robots'] ?? 'index, follow';
        $data['og_image'] = Upload::replace($request->file('og_image'), $seo->og_image, 'seo', $request->boolean('remove_og_image'));
        $seo->update($data);

        return redirect()->route('admin.seo.index')->with('success', 'SEO settings updated successfully.');
    }
}
