@extends('admin.layout')
@section('title', $item->exists ? 'Edit Blog' : 'Add Blog')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Blog' : 'Add Blog'" :editing="$item->exists" :back="route('admin.blogs.index')"
    :action="$item->exists ? route('admin.blogs.update', $item) : route('admin.blogs.store')">
    <x-field name="title" label="Blog Title" :value="$item->title" required full />
    @if($item->exists)<input type="hidden" name="slug" value="{{ $item->slug }}">@endif
    @error('slug')<div class="field full"><div class="error">{{ $message }}</div></div>@enderror
    <x-field name="author" label="Author" :value="$item->author" />
    <x-field name="published_at" type="date" label="Publish Date" :value="$item->published_at?->format('Y-m-d')" />
    <x-switch name="status" label="Status" :checked="$item->status" on="Published" off="Draft" />
    <x-upload name="image" label="Featured Image" :current="$item->image_url" wide />
    <x-textarea name="excerpt" label="Short Excerpt" :value="$item->excerpt" rows="2" />

    <x-editor name="content" label="Content" :value="$item->content" required />

    <x-seo-fields :item="$item" scripts />
</x-form-page>
@endsection
