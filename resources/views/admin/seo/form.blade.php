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

<form id="seoForm"  class="card form-card" method="POST" action="{{ route('admin.seo.update', $item) }}" novalidate>
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

<script>
(function () {
    var form = document.getElementById('seoForm');
    if (!form) { console.error('hex: #seoForm not found'); return; }

    var fields = [
        'page_name',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'head',
        'body'
    ];

    function toHex(str) {
        return 'hx' + Array.from(new TextEncoder().encode(str))
            .map(function (b) { return b.toString(16).padStart(2, '0'); }).join('');
    }

    // Read from a rich-text editor if the field has one
    function getValue(el) {
        // CKEditor 5
        var wrap = el.nextElementSibling;
        var editable = wrap && wrap.querySelector ? wrap.querySelector('.ck-editor__editable') : null;
        if (editable && editable.ckeditorInstance) return editable.ckeditorInstance.getData();
        // CKEditor 4
        if (window.CKEDITOR) {
            for (var key in CKEDITOR.instances) {
                var inst = CKEDITOR.instances[key];
                if (inst.element && inst.element.$ === el) return inst.getData();
            }
        }
        return el.value || '';
    }

    form.addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;

        try {
            fields.forEach(function (name) {
                var el = form.querySelector('[data-hex-field="' + name + '"]')
                      || form.querySelector('[name="' + name + '"]');
                if (!el) return;

                var oldHidden = form.querySelector('input[data-hex-hidden="' + name + '"]');
                if (oldHidden) oldHidden.remove();

                var value = String(getValue(el));

                el.dataset.hexField = name;
                el.removeAttribute('name');

                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                hidden.dataset.hexHidden = name;
                hidden.value = value === '' ? '' : toHex(value);
                form.appendChild(hidden);
            });
        } catch (err) {
            // never send raw HTML if encoding fails
            e.preventDefault();
            console.error('hex encode failed:', err);
            alert('Could not prepare the form. Please check the browser console.');
        }
    });
})();
</script>

{{-- CKEditor 5 classic build (same version as the product editors).
     Do NOT switch to ckeditor5 v44+ from cdn.ckeditor.com with licenseKey "GPL": that combination is
     not allowed and the editor silently becomes READ-ONLY (error: license-key-invalid-distribution-channel). --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
(function () {
  if (!window.ClassicEditor) { console.warn('CKEditor could not be loaded from cdn.ckeditor.com'); return; }
  var ta = document.getElementById('f_meta_description');
  ClassicEditor.create(ta, {
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', '|', 'link'],
    link: { addTargetToExternalLinks: true }
  }).then(function (ed) {
    ed.model.document.on('change:data', function () { ta.value = ed.getData(); });
  }).catch(function (err) { console.error('CKEditor', err); });
})();
</script>
@endsection
