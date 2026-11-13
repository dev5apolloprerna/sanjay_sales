{{-- One "Label | Value" row. $g / $i = indexes (null inside the <template>) --}}
@php $k = $g !== null && $i !== null ? "groups.$g.items.$i" : null; @endphp
<div class="spec-row">
    <div>
        <input class="input @if($k) @error("$k.label") invalid @enderror @endif" data-f="label" value="{{ $item['label'] ?? '' }}" placeholder="e.g. Machine Type">
        @if($k) @error("$k.label")<div class="error">{{ $message }}</div>@enderror @endif
    </div>
    <div>
        <input class="input @if($k) @error("$k.value") invalid @enderror @endif" data-f="value" value="{{ $item['value'] ?? '' }}" placeholder="e.g. CNC Plasma Cutting Machine">
        @if($k) @error("$k.value")<div class="error">{{ $message }}</div>@enderror @endif
    </div>
    <div class="btns">
        <button type="button" class="icon-btn" data-act="up" title="Move up">↑</button>
        <button type="button" class="icon-btn" data-act="down" title="Move down">↓</button>
        <button type="button" class="icon-btn del" data-act="remove" title="Remove row"><x-icon name="x" /></button>
    </div>
</div>
