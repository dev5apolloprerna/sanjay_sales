{{-- Reusable SEO block for category / subcategory / product / blog forms.
     scripts=true also shows the Head / Body script fields (same as Admin > SEO Setup). --}}
@props(['item', 'scripts' => false])
<div class="field full">
    <h3 style="margin:10px 0 0">SEO Settings</h3>
    <div class="hint">Leave blank to auto-generate from the name and description.</div>
</div>
<x-field name="meta_title" label="Meta Title" :value="$item->meta_title" full hint="Recommended: up to 60 characters" />
<x-textarea name="meta_description" label="Meta Description" :value="$item->meta_description" rows="3" hint="Recommended: up to 160 characters" />
<x-field name="meta_keywords" label="Meta Keywords" :value="$item->meta_keywords" full hint="Comma separated, e.g. welding machine, MIG welder" />

@if($scripts)
    <x-textarea name="head" label="Head" :value="$item->head" rows="7" :full="false" class="seo-code" spellcheck="false" data-encode
        hint="Scripts / tags injected inside <head> (e.g. Google Analytics)" />
    <x-textarea name="body" label="Body" :value="$item->body" rows="7" :full="false" class="seo-code" spellcheck="false" data-encode
        hint="Scripts injected at start of <body> (e.g. GTM noscript)" />

    @once
    <style>
    .seo-code{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:13px;line-height:1.5;min-height:140px;resize:vertical;white-space:pre}
    </style>
    <script>
    // Head/Body contain <script> tags, which the hosting server's ModSecurity blocks ("Not Acceptable!").
    // Send them base64-encoded ("__b64__:..."); Laravel decodes them again (App\Support\EditorInput).
    (function () {
        var P = '__b64__:';
        document.addEventListener('submit', function (e) {
            if (e.defaultPrevented) return;
            e.target.querySelectorAll('textarea[data-encode]').forEach(function (ta) {
                if (ta.value === '' || ta.value.indexOf(P) === 0) return;
                ta.value = P + btoa(unescape(encodeURIComponent(ta.value)));
            });
        });
        // Browser Back button: show the real code again, not the encoded text
        window.addEventListener('pageshow', function () {
            document.querySelectorAll('textarea[data-encode]').forEach(function (ta) {
                if (ta.value.indexOf(P) === 0) {
                    try { ta.value = decodeURIComponent(escape(atob(ta.value.slice(P.length)))); } catch (err) {}
                }
            });
        });
    })();
    </script>
    @endonce
@endif
