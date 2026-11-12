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
    <x-textarea name="short_description" label="Short Description" :value="$item->short_description" rows="2" />

    <div class="field full">
        <label>Full Description / Specifications</label>
        <div class="rte" data-rte="description">
            <div class="rte-bar">
                <button type="button" data-cmd="bold" style="font-weight:800">B</button><button type="button" data-cmd="italic" style="font-style:italic">I</button>
                <button type="button" data-cmd="underline" style="text-decoration:underline">U</button><button type="button" data-cmd="formatBlock:h2">H2</button>
                <button type="button" data-cmd="formatBlock:h3">H3</button><button type="button" data-cmd="formatBlock:p">P</button>
                <button type="button" data-cmd="insertUnorderedList">• List</button><button type="button" data-cmd="insertOrderedList">1. List</button>
                <button type="button" data-cmd="createLink">Link</button><button type="button" data-cmd="removeFormat">Clear</button>
            </div>
            <div class="rte-area" contenteditable="true"></div>
        </div>
        <textarea name="description" hidden>{{ old('description', $item->description) }}</textarea>
    </div>

    <x-textarea name="features" label="Key Features" :value="\App\Support\LineList::listText($item->features)" rows="5"
        hint="One feature per line." placeholder="High speed &amp; precision cutting&#10;Heavy-duty welded structure" />
    <x-textarea name="applications" label="Applications" :value="\App\Support\LineList::listText($item->applications)" rows="5"
        hint="One application per line." placeholder="Metal fabrication&#10;Sheet metal industry" />
    <x-textarea name="specifications" label="Technical Specifications" :value="\App\Support\LineList::specsText($item->specifications)" rows="7"
        hint="One per line:  Group | Label | Value   (e.g.  General | Machine Type | CNC Plasma Cutter ). Without a group it goes under “General”." />
    <x-textarea name="faqs" label="FAQ" :value="\App\Support\LineList::faqsText($item->faqs)" rows="6"
        hint="One per line:  Question | Answer" />

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
