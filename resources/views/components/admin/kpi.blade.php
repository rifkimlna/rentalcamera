@props(['label' => '', 'value' => '', 'hint' => null, 'icon' => 'money', 'color' => 'emerald'])

@php
$colors = [
    'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
    'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
    'violet' => 'bg-violet-50 text-violet-600 border-violet-100',
    'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
    'red' => 'bg-red-50 text-[#d70015] border-red-100',
    'slate' => 'bg-slate-50 text-[#6e6e73] border-[#e5e5e7]',
    'dark' => 'bg-[#1d1d1f] text-white border-[#1d1d1f]',
];
$circle = $colors[$color] ?? $colors['slate'];
@endphp

<div class="bg-white border border-[#e5e5e7] rounded-[24px] p-5 flex flex-col gap-4 hover:border-black/[0.06] hover:shadow-[0_8px_24px_rgba(0,0,0,0.04)] transition-all duration-200">
    <div class="w-8 h-8 rounded-full border flex items-center justify-center {{ $circle }}">
        <x-admin.icon :name="$icon" :size="16" />
    </div>
    <div class="min-w-0">
        <div class="text-[10px] font-semibold tracking-[0.14em] uppercase text-[#86868b]">{{ $label }}</div>
        <div class="text-[22px] font-semibold tracking-tight text-[#1d1d1f] leading-none mt-1 truncate">{{ $value }}</div>
        @if($hint)
            <div class="text-xs text-[#86868b] mt-1.5 leading-relaxed">{{ $hint }}</div>
        @endif
        {{ $slot }}
    </div>
</div>
