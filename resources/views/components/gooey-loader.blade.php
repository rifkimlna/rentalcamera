@props(['size' => 12, 'color' => 'currentColor', 'shift' => null])
{{-- Loader gooey blobs (gaya loaders-gooey-blobs): 3 titik menyatu, bergerak kiri-kanan --}}
<span {{ $attributes->merge(['class' => 'gooey-loader']) }} style="--gooey-dot: {{ $size }}px; --gooey-color: {{ $color }};{{ $shift ? ' --gooey-shift: ' . $shift . 'px;' : '' }}" role="status" aria-label="Memuat"><i></i><i></i><i></i></span>
