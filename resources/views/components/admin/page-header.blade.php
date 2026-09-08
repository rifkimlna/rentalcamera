@props(['title' => '', 'subtitle' => null])
<div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-6">
    <div class="min-w-0">
        <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f] leading-none">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-xs text-[#86868b] mt-1.5 leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>
    @if(trim($actions ?? '') !== '')
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            {{ $actions ?? '' }}
        </div>
    @endif
    {{ $slot }}
</div>
