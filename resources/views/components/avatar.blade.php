@props(['user' => null, 'size' => 32, 'rounded' => 'full'])

@php
$u = $user ?? auth()->user();
$nama = $u->nama ?? $u->name ?? 'U';
$initial = strtoupper(substr($nama, 0, 1));
$sizePx = (int) $size;
$fontPx = max(10, (int) round($sizePx * 0.42));
$isRoundedFull = $rounded === 'full';
$radiusClass = $isRoundedFull ? 'rounded-full' : 'rounded-2xl';
@endphp
@if($u?->foto_profil)
    <img src="{{ asset('storage/' . $u->foto_profil) }}" alt="{{ $nama }}" class="{{ $radiusClass }} object-cover border border-[#e5e5e7] shrink-0" style="width: {{ $sizePx }}px; height: {{ $sizePx }}px;" loading="lazy">
@else
    <div class="{{ $radiusClass }} bg-[#1d1d1f] text-white flex items-center justify-center font-semibold shrink-0 border border-[#1d1d1f]" style="width: {{ $sizePx }}px; height: {{ $sizePx }}px; font-size: {{ $fontPx }}px;" aria-hidden="true">{{ $initial }}</div>
@endif
