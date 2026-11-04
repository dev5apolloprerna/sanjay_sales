{{-- Text-like input with label + validation error --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null, 'full' => false])
<div class="field {{ $full ? 'full' : '' }}">
    <label for="f_{{ $name }}">{{ $label }} @if($required)<span class="req">*</span>@endif</label>
    <input id="f_{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        {{ $attributes->class(['input', 'invalid' => $errors->has($name)]) }} @if($required) required @endif>
    @if($hint)<div class="hint">{{ $hint }}</div>@endif
    @error($name)<div class="error">{{ $message }}</div>@enderror
</div>
