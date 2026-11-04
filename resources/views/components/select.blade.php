@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'placeholder' => null])
<div class="field">
    <label for="f_{{ $name }}">{{ $label }} @if($required)<span class="req">*</span>@endif</label>
    <select id="f_{{ $name }}" name="{{ $name }}" {{ $attributes->class(['input', 'invalid' => $errors->has($name)]) }} @if($required) required @endif>
        <option value="">{{ $placeholder ?? '— Select '.$label.' —' }}</option>
        @foreach($options as $id => $text)
            <option value="{{ $id }}" @selected((string) old($name, $value) === (string) $id)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)<div class="error">{{ $message }}</div>@enderror
</div>
