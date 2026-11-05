@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="page-head"><h1>Welcome, {{ auth('admin')->user()->name }}</h1></div>
<div class="stats">
    @foreach([['categories','Categories','cat','admin.categories.index'],['subcategories','Subcategories','sub','admin.subcategories.index'],['products','Products','box','admin.products.index'],['gallery','Gallery Images','img','admin.gallery.index'],['clients','Clients','clients','admin.clients.index'],['videos','Videos','video','admin.videos.index'],['blogs','Blog Posts','blog','admin.blogs.index'],['catalogues','Catalogues','file','admin.catalogues.index'],['inquiries','Inquiries','mail','admin.inquiries.index']] as [$k,$label,$icon,$route])
        <a href="{{ route($route) }}" class="card stat"><div class="ic"><x-icon :name="$icon" /></div><div><b>{{ $counts[$k] }}</b><small>{{ $label }}</small></div></a>
    @endforeach
</div>
<div class="grid2" style="gap:16px">
    <div class="card">
        <div class="page-head" style="margin-bottom:10px"><h3 style="flex:1;margin:0">Recent Products</h3><a class="btn sm ghost" href="{{ route('admin.products.index') }}">View all</a></div>
        @forelse($recentProducts as $p)
            <div style="display:flex;gap:12px;align-items:center;padding:8px 0;border-bottom:1px solid var(--line)">
                @if($p->main_image_url)<img class="thumb" src="{{ $p->main_image_url }}" alt="">@else<div class="thumb"></div>@endif
                <div><b>{{ $p->name }}</b><br><small class="muted">{{ $p->category?->name }}@if($p->subcategory) › {{ $p->subcategory->name }}@endif</small></div>
            </div>
        @empty <p class="muted">No products yet.</p> @endforelse
    </div>
    <div class="card">
        <div class="page-head" style="margin-bottom:10px"><h3 style="flex:1;margin:0">Recent Blogs</h3><a class="btn sm ghost" href="{{ route('admin.blogs.index') }}">View all</a></div>
        @forelse($recentBlogs as $b)
            <div style="display:flex;gap:12px;align-items:center;padding:8px 0;border-bottom:1px solid var(--line)">
                @if($b->image_url)<img class="thumb" src="{{ $b->image_url }}" alt="">@else<div class="thumb"></div>@endif
                <div><b>{{ $b->title }}</b><br><small class="muted">{{ $b->published_at?->format('d M Y') }}</small></div>
            </div>
        @empty <p class="muted">No blog posts yet.</p> @endforelse
    </div>
</div>
@endsection
