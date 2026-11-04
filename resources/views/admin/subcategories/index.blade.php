@extends('admin.layout')
@section('title', 'Subcategories')
@section('content')
<x-list-head title="Subcategories" :total="$items->total()" :create="route('admin.subcategories.create', ['category' => request('category')])" singular="Subcategory">
    <select class="input" name="category" onchange="this.form.submit()">
        <option value="">All categories</option>
        @foreach($categories as $id => $name)<option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>@endforeach
    </select>
</x-list-head>
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No subcategories found. <a style="color:var(--accent)" href="{{ route('admin.subcategories.create', ['category' => request('category')]) }}">Add a subcategory</a></div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Image</th><th>Subcategory</th><th>Category</th><th>Products</th><th>Order</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $s)
        <tr>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>@if($s->image_url)<img class="thumb" src="{{ $s->image_url }}" alt="">@else<div class="thumb"></div>@endif</td>
            <td><b>{{ $s->name }}</b><br><small class="muted">/{{ $s->category?->slug }}/{{ $s->slug }}</small></td>
            <td><a href="{{ route('admin.subcategories.index', ['category' => $s->category_id]) }}" style="color:var(--accent)">{{ $s->category?->name }}</a></td>
            <td><a href="{{ route('admin.products.index', ['category' => $s->category_id, 'subcategory' => $s->id]) }}">{{ $s->products_count }}</a></td>
            <td>{{ $s->sort_order }}</td>
            <td><x-status :on="$s->status" /></td>
            <td><x-row-actions :edit="route('admin.subcategories.edit', $s)" :destroy="route('admin.subcategories.destroy', $s)" :label="$s->name" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
