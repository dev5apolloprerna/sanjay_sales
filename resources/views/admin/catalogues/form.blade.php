@extends('admin.layout')
@section('title', $item->exists ? 'Edit Catalogue' : 'Add Catalogue')
@section('content')
<x-form-page :title="$item->exists ? 'Edit Catalogue' : 'Add Catalogue'" :editing="$item->exists" :back="route('admin.catalogues.index')"
    :action="$item->exists ? route('admin.catalogues.update', $item) : route('admin.catalogues.store')">
    <x-field name="title" label="Title" :value="$item->title" required full placeholder="e.g. Welding Solutions" />
    <x-field name="sort_order" type="number" label="Display Order" :value="$item->sort_order" />
    <x-switch name="status" label="Status" :checked="$item->status" />
    <div class="field full">
        <label for="f_file">Catalogue File @if(! $item->exists)<span class="req">*</span>@endif</label>
        <input id="f_file" type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" data-max-mb="10" @class(['input', 'invalid' => $errors->has('file')])>
        @if($item->file)<div class="hint">Current: <a href="{{ $item->file_url }}" target="_blank" rel="noopener" style="color:var(--accent)">{{ basename($item->file) }}</a> — choose a new file to replace it.</div>
        @else<div class="hint">PDF (recommended), Word, Excel or ZIP — max 10MB</div>@endif
        @error('file')<div class="error">{{ $message }}</div>@enderror
    </div>
</x-form-page>
<script>
// Instant check so a too-big file is caught before uploading
document.getElementById('f_file').addEventListener('change', function () {
    var max = parseFloat(this.dataset.maxMb), f = this.files[0];
    if (f && f.size > max * 1024 * 1024) {
        alert('"' + f.name + '" is ' + (f.size / 1048576).toFixed(1) + ' MB. The maximum is ' + max + ' MB.');
        this.value = '';
    }
});
</script>
@endsection
