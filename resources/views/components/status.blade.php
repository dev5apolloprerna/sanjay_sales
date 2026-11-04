@props(['on', 'yes' => 'Active', 'no' => 'Inactive'])
<span class="badge {{ $on ? 'on' : 'off' }}">{{ $on ? $yes : $no }}</span>
