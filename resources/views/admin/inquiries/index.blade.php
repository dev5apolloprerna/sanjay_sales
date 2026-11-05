@extends('admin.layout')
@section('title', 'Inquiries')
@section('content')
<form class="page-head" method="GET">
    <h1>Inquiries <span class="muted" style="font-size:14px;font-weight:400">({{ $items->total() }}@if($unread) · {{ $unread }} unread @endif)</span></h1>
    <div class="filters">
        <select name="status" class="input" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="unread" @selected(request('status') === 'unread')>Unread only</option>
        </select>
        <input class="input search" name="q" value="{{ request('q') }}" placeholder="Name, email or phone…">
        <button class="btn ghost" type="submit">Search</button>
        @if(request()->hasAny(['q', 'status']))<a class="btn ghost" href="{{ url()->current() }}">Clear</a>@endif
    </div>
</form>
<div class="table-wrap">
@if($items->isEmpty())
    <div class="empty">No inquiries yet. Submissions from the website's "Inquire Now" form appear here.</div>
@else
<table>
    <thead><tr><th style="width:50px">#</th><th>Name</th><th>Contact</th><th>Message</th><th>Product</th><th>Received</th><th style="text-align:right">Actions</th></tr></thead>
    <tbody>
    @foreach($items as $i)
        <tr @if(! $i->is_read) style="font-weight:600" @endif>
            <td class="muted">{{ $items->firstItem() + $loop->index }}</td>
            <td>{{ $i->name }} @if(! $i->is_read)<span class="badge on">New</span>@endif</td>
            <td>{{ $i->phone }}<br><small class="muted">{{ $i->email }}</small></td>
            <td style="max-width:320px">{{ \Illuminate\Support\Str::limit($i->subject ? $i->subject.' – '.$i->message : $i->message, 90) }}</td>
            <td>{{ $i->product?->name ?? '—' }}</td>
            <td>{{ $i->created_at->format('d M Y, h:i A') }}</td>
            <td>
                <div class="actions">
                    <a class="btn sm ghost" href="{{ route('admin.inquiries.show', $i) }}">View</a>
                    <form method="POST" action="{{ route('admin.inquiries.destroy', $i) }}" data-confirm="Delete the inquiry from “{{ $i->name }}”? This cannot be undone.">
                        @csrf @method('DELETE')
                        <button class="icon-btn del" title="Delete"><x-icon name="trash" /></button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>
{{ $items->links('admin.partials.pagination') }}
@endsection
