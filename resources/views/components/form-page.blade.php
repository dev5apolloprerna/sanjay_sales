{{-- Wrapper for create/edit pages --}}
@props(['title', 'action', 'back', 'editing' => false])
<div class="page-head">
    <a class="icon-btn" href="{{ $back }}" title="Back"><x-icon name="back" /></a>
    <h1>{{ $title }}</h1>
</div>
<form class="card form-card" method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if($editing) @method('PUT') @endif
    @if($errors->any())
        <div class="alert">Please fix the highlighted fields below.</div>
    @endif
    <div class="grid2">{{ $slot }}</div>
    <div class="form-actions">
        <button class="btn" type="submit">{{ $editing ? 'Update' : 'Save' }}</button>
        <a class="btn ghost" href="{{ $back }}">Cancel</a>
    </div>
</form>
