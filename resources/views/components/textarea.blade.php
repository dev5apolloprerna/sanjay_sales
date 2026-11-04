@props(['name', 'label', 'value' => null, 'rows' => 4, 'required' => false, 'hint' => null, 'full' => true])
<div class="field {{ $full ? 'full' : '' }}">
    <label for="f_{{ $name }}">{{ $label }} @if($required)<span class="req">*</span>@endif</label>
    <textarea id="f_{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" {{ $attributes->class(['input', 'invalid' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
    @if($hint)<div class="hint">{{ $hint }}</div>@endif
    @error($name)<div class="error">{{ $message }}</div>@enderror
</div>
