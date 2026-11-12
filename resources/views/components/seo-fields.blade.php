{{-- Reusable SEO block for category / subcategory / product / blog forms --}}
@props(['item'])
<div class="field full">
    <h3 style="margin:10px 0 0">SEO Settings</h3>
    <div class="hint">Leave blank to auto-generate from the name and description.</div>
</div>
<x-field name="meta_title" label="Meta Title" :value="$item->meta_title" full hint="Recommended: up to 60 characters" />
<x-textarea name="meta_description" label="Meta Description" :value="$item->meta_description" rows="3" hint="Recommended: up to 160 characters" />
<x-field name="meta_keywords" label="Meta Keywords" :value="$item->meta_keywords" full hint="Comma separated, e.g. welding machine, MIG welder" />
