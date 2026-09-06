@extends('layouts.customer')

@section('title', 'Transaksi Saya')
@section('page-title', 'Transaksi Saya')

@php
    $waPhone = config('app.wa_phone', '6281234567890');
@endphp

@section('content')
<x-flash-messages />
<div class="space-y-5">

    {{-- Filter --}}
    <div class="card-apple-static p-3 sm:p-4" x-data="{ filterOpen: false }">
        {{-- Desktop --}}
        <form method="GET" action="{{ route('customer.transactions.index') }}" class="hidden sm:flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[#86868b]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." class="input-apple !pl-10">
            </div>
            <div class="w-48">
                <label for="status-desktop" class="sr-only">Filter status</label>
                <select id="status-desktop" name="status" class="select-apple" onchange="this.form.submit()">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-dark-apple !px-5 !py-2.5 shrink-0">Cari</button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('customer.transactions.index') }}" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] px-3 py-2 rounded-full hover:bg-[#f5f5f7] transition-colors shrink-0">Reset</a>
            @endif
        </form>

        {{-- Mobile --}}
        <form method="GET" action="{{ route('customer.transactions.index') }}" class="sm:hidden">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <label for="search-mobile" class="sr-only">Cari transaksi</label>
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#86868b]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                    </span>
                    <input type="text" id="search-mobile" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." class="input-apple !pl-9 !text-sm">
                </div>
                <button type="button" @click="filterOpen = !filterOpen" aria-label="Filter status" class="w-10 h-10 shrink-0 rounded-xl bg-[#f5f5f7] hover:bg-[#e5e5e7] flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>
                <button type="submit" class="btn-dark-apple !px-4 !py-2.5 !text-sm shrink-0">Cari</button>
            </div>

            <div x-show="filterOpen" x-collapse x-cloak class="mt-3">
                <label for="status-mobile" class="sr-only">Filter status</label>
                <select id="status-mobile" name="status" class="select-apple w-full" onchange="this.form.submit()">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Daftar Transaksi --}}
    <div class="space-y-3 sm:space-y-4">
        @forelse($paginated as $transaction)
            @php
                $isSewa = $transaction->tipe === 'sewa_kamera';
                $isStudio = $transaction->tipe === 'studio';

                // Warna status (Apple palette)
                $statusStyles = [
                    'pending' => ['bg-[#fffbeb]', 'text-[#b45309]'],
                    'menunggu_pembayaran' => ['bg-[#fffbeb]', 'text-[#b45309]'],
                    'confirmed' => ['bg-[#eff6ff]', 'text-[#0071e3]'],
                    'dikonfirmasi' => ['bg-[#eff6ff]', 'text-[#0071e3]'],
                    'siap_diambil' => ['bg-[#eff6ff]', 'text-[#0071e3]'],
                    'completed' => ['bg-[#f0faf1]', 'text-[#248a3d]'],
                    'selesai' => ['bg-[#f0faf1]', 'text-[#248a3d]'],
                    'cancelled' => ['bg-[#fef2f2]', 'text-[#d70015]'],
                    'dibatalkan' => ['bg-[#fef2f2]', 'text-[#d70015]'],
                ];
                $statusLabels = [
                    'pending' => 'Menunggu',
                    'menunggu_pembayaran' => 'Menunggu Bayar',
                    'confirmed' => 'Dikonfirmasi',
                    'dikonfirmasi' => 'Dikonfirmasi',
                    'siap_diambil' => 'Siap Diambil',
                    'completed' => 'Selesai',
                    'selesai' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    'dibatalkan' => 'Dibatalkan',
                ];

                // Item utama
                if ($isSewa) {
                    $itemName = $transaction->product_names;
                    $itemImage = $transaction->detailTransaksis->first()?->produk?->gambar_utama
                        ? asset('storage/' . $transaction->detailTransaksis->first()->produk->gambar_utama)
                        : null;
                } elseif ($isStudio) {
                    $itemName = $transaction->studio?->nama_studio ?? 'Studio';
                    $itemImage = $transaction->studio?->gambar_utama
                        ? asset('storage/' . $transaction->studio->gambar_utama)
                        : null;
                } else {
                    $itemName = $transaction->layanan?->nama_layanan ?? 'Layanan';
                    $itemImage = $transaction->layanan?->gambar_utama
                        ? asset('storage/' . $transaction->layanan->gambar_utama)
                        : null;
                }

                $statusKey = $transaction->status_global;
                [$badgeBg, $badgeText] = $statusStyles[$statusKey] ?? ['bg-[#f5f5f7]', 'text-[#1d1d1f]'];
                $statusLabel = $statusLabels[$statusKey] ?? $statusKey;

                $createdAt = $transaction->created_at
                    ? \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y')
                    : '-';

                $waText = rawurlencode(
                    "Halo, saya mau tanya tentang {$transaction->tipe_label}:\n" .
                    "{$itemName}\n" .
                    "Kode: {$transaction->kode}\n" .
                    "Status: {$statusLabel}"
                );
            @endphp

            <div class="card-apple overflow-hidden hover:shadow-md hover:-translate-y-px transition-all duration-300">
                <div class="p-3.5 sm:p-5">
                    <div class="flex items-start gap-3 sm:gap-4">
                        {{-- Gambar --}}
                        <a href="{{ $transaction->detail_link }}" class="shrink-0 w-14 h-14 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl overflow-hidden bg-[#f5f5f7]" aria-hidden="true" tabindex="-1">
                            @if($itemImage)
                                <img src="{{ $itemImage }}" class="w-full h-full object-cover" alt="" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                                    <svg class="h-7 w-7 text-[#c7c7cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            @endif
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ $transaction->detail_link }}" class="block">
                                        <h4 class="text-[15px] sm:text-base font-semibold text-[#1d1d1f] truncate hover:text-[#0071e3] transition-colors">{{ $itemName }}</h4>
                                    </a>
                                    <p class="mt-1 flex items-center gap-x-1.5 text-xs text-[#86868b] truncate">
                                        <span class="hidden sm:inline font-medium text-[#6e6e73]">{{ $transaction->tipe_label }}<span class="mx-1" aria-hidden="true">&middot;</span></span>
                                        <span class="font-mono truncate">{{ $transaction->kode }}</span>
                                        <span aria-hidden="true">&middot;</span>
                                        <span class="shrink-0">{{ $createdAt }}</span>
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm sm:text-lg font-bold text-[#1d1d1f] tracking-tight">Rp {{ number_format($transaction->total ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Status + Aksi (sebaris, hemat ruang di mobile) --}}
                            <div class="mt-2 sm:mt-2.5 pt-2 border-t border-[#f5f5f7] flex flex-wrap items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2.5 py-1 rounded-full {{ $badgeBg }} {{ $badgeText }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                    {{ $statusLabel }}
                                </span>
                                @if(in_array($transaction->status_bayar, ['settlement', 'capture', 'paid']) && !in_array($statusKey, ['cancelled', 'dibatalkan']))
                                    <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-medium text-[#248a3d]">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Lunas
                                    </span>
                                @elseif($transaction->status_bayar == 'pending' && !in_array($statusKey, ['cancelled', 'dibatalkan']))
                                    <span class="hidden sm:inline-flex items-center text-[11px] font-medium text-[#b45309]">Belum dibayar</span>
                                @endif

                                <div class="flex items-center gap-1.5 sm:gap-2 ml-auto">
                                    <a href="{{ $transaction->detail_link }}" class="inline-flex items-center justify-center text-xs font-medium px-4 py-2 rounded-lg lg:rounded-xl bg-[#1d1d1f] text-white hover:bg-[#333] transition-colors">
                                        Detail
                                    </a>
                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg lg:rounded-xl border border-[#25D366]/40 text-[#128C4A] hover:bg-[#25D366]/10 transition-colors">
                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        Tanya
                                    </a>
                                    @if($isSewa && $transaction->canBeCancelled())
                                        <form method="POST" action="{{ route('customer.transactions.cancel', $transaction->id) }}" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium px-2.5 py-2 rounded-lg lg:rounded-xl text-[#d70015] hover:bg-[#fef2f2] transition-colors">Batal</button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Progress --}}
                            @if(!in_array($statusKey, ['cancelled', 'completed', 'dibatalkan', 'selesai']))
                                @php
                                    $progressSteps = $isSewa
                                        ? ['menunggu_pembayaran', 'dikonfirmasi', 'siap_diambil', 'selesai']
                                        : ['pending', 'confirmed', 'completed'];
                                    $currentIdx = array_search($statusKey, $progressSteps);
                                    if ($currentIdx === false) { $currentIdx = -1; }
                                    $totalSteps = count($progressSteps) - 1;
                                    $progressPct = $currentIdx >= 0 ? round(($currentIdx / $totalSteps) * 100) : 0;
                                @endphp
                                <div class="mt-2">
                                    <div class="w-full bg-[#f0f0f2] rounded-full h-1" role="progressbar" aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="h-1 rounded-full bg-gradient-to-r from-[#1d1d1f] to-[#48484a] transition-all duration-500" style="width: {{ $progressPct }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-apple-static">
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-[#f5f5f7] flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-[#1d1d1f] mb-1">Belum ada transaksi</p>
                    <p class="text-sm text-[#86868b] mb-6">Mulai sewa kamera, booking studio, atau pesan layanan pertama Anda.</p>
                    <div class="flex gap-2 justify-center flex-wrap">
                        <a href="{{ route('customer.products.index') }}" class="btn-dark-apple !text-sm !px-5 !py-2.5">Sewa Kamera</a>
                        <a href="{{ route('customer.studio.index') }}" class="btn-outline-apple !text-sm !px-5 !py-2.5">Booking Studio</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($paginated->hasPages())
        <div class="flex justify-center pt-2">
            {{ $paginated->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
