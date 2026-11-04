{{-- Page heading with count, search box, optional extra filters (slot) and "Add" button --}}
@props(['title', 'total', 'create', 'singular'])
<form class="page-head" method="GET">
    <h1>{{ $title }} <span class="muted" style="font-size:14px;font-weight:400">({{ $total }})</span></h1>
    <div class="filters">
        {{ $slot }}
        <input class="input search" name="q" value="{{ request('q') }}" placeholder="Search…">
        <button class="btn ghost" type="submit">Search</button>
        @if(request()->hasAny(['q', 'category', 'subcategory']))<a class="btn ghost" href="{{ url()->current() }}">Clear</a>@endif
    </div>
    <a class="btn" href="{{ $create }}"><x-icon name="plus" width="16" height="16" /> Add {{ $singular }}</a>
</form>
