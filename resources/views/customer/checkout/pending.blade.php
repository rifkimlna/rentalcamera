@extends('layouts.customer')

@section('title', 'Pembayaran Pending - Sewa Kamera Pro')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full bg-base-200 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl tracking-tight font-light mb-2">Menunggu Pembayaran</h1>
            <p class="text-sm text-base-content/60 leading-relaxed">Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran Anda.</p>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-base-300">
                    <span class="text-sm text-base-content/60">Kode Transaksi</span>
                    <span class="text-sm font-medium">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-base-300">
                    <span class="text-sm text-base-content/60">Total Pembayaran</span>
                    <span class="text-sm font-bold text-primary">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-base-300">
                    <span class="text-sm text-base-content/60">Status</span>
                    <span class="badge badge-warning">Menunggu Pembayaran</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('customer.checkout.payment', $transaksi->id) }}" class="btn btn-primary">
                Lanjutkan Pembayaran
            </a>
            <a href="{{ route('customer.transactions.show', $transaksi->id) }}" class="btn btn-ghost ml-2">
                Lihat Detail Transaksi
            </a>
        </div>
    </div>
</div>
@endsection
