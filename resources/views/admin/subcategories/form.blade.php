@extends('admin.layout')
@section('title', $item->exists ? 'Edit Subcategory' : 'Add Subcategory')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Subcategory' : 'Add Subcategory'" :editing="$item->exists" :back="route('admin.subcategories.index')"
    :action="$item->exists ? route('admin.subcategories.update', $item) : route('admin.subcategories.store')">
    <x-select name="category_id" label="Category" :options="$categories" :value="$item->category_id" required />
    <x-field name="name" label="Subcategory Name" :value="$item->name" required />
    <x-field name="slug" label="Slug (URL)" :value="$item->slug" data-slug-from="name" hint="Auto-generated from name" />
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" />
    <x-switch name="status" label="Status" :checked="$item->status" />
    <x-textarea name="description" label="Description" :value="$item->description" />
    <x-upload name="image" label="Subcategory Image" :current="$item->image_url" hint="JPG, PNG, WEBP — max 5MB" />
    <x-seo-fields :item="$item" />
</x-form-page>
@endsection
