{{-- Single image / video upload with live preview.
     removable: adds a hidden "remove_<name>" flag that is set when the user clicks × on the current file. --}}
@props(['name', 'label', 'current' => null, 'kind' => 'image', 'required' => false, 'removable' => true, 'wide' => false, 'hint' => null, 'full' => true])
<div class="field {{ $full ? 'full' : '' }}">
    <label>{{ $label }} @if($required)<span class="req">*</span>@endif</label>
    <div data-file-preview="{{ $kind }}" @if($current) data-current="{{ $current }}" @endif @if($wide) data-wide @endif>
        <div class="drop">
            <input type="file" name="{{ $name }}" accept="{{ $kind === 'video' ? 'video/mp4,video/webm,video/ogg' : 'image/*' }}" @if($required && ! $current) required @endif>
            <x-icon name="upload" width="20" height="20" /><div>Click or drop {{ $kind === 'video' ? 'a video' : 'an image' }} here</div>
        </div>
        @if($removable)<input type="hidden" name="remove_{{ $name }}" value="0" data-remove-flag>@endif
        <div class="previews"></div>
    </div>
    @if($hint)<div class="hint">{{ $hint }}</div>@endif
    @error($name)<div class="error">{{ $message }}</div>@enderror
</div>
