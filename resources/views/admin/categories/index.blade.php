@extends('admin.layout')
@section('title', 'Product Categories')
@section('content')
<x-list-head title="Product Categories" :total="$items->total()" :create="route('admin.categories.create')" singular="Category" />
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No categories found.</div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Image</th><th>Name</th><th>Subcategories</th><th>Products</th><th>Order</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $c)
        <tr>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>@if($c->image_url)<img class="thumb" src="{{ $c->image_url }}" alt="">@else<div class="thumb"></div>@endif</td>
            <td><b>{{ $c->name }}</b><br><small class="muted">/{{ $c->slug }}</small></td>
            <td><a href="{{ route('admin.subcategories.index', ['category' => $c->id]) }}" style="color:var(--accent)">{{ $c->subcategories_count }}</a></td>
            <td>{{ $c->products_count }}</td>
            <td>{{ $c->sort_order }}</td>
            <td><x-status :on="$c->status" /></td>
            <td><x-row-actions :edit="route('admin.categories.edit', $c)" :destroy="route('admin.categories.destroy', $c)" :label="$c->name" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
