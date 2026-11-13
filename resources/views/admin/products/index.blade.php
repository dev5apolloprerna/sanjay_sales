@extends('admin.layout')
@section('title', 'Products')
@section('content')
<x-list-head title="Products" :total="$items->total()" :create="route('admin.products.create')" singular="Product">
    <select class="input" name="category" onchange="this.form.subcategory.value='';this.form.submit()">
        <option value="">All categories</option>
        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
    </select>
    <select class="input" name="subcategory" data-depends-on="category" onchange="this.form.submit()">
        <option value="">All subcategories</option>
        @foreach($subcategories as $sc)<option value="{{ $sc->id }}" data-parent="{{ $sc->category_id }}" @selected(request('subcategory') == $sc->id)>{{ $sc->name }}</option>@endforeach
    </select>
</x-list-head>
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No products found. <a style="color:var(--accent)" href="{{ route('admin.products.create') }}">Add a product</a></div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Image</th><th>Product</th><th>Category / Subcategory</th><th>Price</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $p)
        <tr>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>@if($p->main_image_url)<img class="thumb" src="{{ $p->main_image_url }}" alt="">@else<div class="thumb"></div>@endif</td>
            <td><b>{{ $p->name }}</b> @if($p->featured)<span class="badge star">Featured</span>@endif<br>
                <small class="muted">{{ $p->brand ?: '—' }} · {{ $p->images->count() }} image(s)</small></td>
            <td>{{ $p->category?->name }}@if($p->subcategory)<br><small class="muted">› {{ $p->subcategory->name }}</small>@endif</td>
            <td>{!! $p->price ? e($p->price) : '<span class="muted">On request</span>' !!}</td>
            <td><x-status :on="$p->status" /></td>
            <td><x-row-actions :edit="route('admin.products.edit', $p)" :destroy="route('admin.products.destroy', $p)" :label="$p->name">
                <a class="icon-btn faq-btn" title="Technical Specifications ({{ $p->spec_rows_count }} rows)" href="{{ route('admin.products.specifications.edit', $p) }}"><x-icon name="specs" />@if($p->spec_rows_count)<span class="count">{{ $p->spec_rows_count }}</span>@endif</a>
                <a class="icon-btn faq-btn" title="FAQs ({{ $p->faqs_count }})" href="{{ route('admin.products.faqs.index', $p) }}"><x-icon name="faq" />@if($p->faqs_count)<span class="count">{{ $p->faqs_count }}</span>@endif</a>
            </x-row-actions></td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
