@extends('admin.layout')
@section('title', 'SEO Setup')
@section('content')
<div class="page-head">
    <h1>SEO Setup <span class="muted" style="font-size:14px;font-weight:400">({{ $items->count() }} pages)</span></h1>
</div>
<div class="card" style="margin-bottom:16px">
    <small class="muted">SEO for fixed website pages is managed here. SEO for individual categories, subcategories, products and blogs is set inside their own forms (SEO Settings section).</small>
</div>
<div class="table-wrap">
<table>
    <thead><tr><th style="width:50px">#</th><th>Page Name</th><th>Meta Title</th><th>Meta Description</th><th>Head / Body</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $p)
        @php $desc = trim(html_entity_decode(strip_tags((string) $p->meta_description), ENT_QUOTES | ENT_HTML5, 'UTF-8')); @endphp
        <tr>
            <td class="muted">{{ $loop->iteration }}</td>
            <td><b>{{ $p->label }}</b><br><small class="muted">{{ $p->page_key }}</small></td>
            <td style="max-width:260px">{{ $p->meta_title ?: '—' }}</td>
            <td style="max-width:340px">{{ \Illuminate\Support\Str::limit($desc, 90) ?: '—' }}</td>
            <td><small>Head: {{ filled($p->head) ? 'Yes' : '—' }}<br>Body: {{ filled($p->body) ? 'Yes' : '—' }}</small></td>
            <td><div class="actions"><a class="icon-btn" title="Edit" href="{{ route('admin.seo.edit', $p) }}"><x-icon name="edit" /></a></div></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
