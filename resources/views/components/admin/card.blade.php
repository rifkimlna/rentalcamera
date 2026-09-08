@props(['padding' => 'p-5', 'hover' => false])
<div {{ $attributes->merge(['class' => 'bg-white border border-[#e5e5e7] rounded-[24px] ' . $padding . ($hover ? ' hover:border-black/10 hover:shadow-[0_8px_24px_rgba(0,0,0,0.04)] transition-all duration-200' : '')]) }}>
    {{ $slot }}
</div>
