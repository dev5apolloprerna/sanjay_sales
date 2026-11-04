@extends('admin.layout')
@section('title', 'Our Clients')
@section('content')
<div class="page-head">
    <h1>Our Clients <span class="muted" style="font-size:14px;font-weight:400">({{ $items->total() }})</span></h1>
    <a class="btn" href="{{ route('admin.clients.create') }}"><x-icon name="plus" width="16" height="16" /> Upload Logos</a>
</div>
@if($items->isEmpty())
    <div class="table-wrap"><div class="empty">No client logos yet. <a style="color:var(--accent)" href="{{ route('admin.clients.create') }}">Upload logos</a></div></div>
@else
<div class="gal-grid client-grid">
    @foreach($items as $c)
        <div class="gal-card">
            <div class="logo-box"><img src="{{ $c->image_url }}" alt="{{ $c->name }}" loading="lazy"></div>
            <div class="b"><b title="{{ $c->name }}">{{ $c->name ?: 'Client' }}</b><x-status :on="$c->status" />
                <x-row-actions :edit="route('admin.clients.edit', $c)" :destroy="route('admin.clients.destroy', $c)" :label="$c->name ?: 'this logo'" /></div>
        </div>
    @endforeach
</div>
@endif
{{ $items->links('admin.partials.pagination') }}
@endsection
