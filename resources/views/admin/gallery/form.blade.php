@extends('admin.layout')
@section('title', $item->exists ? 'Edit Gallery Image' : 'Add Gallery Image')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Gallery Image' : 'Add Gallery Image'" :editing="$item->exists" :back="route('admin.gallery.index')"
    :action="$item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store')">
    <x-field name="title" label="Title" :value="$item->title" required full />
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" />
    <x-switch name="status" label="Status" :checked="$item->status" />
    <x-upload name="image" label="Image" :current="$item->image_url" required :removable="false" wide hint="JPG, PNG, WEBP — max 5MB" />
</x-form-page>
@endsection
