@extends('layouts.customer')

@section('title', 'Transaksi yang Dapat Diulas - Stekpro Multimedia & Broadcast')

@section('content')
<div class="p-4">
    <div class="text-sm mb-4">
        <ul>
            <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('customer.reviews.index') }}">Ulasan Saya</a></li>
            <li>Transaksi yang Dapat Diulas</li>
        </ul>
    </div>

    <div class="card bg-white shadow-md">
        <div class="p-5">
            <h5 class="font-semibold text-lg mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-lineflex="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Beri Ulasan untuk Transaksi Selesai
            </h5>

            @if(count($transactions) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($transactions as $transaction)
                        <div class="card bg-white border border-[#e5e5e7] hover:shadow-lg transition-shadow">
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h6 class="font-semibold mb-1">{{ $transaction->kode_transaksi }}</h6>
                                        <small class="text-[#6e6e73]">
                                            Selesai: {{ \Carbon\Carbon::parse($transaction->completed_at)->translatedFormat('d M Y') }}
                                        </small>
                                    </div>
                                    <span class="badge-apple">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-lineflex="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </span>
                                </div>

                                <!-- Products List -->
                                <div class="mb-3">
                                    <h6 class="text-sm font-semibold mb-2">Produk yang Disewa:</h6>
                                    <div class="space-y-2">
                                        @foreach($transaction->detailTransaksis as $detail)
                                            <div class="flex items-center gap-2">
                                                @if($detail->produk && $detail->produk->gambar_utama)
                                                    <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" 
                                                         alt="{{ $detail->produk->nama_produk }}" 
                                                         class="rounded w-10 h-10 object-cover">
                                                @else
                                                    <div class="bg-[#f5f5f7] rounded flex items-center justify-center w-10 h-10">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-lineflex="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                            <path stroke-linecap="round" stroke-lineflex="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="mb-0 text-sm">{{ $detail->produk ? $detail->produk->nama_produk : $detail->nama_produk }}</p>
                                                    <small class="text-[#6e6e73]">
                                                        {{ $detail->lama_sewa }} hari × Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Check if product already has review -->
                                @php
                                    $unreviewedProducts = $transaction->detailTransaksis->filter(function($detail) use ($transaction) {
                                        return !$transaction->hasReviewForProduct($detail->produk_id);
                                    });
                                @endphp

                                @if($unreviewedProducts->count() > 0)
                                    <div class="rounded-xl bg-[#0071e3]/10 border border-[#0071e3]/20 p-3 flex items-center gap-2 text-sm text-[#0071e3] mb-3 p-3">
                                        <small>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-lineflex="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Anda dapat memberikan ulasan untuk {{ $unreviewedProducts->count() }} produk
                                        </small>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div>
                                        <small class="text-[#6e6e73] block">Total Pembayaran</small>
                                        <h6 class="mb-0 text-[#6e6e73]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</h6>
                                    </div>
                                    <a href="{{ route('customer.reviews.create', $transaction->id) }}" 
                                       class="btn-dark-apple btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-lineflex="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        Beri Ulasan
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-lineflex="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4>Tidak Ada Transaksi yang Dapat Diulas</h4>
                    <p class="text-[#6e6e73] mb-4">
                        Saat ini tidak ada transaksi yang sudah selesai dan belum diulas.
                        Anda hanya dapat memberikan ulasan untuk transaksi yang statusnya "selesai".
                    </p>
                    <a href="{{ route('customer.transactions.index') }}" class="btn-dark-apple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-lineflex="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Lihat Riwayat Transaksi
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

