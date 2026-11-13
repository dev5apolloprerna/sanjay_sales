@extends('admin.layout')
@section('title', $item->exists ? 'Edit Product' : 'Add Product')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Product' : 'Add Product'" :editing="$item->exists" :back="route('admin.products.index')"
    :action="$item->exists ? route('admin.products.update', $item) : route('admin.products.store')">
    <x-field name="name" label="Product Name" :value="$item->name" required />
    <x-field name="slug" label="Slug (URL)" :value="$item->slug" data-slug-from="name" hint="Auto-generated from name" />
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
    <x-editor name="specifications" label="Technical Specifications" :value="$item->specifications"
        hint="Tip: use the Table button for a Label | Value specification table. Headings can separate groups." />

    {{-- FAQ: question + rich-text answer, add / remove rows --}}
    @php
        // keep old() keys as-is so validation errors line up with their rows
        $faqs = old('faqs', array_values($item->faqs ?? []));
        $faqs = is_array($faqs) ? $faqs : [];
    @endphp
    <div class="field full" data-repeater data-next="{{ $faqs ? max(array_keys($faqs)) + 1 : 0 }}">
        <label>FAQs</label>
        <div data-repeater-list>
            @foreach($faqs as $i => $faq)
                @include('admin.products.partials.faq-row', ['i' => $i, 'faq' => $faq])
            @endforeach
        </div>
        <div class="hint" data-repeater-empty>No FAQs yet for this product.</div>
        <template data-repeater-template>
            @include('admin.products.partials.faq-row', ['i' => '__INDEX__', 'faq' => []])
        </template>
        <div><button type="button" class="btn ghost sm" data-repeater-add><x-icon name="plus" width="14" height="14" /> Add FAQ</button></div>
        @error('faqs')<div class="error">{{ $message }}</div>@enderror
    </div>

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
