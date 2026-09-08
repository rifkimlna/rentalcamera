@props(['title' => 'Belum ada data', 'subtitle' => null, 'icon' => 'package'])
<div class="py-10 flex flex-col items-center text-center">
    <div class="w-12 h-12 rounded-full bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center text-[#86868b] mb-3">
        <x-admin.icon :name="$icon" :size="20" />
    </div>
    <div class="text-sm font-medium text-[#1d1d1f]">{{ $title }}</div>
    @if($subtitle)
        <div class="text-xs text-[#86868b] mt-1 max-w-sm">{{ $subtitle }}</div>
    @endif
    @if(trim($actions ?? '') !== '')
        <div class="mt-4 flex flex-wrap justify-center gap-2">{{ $actions }}</div>
    @endif
    {{ $slot }}
</div>
