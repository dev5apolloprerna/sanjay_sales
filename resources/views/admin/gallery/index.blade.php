@extends('admin.layout')
@section('title', 'Gallery')
@section('content')
<x-list-head title="Gallery" :total="$items->total()" :create="route('admin.gallery.create')" singular="Image" />
@if($items->isEmpty())
    <div class="table-wrap"><div class="empty">No gallery images yet. <a style="color:var(--accent)" href="{{ route('admin.gallery.create') }}">Upload the first one</a></div></div>
@else
<div class="gal-grid">
    @foreach($items as $g)
        <div class="gal-card">
            <img src="{{ $g->image_url }}" alt="{{ $g->title }}" loading="lazy">
            <div class="b"><b title="{{ $g->title }}">{{ $g->title }}</b><x-status :on="$g->status" />
                <x-row-actions :edit="route('admin.gallery.edit', $g)" :destroy="route('admin.gallery.destroy', $g)" :label="$g->title" /></div>
        </div>
    @endforeach
</div>
@endif
{{ $items->links('admin.partials.pagination') }}
@endsection
