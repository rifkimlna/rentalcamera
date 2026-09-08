@extends('layouts.customer')

@section('title', 'Voucher Saya - Stekpro Multimedia & Broadcast')
@section('page-title', 'Voucher')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    {{-- Header minimal --}}
    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Voucher</h1>
            <p class="text-sm text-[#6e6e73] mt-1">Gunakan voucher untuk potongan sewa equipment & studio.</p>
        </div>
        @if($availableVouchers->isNotEmpty())
            <span class="hidden sm:inline-flex items-center text-xs font-medium text-[#86868b] bg-white border border-[#e5e5e7] rounded-full px-3 py-1.5">
                {{ $availableVouchers->count() }} tersedia
            </span>
        @endif
    </div>

    {{-- Voucher Tersedia --}}
    @if($availableVouchers->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-semibold tracking-widest uppercase text-[#86868b]">Tersedia</h2>
                <span class="sm:hidden text-xs text-[#86868b]">{{ $availableVouchers->count() }} voucher</span>
            </div>

            <div class="space-y-3">
                @foreach($availableVouchers as $voucher)
                    @php
                        $isPercent = $voucher->type === 'percentage';
                        $daysLeft = $voucher->days_remaining ?? null;
                        $isAlmostExpired = $daysLeft !== null && $daysLeft <= 3;
                    @endphp
                    <div class="group relative bg-white rounded-2xl border border-[#e5e5e7] overflow-hidden hover:border-[#d2d2d7] hover:shadow-sm transition-all">
                        <div class="p-4 sm:p-6">
                            {{-- Nama --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-semibold text-[#1d1d1f] truncate">{{ $voucher->nama_voucher }}</p>
                                @if($isAlmostExpired)
                                    <span class="inline-flex items-center text-[10px] font-medium tracking-wide uppercase bg-[#fff1f2] text-[#e11d48] border border-[#ffe4e6] rounded-full px-2 py-0.5 shrink-0">Segera berakhir</span>
                                @endif
                                <span class="ml-auto text-[11px] font-medium text-[#86868b] sm:hidden">{{ $voucher->type_label }}</span>
                            </div>

                            {{-- Diskon hero --}}
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-[26px] sm:text-[28px] font-semibold tracking-tight leading-none text-[#1d1d1f]">{{ $voucher->value_formatted }}</span>
                                <span class="hidden sm:inline text-xs font-medium text-[#86868b]">{{ $voucher->type_label }}</span>
                                @if($voucher->max_discount)
                                    <span class="text-xs text-[#6e6e73]">• maks {{ $voucher->max_discount_formatted }}</span>
                                @endif
                            </div>

                            {{-- Meta - super minimal, tidak menumpuk di HP --}}
                            <div class="mt-3 flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs">
                                @if($voucher->min_purchase > 0)
                                    <span class="inline-flex items-center rounded-full bg-[#f5f5f7] px-2.5 py-1.5 text-[#6e6e73] leading-none">Min. {{ $voucher->min_purchase_formatted }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#f5f5f7] px-2.5 py-1.5 text-[#6e6e73] leading-none">
                                    <svg class="w-3 h-3 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="truncate">s/d {{ $voucher->end_date->translatedFormat('d M Y') }}</span>
                                    @if($daysLeft !== null)
                                        <span class="hidden xs:inline text-[#86868b]">• {{ (int) ceil($daysLeft) }} hari lagi</span>
                                    @endif
                                </span>
                                @if($voucher->kuota)
                                    <span class="hidden sm:inline text-xs text-[#86868b] leading-none">{{ $voucher->kuota_terpakai }}/{{ $voucher->kuota }} terpakai</span>
                                @endif
                            </div>

                            {{-- Max discount di HP pindah ke bawah biar tidak sesak --}}
                            @if($voucher->produk_id || $voucher->kategori_id)
                                <div class="mt-3 flex items-center gap-2 text-xs text-[#86868b] leading-snug">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="truncate">
                                        @if($voucher->produk) Berlaku untuk {{ $voucher->produk->nama_produk }}
                                        @elseif($voucher->kategori) Berlaku kategori {{ $voucher->kategori->nama_kategori }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Kode area - di HP full width terpisah, tidak menumpuk di samping --}}
                        <div class="border-t border-dashed border-[#e5e5e7] bg-[#fbfbfb] sm:bg-white px-4 py-3 sm:px-6 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="hidden sm:inline text-[11px] font-medium tracking-widest uppercase text-[#86868b]">Kode</span>
                                <code class="text-sm font-mono font-semibold tracking-widest text-[#1d1d1f] truncate">{{ $voucher->kode_voucher }}</code>
                            </div>
                            <button onclick="copyVoucher('{{ $voucher->kode_voucher }}', this)" class="inline-flex items-center justify-center gap-1.5 shrink-0 h-8 px-4 rounded-full bg-white border border-[#e5e5e7] text-xs font-medium text-[#1d1d1f] hover:bg-[#f5f5f7] hover:border-[#d2d2d7] active:scale-[0.98] transition-all" title="Salin kode">
                                <svg class="w-3.5 h-3.5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span class="leading-none">Salin</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-[#e5e5e7] p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-[#f5f5f7] flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
            <p class="text-sm font-medium text-[#1d1d1f]">Belum ada voucher tersedia</p>
            <p class="text-sm text-[#6e6e73] mt-1">Voucher baru akan muncul di sini saat tersedia untuk akunmu.</p>
        </div>
    @endif

    {{-- Riwayat penggunaan - ultra minimal list --}}
    @if($usedVouchers->isNotEmpty())
        <div>
            <h2 class="text-xs font-semibold tracking-widest uppercase text-[#86868b] mb-3">Riwayat pemakaian</h2>
            <div class="bg-white rounded-2xl border border-[#e5e5e7] divide-y divide-[#f5f5f7]">
                @foreach($usedVouchers as $usage)
                    <div class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-[#fbfbfc] transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-[#1d1d1f] truncate">{{ $usage->voucher->kode_voucher ?? 'Voucher' }}</p>
                            <p class="text-xs text-[#86868b] truncate mt-0.5">{{ $usage->voucher->nama_voucher ?? 'Potongan sewa' }} • {{ $usage->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-semibold text-[#1d1d1f]">−{{ $usage->discount_amount_formatted }}</p>
                            <p class="text-xs text-[#86868b]">terpakai</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($usedVouchers->hasPages())
                <div class="mt-4 flex justify-center">
                    {{ $usedVouchers->links() }}
                </div>
            @endif
        </div>
    @else
        @if($availableVouchers->isNotEmpty())
            <div class="text-center py-2">
                <p class="text-xs text-[#86868b]">Belum ada pemakaian voucher. Gunakan kode di atas saat checkout.</p>
            </div>
        @endif
    @endif
</div>

{{-- Toast minimal --}}
<div id="voucher-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 hidden z-50">
    <div class="flex items-center gap-2 bg-[#1d1d1f] text-white text-sm font-medium rounded-full px-4 py-2.5 shadow-lg">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span id="voucher-toast-text">Kode disalin</span>
    </div>
</div>

@push('scripts')
<script>
function copyVoucher(code, btn) {
    const fallback = () => {
        const ta = document.createElement('textarea');
        ta.value = code;
        ta.setAttribute('readonly','');
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    };
    const showToast = (msg) => {
        const toast = document.getElementById('voucher-toast');
        const text = document.getElementById('voucher-toast-text');
        if (text) text.textContent = msg;
        if (!toast) return;
        toast.classList.remove('hidden');
        clearTimeout(window.__voucherToastTimer);
        window.__voucherToastTimer = setTimeout(() => toast.classList.add('hidden'), 1800);
    };
    const flashBtn = () => {
        if (!btn) return;
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
        btn.classList.add('!border-[#1d1d1f]', '!text-[#1d1d1f]');
        setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('!border-[#1d1d1f]', '!text-[#1d1d1f]'); }, 1200);
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code).then(() => { flashBtn(); showToast(code + ' disalin'); }).catch(() => { fallback(); flashBtn(); showToast(code + ' disalin'); });
    } else {
        fallback(); flashBtn(); showToast(code + ' disalin');
    }
}
</script>
@endpush
@endsection
