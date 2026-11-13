@extends('admin.layout')
@section('title', ($item->exists ? 'Edit FAQ' : 'Add FAQ').' – '.$product->name)
@section('content')
<x-form-page :title="($item->exists ? 'Edit FAQ' : 'Add FAQ').' – '.$product->name" :editing="$item->exists" :back="route('admin.products.faqs.index', $product)"
    :action="$item->exists ? route('admin.products.faqs.update', [$product, $item]) : route('admin.products.faqs.store', $product)">
    <x-field name="question" label="Question" :value="$item->question" required full placeholder="e.g. What is the warranty period?" />
    <x-editor name="answer" label="Answer" :value="$item->answer" toolbar="basic" required />
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" hint="Lower numbers are shown first." />
    <x-switch name="status" label="Status" :checked="$item->status" on="Show on website" off="Hidden" />
</x-form-page>
@endsection
