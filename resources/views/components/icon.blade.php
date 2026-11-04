@props(['name'])
@php
$paths = [
  'dash' => ['M3 13h8V3H3z','M13 21h8V11h-8z','M3 21h8v-6H3z','M13 3v6h8V3z'],
  'cat' => ['M3 7l9-4 9 4-9 4-9-4z','M3 12l9 4 9-4','M3 17l9 4 9-4'],
  'sub' => ['M4 4h7v7H4z','M14 7h6','M14 14h6','M14 18h6','M7 11v7h4'],
  'clients' => ['M17 20v-2a4 4 0 00-3-3.87','M7 20v-2a4 4 0 013-3.87','M12 11a3 3 0 100-6 3 3 0 000 6z','M19 8a2.5 2.5 0 110-5','M5 8a2.5 2.5 0 100-5'],
  'box' => ['M21 8l-9-5-9 5v8l9 5 9-5z','M3 8l9 5 9-5','M12 13v8'],
  'img' => ['M3 5h18v14H3z','M3 16l5-5 4 4 3-3 6 6','M15.5 9.5h.01'],
  'video' => ['M3 6h13v12H3z','M16 10l5-3v10l-5-3'],
  'blog' => ['M5 3h10l4 4v14H5z','M14 3v5h5','M8 13h8','M8 17h6'],
  'user' => ['M20 21a8 8 0 10-16 0','M12 11a4 4 0 100-8 4 4 0 000 8z'],
  'lock' => ['M5 11h14v10H5z','M8 11V7a4 4 0 018 0v4'],
  'out' => ['M15 4h4v16h-4','M10 17l-5-5 5-5','M5 12h11'],
  'plus' => ['M12 5v14','M5 12h14'],
  'edit' => ['M4 20h4L19 9l-4-4L4 16z','M13.5 6.5l4 4'],
  'trash' => ['M4 7h16','M10 11v6','M14 11v6','M6 7l1 13h10l1-13','M9 7V4h6v3'],
  'x' => ['M6 6l12 12','M18 6L6 18'],
  'menu' => ['M4 6h16','M4 12h16','M4 18h16'],
  'upload' => ['M12 16V4','M7 9l5-5 5 5','M4 20h16'],
  'back' => ['M19 12H5','M12 19l-7-7 7-7'],
][$name] ?? [];
@endphp
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes }}>@foreach($paths as $d)<path d="{{ $d }}"/>@endforeach</svg>
