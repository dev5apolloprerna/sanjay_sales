@extends('admin.layout')
@section('title', 'SEO Setup – Edit Page SEO')
@section('content')
<style>
.seo-head{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;margin-bottom:16px}
.seo-head .crumb{color:var(--muted);font-size:13px;margin-bottom:6px}
.seo-head h1{margin:0}
.seo-code{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:13px;line-height:1.5;min-height:140px;resize:vertical;white-space:pre}
.seo-desc .ck-editor__editable_inline{min-height:100px}
.seo-desc.invalid .ck.ck-editor__main>.ck-editor__editable{border-color:#dc2626}
</style>

<div class="seo-head">
    <div>
        <div class="crumb">Admin / SEO Setup</div>
        <h1>SEO Setup › Edit Page SEO</h1>
    </div>
    <a class="btn ghost" href="{{ route('admin.seo.index') }}">← Back to List</a>
</div>

<form class="card form-card" method="POST" action="{{ route('admin.seo.update', $item) }}" novalidate>
    @csrf
    @method('PUT')
    @if($errors->any())
        <div class="alert">Please fix the highlighted fields below.</div>
    @endif

    <div class="grid2">
        <x-field name="page_name" label="Page Name" :value="$item->page_name ?: $item->label" required full />

        <x-field name="meta_title" label="Meta Title" :value="$item->meta_title" placeholder="Meta Title" />
        <x-field name="meta_keywords" label="Meta Keyword" :value="$item->meta_keywords" placeholder="Meta Keyword" />

        <div class="field full">
            <label for="f_meta_description">Meta Description</label>
            <div @class(['seo-desc', 'invalid' => $errors->has('meta_description')])>
                <textarea id="f_meta_description" name="meta_description" rows="4" class="input">{{ old('meta_description', $item->meta_description) }}</textarea>
            </div>
            @error('meta_description')<div class="error">{{ $message }}</div>@enderror
        </div>

        <x-textarea name="head" label="Head" :value="$item->head" rows="7" :full="false" class="seo-code" spellcheck="false"
            hint="Scripts / tags injected inside <head> (e.g. Google Analytics)" />
        <x-textarea name="body" label="Body" :value="$item->body" rows="7" :full="false" class="seo-code" spellcheck="false"
            hint="Scripts injected at start of <body> (e.g. GTM noscript)" />
    </div>

    <div class="form-actions">
        <button class="btn" type="submit">Update</button>
        <a class="btn ghost" href="{{ route('admin.seo.index') }}">Cancel</a>
    </div>
</form>

{{-- CKEditor 5 (open-source build) with Undo/Redo, Bold, Italic, Underline, Link and Source --}}
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css">
<script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js"></script>
<script>
(function () {
  if (!window.CKEDITOR) { console.warn('CKEditor could not be loaded from cdn.ckeditor.com'); return; }
  var C = window.CKEDITOR;
  var ta = document.getElementById('f_meta_description');
  C.ClassicEditor.create(ta, {
    licenseKey: 'GPL',
    plugins: [C.Essentials, C.Paragraph, C.Bold, C.Italic, C.Underline, C.Link, C.SourceEditing],
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', 'underline', '|', 'link', 'sourceEditing'],
    link: { addTargetToExternalLinks: true }
  }).then(function (ed) {
    ed.model.document.on('change:data', function () { ta.value = ed.getData(); });
  }).catch(function (err) { console.error('CKEditor', err); });
})();
</script>
@endsection
