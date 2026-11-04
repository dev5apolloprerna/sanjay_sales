@extends('admin.layout')
@section('title', 'Upload Client Logos')
@section('content')
<div class="page-head">
    <a class="icon-btn" href="{{ route('admin.clients.index') }}" title="Back"><x-icon name="back" /></a>
    <h1>Upload Client Logos</h1>
</div>
<form class="card form-card" method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="field" data-multi-preview>
        <label>Client Logos <span class="req">*</span></label>
        <div class="drop"><input type="file" name="images[]" accept="image/*" multiple required><x-icon name="upload" width="20" height="20" /><div>Click or drop logos here — you can select many at once (max 30)</div></div>
        <div class="previews new"></div>
        <div class="hint">PNG with a transparent background looks best. Max 5MB each.</div>
        @error('images')<div class="error">{{ $message }}</div>@enderror
        @error('images.*')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="form-actions">
        <button class="btn" type="submit">Upload</button>
        <a class="btn ghost" href="{{ route('admin.clients.index') }}">Cancel</a>
    </div>
</form>
@endsection
