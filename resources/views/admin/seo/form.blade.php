@extends('admin.layout')
@section('title', 'Edit SEO – '.$item->label)
@section('content')
<x-form-page :title="'SEO – '.$item->label" editing :back="route('admin.seo.index')" :action="route('admin.seo.update', $item)">
    <x-field name="meta_title" label="Meta Title" :value="$item->meta_title" full hint="Recommended: up to 60 characters" />
    <x-textarea name="meta_description" label="Meta Description" :value="$item->meta_description" rows="3" hint="Recommended: up to 160 characters" />
    <x-field name="meta_keywords" label="Meta Keywords" :value="$item->meta_keywords" full hint="Comma separated" />
    <x-field name="canonical_url" type="url" label="Canonical URL (optional)" :value="$item->canonical_url" placeholder="https://www.yoursite.com/" />
    <x-select name="robots" label="Robots" :options="$robots" :value="$item->robots" />
    <x-field name="og_title" label="Social Share Title (OG)" :value="$item->og_title" full hint="Leave blank to use the Meta Title" />
    <x-textarea name="og_description" label="Social Share Description (OG)" :value="$item->og_description" rows="2" hint="Leave blank to use the Meta Description" />
    <x-upload name="og_image" label="Social Share Image (OG)" :current="$item->og_image_url" hint="1200 × 630 px recommended — JPG, PNG, WEBP, max 5MB" />
</x-form-page>
@endsection
