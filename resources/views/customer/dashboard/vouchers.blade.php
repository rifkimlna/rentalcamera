@extends('layouts.customer')

@section('title', 'Voucher Saya - Sewa Kamera Pro')

@section('page-title', 'Voucher Saya')

@push('styles')
<style>
.voucher-card { position: relative; }
.voucher-card::before { content: ''; position: absolute; left: -1px; top: 0; bottom: 0; width: 3px; background: hsl(var(--bc)); opacity: 0.2; border-radius: 3px 0 0 3px; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    @if($availableVouchers->isNotEmpty())
    <div>
        <h3 class="text-xs font-medium text-base-content/60 uppercase tracking-wider mb-3">Voucher Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($availableVouchers as $voucher)
            <div class="card bg-base-100 border border-base-300 voucher-card">
                <div class="card-body p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <p class="text-sm font-medium">{{ $voucher->nama_voucher }}</p>
                            <p class="text-lg font-semibold text-base-content mt-0.5">{{ $voucher->kode_voucher }}</p>
                        </div>
                        <span class="badge badge-outline text-[10px]">{{ $voucher->type_label }}</span>
                    </div>
                    <div class="text-xs text-base-content/60 space-y-1 mb-3">
                        <p>Diskon: <span class="text-base-content/80 font-medium">{{ $voucher->value_formatted }}</span></p>
                        @if($voucher->min_purchase > 0)
                        <p>Min. Belanja: <span class="text-base-content/80 font-medium">{{ $voucher->min_purchase_formatted }}</span></p>
                        @endif
                        @if($voucher->max_discount)
                        <p>Maks. Diskon: <span class="text-base-content/80 font-medium">{{ $voucher->max_discount_formatted }}</span></p>
                        @endif
                        <p>Berlaku: <span class="text-base-content/80 font-medium">{{ $voucher->start_date->format('d M Y') }} - {{ $voucher->end_date->format('d M Y') }}</span></p>
                    </div>
                    <button onclick="copyVoucher('{{ $voucher->kode_voucher }}')" class="btn btn-outline btn-sm w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Salin Kode
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-sm text-base-content/60">Belum ada voucher</p>
        </div>
    </div>
    @endif

    @if($usedVouchers->isNotEmpty())
    <div>
        <h3 class="text-xs font-medium text-base-content/60 uppercase tracking-wider mb-3">Riwayat Penggunaan Voucher</h3>
        <div class="space-y-2">
            @foreach($usedVouchers as $usage)
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium">{{ $usage->voucher->kode_voucher ?? 'Voucher' }}</p>
                            <p class="text-xs text-base-content/40">{{ $usage->voucher->nama_voucher ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium">-{{ $usage->discount_amount_formatted }}</p>
                            <p class="text-xs text-base-content/40">{{ $usage->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-4">
            {{ $usedVouchers->links() }}
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function copyVoucher(code) {
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Tersalin!';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}
</script>
@endpush
@endsection
