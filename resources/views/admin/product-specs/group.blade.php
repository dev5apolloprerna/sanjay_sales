{{-- One specification group. $g = index (null inside the <template>) --}}
<div class="spec-group">
    <div class="spec-group-head">
        <input class="input @if($g !== null) @error("groups.$g.group") invalid @enderror @endif" data-f="group" value="{{ $group['group'] ?? '' }}" placeholder="Group name – e.g. General, Cutting Capacity">
        <div class="btns">
            <button type="button" class="icon-btn" data-act="up" title="Move group up">↑</button>
            <button type="button" class="icon-btn" data-act="down" title="Move group down">↓</button>
            <button type="button" class="icon-btn del" data-act="remove" title="Remove group"><x-icon name="trash" /></button>
        </div>
    </div>
    <div class="spec-rows">
        @if($g !== null)
            @error("groups.$g.group")<div class="error">{{ $message }}</div>@enderror
            @error("groups.$g.items")<div class="error">{{ $message }}</div>@enderror
        @endif
        <div class="spec-row-head"><span>Label</span><span>Value</span><span style="width:104px"></span></div>
        <div class="spec-row-list">
            @foreach(($group['items'] ?? []) as $i => $item)
                @include('admin.product-specs.row', ['g' => $g, 'i' => $i, 'item' => $item])
            @endforeach
        </div>
        <button type="button" class="btn ghost sm" data-act="add-row"><x-icon name="plus" width="14" height="14" /> Add Row</button>
    </div>
</div>
