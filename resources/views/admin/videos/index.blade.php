@extends('admin.layout')
@section('title', 'Video Gallery')
@section('content')
<x-list-head title="Video Gallery" :total="$items->total()" :create="route('admin.videos.create')" singular="Video" />
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No videos yet.</div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Preview</th><th>Title</th><th>Link</th><th>Order</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $v)
        <tr>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>@if($v->thumbnail_url)<img class="thumb" style="width:96px;height:54px" src="{{ $v->thumbnail_url }}" alt="">@else<div class="thumb" style="width:96px;height:54px"></div>@endif</td>
            <td><b>{{ $v->title }}</b></td>
            <td><a href="{{ $v->youtube_url }}" target="_blank" rel="noopener" style="color:var(--accent)">Watch on YouTube ↗</a></td>
            <td>{{ $v->sort_order }}</td>
            <td><x-status :on="$v->status" /></td>
            <td><x-row-actions :edit="route('admin.videos.edit', $v)" :destroy="route('admin.videos.destroy', $v)" :label="$v->title" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
