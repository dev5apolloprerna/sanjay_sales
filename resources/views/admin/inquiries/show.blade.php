@extends('admin.layout')
@section('title', 'Inquiry from '.$item->name)
@section('content')
<div class="page-head">
    <a class="icon-btn" href="{{ route('admin.inquiries.index') }}" title="Back"><x-icon name="back" /></a>
    <h1>Inquiry from {{ $item->name }}</h1>
</div>
<div class="card form-card">
    <table>
        <tbody>
            <tr><th style="width:160px;text-align:left">Name</th><td>{{ $item->name }}</td></tr>
            <tr><th style="text-align:left">Phone</th><td><a href="tel:{{ preg_replace('/[^0-9+]/', '', $item->phone) }}" style="color:var(--accent)">{{ $item->phone }}</a></td></tr>
            <tr><th style="text-align:left">Email</th><td>@if($item->email)<a href="mailto:{{ $item->email }}" style="color:var(--accent)">{{ $item->email }}</a>@else — @endif</td></tr>
            <tr><th style="text-align:left">Subject</th><td>{{ $item->subject ?: '—' }}</td></tr>
            <tr><th style="text-align:left">Product</th><td>{{ $item->product?->name ?? '—' }}</td></tr>
            <tr><th style="text-align:left">Source</th><td>{{ ucfirst($item->source) }} page</td></tr>
            <tr><th style="text-align:left">Received</th><td>{{ $item->created_at->format('d M Y, h:i A') }}</td></tr>
            <tr><th style="text-align:left;vertical-align:top">Message</th><td style="white-space:pre-line">{{ $item->message }}</td></tr>
        </tbody>
    </table>
    <div class="form-actions">
        @if($item->email)<a class="btn" href="mailto:{{ $item->email }}?subject={{ rawurlencode('Re: Your inquiry – Sanjay Sales') }}">Reply by Email</a>@endif
        <form method="POST" action="{{ route('admin.inquiries.destroy', $item) }}" data-confirm="Delete this inquiry? This cannot be undone." style="display:inline">
            @csrf @method('DELETE')
            <button class="btn ghost" type="submit">Delete</button>
        </form>
    </div>
</div>
@endsection
