@props(['name', 'label', 'checked' => false, 'on' => 'Active', 'off' => 'Inactive'])
<div class="field">
    <label>{{ $label }}</label>
    <input type="hidden" name="{{ $name }}" value="0">
    <label class="switch" data-on="{{ $on }}" data-off="{{ $off }}">
        <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked))><span></span><em style="font-style:normal">{{ $on }}</em>
    </label>
</div>
