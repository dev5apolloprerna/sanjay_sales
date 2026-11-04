@extends('admin.layout')
@section('title', $item->exists ? 'Edit Category' : 'Add Category')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Category' : 'Add Category'" :editing="$item->exists" :back="route('admin.categories.index')"
    :action="$item->exists ? route('admin.categories.update', $item) : route('admin.categories.store')">
    <x-field name="name" label="Category Name" :value="$item->name" required />
    <x-field name="slug" label="Slug (URL)" :value="$item->slug" data-slug-from="name" hint="Auto-generated from name" />
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" />
    <x-switch name="status" label="Status" :checked="$item->status" />
    <x-textarea name="description" label="Description" :value="$item->description" />
    <x-upload name="image" label="Category Image" :current="$item->image_url" hint="JPG, PNG, WEBP — max 5MB" />
</x-form-page>
@endsection
