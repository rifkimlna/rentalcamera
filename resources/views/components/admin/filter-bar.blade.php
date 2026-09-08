@props(['method' => 'GET'])
<div class="bg-white border border-[#e5e5e7] rounded-[24px] p-3 sm:p-4">
    <form method="{{ $method }}" {{ $attributes }} class="flex flex-wrap items-end gap-3">
        {{ $slot }}
    </form>
</div>
