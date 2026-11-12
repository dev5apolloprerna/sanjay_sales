@extends('admin.layout')
@section('title', $item->exists ? 'Edit Blog' : 'Add Blog')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Blog' : 'Add Blog'" :editing="$item->exists" :back="route('admin.blogs.index')"
    :action="$item->exists ? route('admin.blogs.update', $item) : route('admin.blogs.store')">
    <x-field name="title" label="Blog Title" :value="$item->title" required full />
    <x-field name="slug" label="Slug (URL)" :value="$item->slug" data-slug-from="title" />
    <x-field name="author" label="Author" :value="$item->author" />
    <x-field name="published_at" type="date" label="Publish Date" :value="$item->published_at?->format('Y-m-d')" />
    <x-switch name="status" label="Status" :checked="$item->status" on="Published" off="Draft" />
    <x-upload name="image" label="Featured Image" :current="$item->image_url" wide />
    <x-textarea name="excerpt" label="Short Excerpt" :value="$item->excerpt" rows="2" />

    <div class="field full">
        <label>Content <span class="req">*</span></label>
        <div class="rte" data-rte="content">
            <div class="rte-bar">
                <button type="button" data-cmd="bold" style="font-weight:800">B</button><button type="button" data-cmd="italic" style="font-style:italic">I</button>
                <button type="button" data-cmd="underline" style="text-decoration:underline">U</button><button type="button" data-cmd="formatBlock:h2">H2</button>
                <button type="button" data-cmd="formatBlock:h3">H3</button><button type="button" data-cmd="formatBlock:p">P</button>
                <button type="button" data-cmd="insertUnorderedList">• List</button><button type="button" data-cmd="insertOrderedList">1. List</button>
                <button type="button" data-cmd="formatBlock:blockquote">Quote</button>
                <button type="button" data-cmd="createLink">Link</button><button type="button" data-cmd="removeFormat">Clear</button>
            </div>
            <div class="rte-area" contenteditable="true"></div>
        </div>
        <textarea name="content" hidden>{{ old('content', $item->content) }}</textarea>
        @error('content')<div class="error">{{ $message }}</div>@enderror
    </div>

    <x-seo-fields :item="$item" />
</x-form-page>
@endsection
