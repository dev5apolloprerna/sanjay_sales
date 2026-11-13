{{-- CKEditor 5 rich text field.  toolbar: "full" (headings, lists, tables, links) or "basic" (bold, italic, link, lists) --}}
@props(['name', 'label' => null, 'value' => null, 'required' => false, 'hint' => null, 'toolbar' => 'full', 'full' => true, 'oldKey' => null])
@php $key = $oldKey ?? str_replace(['[', ']'], ['.', ''], $name); @endphp
<div class="field {{ $full ? 'full' : '' }}">
    @if($label)<label>{{ $label }} @if($required)<span class="req">*</span>@endif</label>@endif
    <div @class(['editor-wrap', 'invalid' => $errors->has($key)])>
        <textarea name="{{ $name }}" data-editor="{{ $toolbar }}" rows="6" class="input" {{ $attributes }}>{{ old($key, $value) }}</textarea>
    </div>
    @if($hint)<div class="hint">{{ $hint }}</div>@endif
    @error($key)<div class="error">{{ $message }}</div>@enderror
</div>
@once
    {{-- CKEditor + FAQ repeater script/styles, printed inline once per page --}}
    @include('components.editor-assets')
@endonce
