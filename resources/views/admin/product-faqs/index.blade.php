@extends('admin.layout')
@section('title', 'FAQs – '.$product->name)
@section('content')
<div class="page-head">
    <a class="icon-btn" href="{{ route('admin.products.index') }}" title="Back to products"><x-icon name="back" /></a>
    <h1>FAQs <span class="muted" style="font-size:14px;font-weight:400">({{ $items->count() }})</span><br>
        <small class="muted" style="font-size:13px;font-weight:400">{{ $product->name }} · <a style="color:var(--accent)" href="{{ route('admin.products.edit', $product) }}">Edit product</a></small></h1>
    <a class="btn" style="margin-left:auto" href="{{ route('admin.products.faqs.create', $product) }}"><x-icon name="plus" width="16" height="16" /> Add FAQ</a>
</div>
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No FAQs for this product yet. <a style="color:var(--accent)" href="{{ route('admin.products.faqs.create', $product) }}">Add the first FAQ</a></div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Question</th><th>Answer</th><th style="width:70px">Order</th><th style="width:100px">Status</th><th style="text-align:right;width:110px">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $f)
        <tr>
            <td class="muted">{{ $loop->iteration }}</td>
            <td><b>{{ $f->question }}</b></td>
            <td class="muted">{{ \Illuminate\Support\Str::limit(\App\Support\Html::text($f->answer), 120) }}</td>
            <td>{{ $f->sort_order }}</td>
            <td><x-status :on="$f->status" /></td>
            <td><x-row-actions :edit="route('admin.products.faqs.edit', [$product, $f])" :destroy="route('admin.products.faqs.destroy', [$product, $f])" :label="$f->question" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
@endsection
