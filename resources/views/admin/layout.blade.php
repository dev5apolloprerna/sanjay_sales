<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – Sanjay Sales Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=1">
</head>
<body>
@php
    $me = auth('admin')->user();
    $nav = [
        ['Main'],
        ['admin.dashboard', 'Dashboard', 'dash', 'admin.dashboard'],
        ['Catalogue'],
        ['admin.categories.index', 'Categories', 'cat', 'admin.categories.*'],
        ['admin.subcategories.index', 'Subcategories', 'sub', 'admin.subcategories.*'],
        ['admin.products.index', 'Products', 'box', 'admin.products.*'],
        ['Media & Content'],
        ['admin.gallery.index', 'Gallery', 'img', 'admin.gallery.*'],
        ['admin.clients.index', 'Our Clients', 'clients', 'admin.clients.*'],
        ['admin.videos.index', 'Video Gallery', 'video', 'admin.videos.*'],
        ['admin.blogs.index', 'Blogs', 'blog', 'admin.blogs.*'],
        ['admin.catalogues.index', 'Catalogues', 'file', 'admin.catalogues.*'],
        ['Website'],
        ['admin.seo.index', 'SEO Settings', 'seo', 'admin.seo.*'],
        ['admin.inquiries.index', 'Inquiries', 'mail', 'admin.inquiries.*'],
        /*['Account'],
        ['admin.profile', 'My Profile', 'user', 'admin.profile*'],
        ['admin.password', 'Change Password', 'lock', 'admin.password*'],*/
    ];
@endphp
<div class="shell">
    <aside class="side">
        <a href="{{ route('admin.dashboard') }}" class="brand"><img src="{{ asset('images/logo.png') }}" alt="Sanjay Sales – Welding & Cutting Related Products"></a>
        <nav class="nav">
            @foreach($nav as $n)
                @if(count($n) === 1)
                    <div class="group">{{ $n[0] }}</div>
                @else
                    <a href="{{ route($n[0]) }}" class="{{ request()->routeIs($n[3]) ? 'active' : '' }}"><x-icon :name="$n[2]" />{{ $n[1] }}</a>
                @endif
            @endforeach
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button><x-icon name="out" />Logout</button></form>
        </nav>
    </aside>
    <div class="overlay"></div>
    <div class="main">
        <header class="topbar">
            <button class="burger" type="button"><x-icon name="menu" width="22" height="22" /></button>
            <div class="title">@yield('title')</div>
            <div class="user">
                <button type="button">
                    <div class="avatar">@if($me->avatar_url)<img src="{{ $me->avatar_url }}" alt="">@else{{ strtoupper($me->initials) }}@endif</div>
                    <div style="text-align:left;line-height:1.2"><b style="font-size:13px">{{ $me->name }}</b><br><small class="muted">{{ $me->email }}</small></div>
                </button>
                <div class="menu" hidden>
                    <a href="{{ route('admin.profile') }}">My Profile</a>
                    <a href="{{ route('admin.password') }}">Change Password</a>
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button style="color:var(--danger)">Logout</button></form>
                </div>
            </div>
        </header>
        <main class="content">@yield('content')</main>
    </div>
</div>

{{-- flash toasts --}}
<div class="toasts">
    @if(session('success'))<div class="toast">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="toast error">{{ session('error') }}</div>@endif
</div>

{{-- delete confirmation --}}
<div class="modal-bg" id="confirm-modal" hidden>
    <div class="modal sm">
        <div class="modal-head"><h3>Please confirm</h3><button class="icon-btn" type="button" data-close-modal><x-icon name="x" /></button></div>
        <div class="modal-body"><p id="confirm-text" style="margin:0"></p></div>
        <div class="modal-foot"><button class="btn ghost" type="button" data-close-modal>Cancel</button><button class="btn danger" type="button" id="confirm-yes">Delete</button></div>
    </div>
</div>
<script src="{{ asset('js/admin.js') }}?v=1"></script>
</body>
</html>
