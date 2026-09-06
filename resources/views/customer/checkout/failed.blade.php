@extends('layouts.customer')

@section('title', 'Pembayaran Gagal - Stekpro Multimedia & Broadcast')

@section('content')
<x-flash-messages />
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full bg-[#f5f5f7] flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h1 class="text-2xl tracking-tight font-light mb-2">Pembayaran Gagal</h1>
            <p class="text-sm text-[#6e6e73] leading-relaxed">Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi.</p>
        </div>

        <div class="card-apple-static">
            <div class="p-5 space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-xs text-[#6e6e73]">Kode Transaksi</span>
                    <span class="text-sm font-mono tracking-tight">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-xs text-[#6e6e73]">Status</span>
                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 border border-[#e5e5e7]">{{ $transaksi->status_pembayaran_label }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-xs text-[#6e6e73]">Total Pembayaran</span>
                    <span class="text-sm">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('customer.checkout.payment', $transaksi->id) }}" class="btn-dark-apple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Coba Lagi
            </a>
            <a href="{{ route('contact') }}" class="btn-outline-apple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Hubungi Kami
            </a>
        </div>
    </div>
</div>
@endsection
