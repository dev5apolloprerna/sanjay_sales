@if ($paginator->hasPages())
<nav class="pager">
    <span class="info">Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</span>
    @if ($paginator->onFirstPage())<span class="dis">‹</span>@else<a href="{{ $paginator->previousPageUrl() }}">‹</a>@endif
    @foreach ($elements as $element)
        @if (is_string($element))<span class="dis">{{ $element }}</span>@endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())<span class="cur">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
            @endforeach
        @endif
    @endforeach
    @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">›</a>@else<span class="dis">›</span>@endif
</nav>
@endif
