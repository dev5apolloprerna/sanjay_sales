@extends('admin.layout')
@section('title', $item->exists ? 'Edit Product' : 'Add Product')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Product' : 'Add Product'" :editing="$item->exists" :back="route('admin.products.index')"
    :action="$item->exists ? route('admin.products.update', $item) : route('admin.products.store')">
    <x-field name="name" label="Product Name" :value="$item->name" required />
    @if($item->exists)<input type="hidden" name="slug" value="{{ $item->slug }}">@endif
    @error('slug')<div class="field full"><div class="error">{{ $message }}</div></div>@enderror
    <x-select name="category_id" label="Category" :options="$categories->pluck('name', 'id')" :value="$item->category_id" required />
    <div class="field">
        <label for="f_subcategory_id">Subcategory</label>
        @php $sub = (string) old('subcategory_id', $item->subcategory_id); @endphp
        <select id="f_subcategory_id" name="subcategory_id" data-depends-on="category_id" @class(['input', 'invalid' => $errors->has('subcategory_id')])>
            <option value="">— Select Subcategory —</option>
            @foreach($subcategories as $sc)
                <option value="{{ $sc->id }}" data-parent="{{ $sc->category_id }}" @selected($sub === (string) $sc->id)>{{ $sc->name }}</option>
            @endforeach
        </select>
        <div class="hint" data-empty-hint hidden>No subcategories in this category yet — <a href="{{ route('admin.subcategories.create') }}" style="color:var(--accent)">add one</a>.</div>
        @error('subcategory_id')<div class="error">{{ $message }}</div>@enderror
    </div>
    <x-field name="brand" label="Brand" :value="$item->brand" placeholder="e.g. ESAB, Ador, Bosch" />
    <x-field name="price" label="Price" :value="$item->price" placeholder="e.g. ₹12,500 — leave blank for 'On request'" />
    <x-switch name="featured" label="Featured" :checked="$item->featured" on="Show on homepage" off="Not featured" />
    <x-switch name="status" label="Status" :checked="$item->status" />
    <x-editor name="short_description" label="Short Description" :value="$item->short_description" toolbar="basic"
        hint="Shown on product cards and at the top of the product page." />
    <x-editor name="description" label="Full Description / Specifications" :value="$item->description" />
    <x-editor name="features" label="Key Features" :value="$item->features" toolbar="basic"
        hint="Tip: use the bullet list button – one feature per point." />
    <x-editor name="applications" label="Applications" :value="$item->applications" toolbar="basic"
        hint="Tip: use the bullet list button – one application per point." />
    @if($item->exists)
        <div class="field full">
            <label>Technical Specifications &amp; FAQs</label>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <a class="btn ghost sm" href="{{ route('admin.products.specifications.edit', $item) }}"><x-icon name="specs" width="14" height="14" /> Manage Specifications ({{ $item->specRows()->count() }})</a>
                <a class="btn ghost sm" href="{{ route('admin.products.faqs.index', $item) }}"><x-icon name="faq" width="14" height="14" /> Manage FAQs ({{ $item->faqs()->count() }})</a>
            </div>
        </div>
    @else
        <div class="field full"><div class="hint">After saving, use the <b>Specifications</b> and <b>FAQ</b> buttons in the product list to add technical specifications and FAQs for this product.</div></div>
    @endif

    <div class="field full" data-multi-preview>
        <label>Product Images</label>
        <div class="drop"><input type="file" name="images[]" accept="image/*" multiple><x-icon name="upload" width="20" height="20" /><div>Click or drop images (multiple allowed, max 10)</div></div>
        @if($item->exists && $item->images->count())
            <div class="previews">
                @foreach($item->images as $img)
                    <div class="pv"><img src="{{ $img->url }}" alt="">@if($loop->first)<span class="tag">main</span>@endif
                        <label class="rm" title="Remove"><input type="checkbox" name="remove_images[]" value="{{ $img->id }}">×</label></div>
                @endforeach
            </div>
        @endif
        <div class="previews new"></div>
        <div class="hint">The first image is used as the main image. Max 5MB each.</div>
        @error('images')<div class="error">{{ $message }}</div>@enderror
        @error('images.*')<div class="error">{{ $message }}</div>@enderror
    </div>

    <x-seo-fields :item="$item" />
</x-form-page>
@endsection
