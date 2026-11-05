@extends('admin.layout')
@section('title', 'Catalogues')
@section('content')
<x-list-head title="Catalogues" :total="$items->total()" :create="route('admin.catalogues.create')" singular="Catalogue" />
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No catalogues yet. <a style="color:var(--accent)" href="{{ route('admin.catalogues.create') }}">Upload the first one</a></div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Cover</th><th>Title</th><th>File</th><th>Order</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $c)
        <tr>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>@if($c->image_url)<img class="thumb" style="width:56px;height:72px" src="{{ $c->image_url }}" alt="">@else<div class="thumb" style="width:56px;height:72px"></div>@endif</td>
            <td><b>{{ $c->title }}</b></td>
            <td><a href="{{ $c->file_url }}" target="_blank" rel="noopener" style="color:var(--accent)">Open {{ strtoupper($c->file_type) }} ↗</a></td>
            <td>{{ $c->sort_order }}</td>
            <td><x-status :on="$c->status" /></td>
            <td><x-row-actions :edit="route('admin.catalogues.edit', $c)" :destroy="route('admin.catalogues.destroy', $c)" :label="$c->title" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
