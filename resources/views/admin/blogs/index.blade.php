@extends('admin.layout')
@section('title', 'Blogs')
@section('content')
<x-list-head title="Blogs" :total="$items->total()" :create="route('admin.blogs.create')" singular="Blog" />
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No blog posts yet.</div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Image</th><th>Title</th><th>Author</th><th>Date</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $b)
        <tr>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>@if($b->image_url)<img class="thumb" style="width:80px;height:52px" src="{{ $b->image_url }}" alt="">@else<div class="thumb" style="width:80px;height:52px"></div>@endif</td>
            <td><b>{{ $b->title }}</b><br><small class="muted">/{{ $b->slug }}</small></td>
            <td>{{ $b->author ?: '—' }}</td>
            <td>{{ $b->published_at?->format('d M Y') }}</td>
            <td><x-status :on="$b->status" yes="Published" no="Draft" /></td>
            <td><x-row-actions :edit="route('admin.blogs.edit', $b)" :destroy="route('admin.blogs.destroy', $b)" :label="$b->title" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
