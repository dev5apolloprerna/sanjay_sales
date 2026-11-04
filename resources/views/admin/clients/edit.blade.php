@extends('admin.layout')
@section('title', 'Edit Client')
@section('content')
<x-form-page title="Edit Client" editing :back="route('admin.clients.index')" :action="route('admin.clients.update', $item)">
    <x-field name="name" label="Client Name (optional)" :value="$item->name" hint="Used as the image alt text" />
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" />
    <x-switch name="status" label="Status" :checked="$item->status" />
    <x-upload name="image" label="Logo" :current="$item->image_url" :removable="false" hint="Upload a new file to replace the logo" />
</x-form-page>
@endsection
