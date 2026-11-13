@props(['edit', 'destroy', 'label'])
<div class="actions">
    {{ $slot }}
    <a class="icon-btn" title="Edit" href="{{ $edit }}"><x-icon name="edit" /></a>
    <form method="POST" action="{{ $destroy }}" data-confirm="Delete “{{ $label }}”? This cannot be undone.">
        @csrf @method('DELETE')
        <button class="icon-btn del" title="Delete"><x-icon name="trash" /></button>
    </form>
</div>
