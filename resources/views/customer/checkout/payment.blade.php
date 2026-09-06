@extends('layouts.customer')

@section('title', 'Pembayaran - Stekpro Multimedia & Broadcast')

@section('content')
<x-flash-messages />
<div class="min-h-[calc(100vh-5rem)] flex flex-col justify-center p-4 sm:p-6">
    <div class="mx-auto w-full max-w-2xl">
            <!-- Transaction Info -->
            <div class="card-apple-static mb-4">
                <div class="p-5">
                    <h5 class="font-semibold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informasi Pembayaran
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-3">
                                <small class="text-[#6e6e73] block">No. Transaksi</small>
                                <h5 class="font-bold text-[#0071e3]">{{ $transaksi->kode_transaksi }}</h5>
                            </div>
                            <div class="mb-3">
                                <small class="text-[#6e6e73] block">Tanggal Transaksi</small>
                                <h6>{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y H:i') }}</h6>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <small class="text-[#6e6e73] block">Total Pembayaran</small>
                                <h3 class="font-bold text-[#6e6e73]">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</h3>
                            </div>
                            <div class="mb-3">
                                <small class="text-[#6e6e73] block">Status</small>
                                <span class="badge-apple {{ $transaksi->status_pembayaran == 'pending' ? 'badge-warning' : 'badge-success' }}">
                                    {{ ucfirst($transaksi->status_pembayaran) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Expiry -->
                    @if($transaksi->payment_expired_at)
                        <div class="rounded-xl bg-[#ff9500]/10 border border-[#ff9500]/20 p-3 mt-3">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#ff9500] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h6 class="font-semibold mb-1">Batas Waktu Pembayaran</h6>
                                    <p class="mb-0 text-sm">
                                        Selesaikan pembayaran sebelum 
                                        <strong>{{ \Carbon\Carbon::parse($transaksi->payment_expired_at)->translatedFormat('d F Y H:i') }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Midtrans Payment -->
            <div class="card-apple-static mb-4">
                <div class="p-5">
                    <h5 class="font-semibold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Pembayaran Aman dengan Midtrans
                    </h5>

                    <!-- Payment Instructions (if available) -->
                    @if($transaksi->payment_type == 'bank_transfer' && $transaksi->va_number)
                        <div class="rounded-xl bg-[#0071e3]/10 border border-[#0071e3]/20 p-4 mb-4">
                            <h6 class="font-semibold mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline text-[#0071e3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Transfer Bank
                            </h6>
                            <p class="mb-2 text-sm">Silakan transfer ke Virtual Account berikut:</p>
                            <div class="bg-white p-4 rounded-xl mb-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                    <div>
                                        <small class="text-[#6e6e73] block">Bank</small>
                                        <h5 class="font-bold mb-0">{{ strtoupper($transaksi->bank) }}</h5>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="min-w-0 flex-1">
                                            <small class="text-[#6e6e73] block">Nomor Virtual Account</small>
                                            <h5 class="font-bold mb-0 break-all">{{ $transaksi->va_number }}</h5>
                                        </div>
                                        <button type="button" id="copy-va" data-va="{{ $transaksi->va_number }}" class="btn-outline-apple shrink-0 !text-xs" title="Salin Nomor VA">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 8V6a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2h2m4 0h6a2 2 0 002-2V8a2 2 0 00-2-2h-6a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Salin
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 text-sm text-[#6e6e73]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Transaksi akan diproses otomatis setelah pembayaran
                            </p>
                        </div>
                    @endif

                    <!-- Midtrans Snap Embed -->
                    <div id="midtrans-payment" class="text-center">
                        @if($snapToken)
                            <div class="mb-4">
                                <button id="pay-button" class="btn-dark-apple w-full md:w-auto md:px-8"
        data-midtrans-token="{{ $snapToken }}"
        data-success-url="{{ route('customer.checkout.success', $transaksi->id) }}"
        data-check-url="{{ route('customer.checkout.check-status', $transaksi->id) }}"
        data-transactions-url="{{ route('customer.transactions.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Bayar Sekarang
                                </button>
                            </div>
                            <p class="text-[#6e6e73] text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Pembayaran diproses secara aman oleh Midtrans
                            </p>
                        @else
                            <div class="rounded-xl bg-[#0071e3]/10 border border-[#0071e3]/20 p-4 mb-4 text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline text-[#0071e3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <strong>Menunggu Pembayaran</strong><br>
                                <span class="text-sm">Silakan selesaikan pembayaran untuk melanjutkan. Halaman ini akan otomatis mendeteksi pembayaran yang masuk.</span>
                            </div>
                            <div class="mb-4">
                                <a href="{{ route('customer.checkout.payment', $transaksi->id) }}" class="btn-dark-apple w-full md:w-auto md:px-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Muat Ulang Halaman
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card-apple-static">
                <div class="p-5">
                    <h5 class="font-semibold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Detail Pesanan
                    </h5>
                    <!-- Desktop: Order Details table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Produk</th>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Tanggal Sewa</th>
                                    <th class="text-right text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->detailTransaksis as $detail)
                                    <tr class="border-t border-[#f0f0f2]">
                                        <td class="py-3 px-2">
                                            <div class="flex items-center gap-3">
                                                <div class="shrink-0">
                                                    @if($detail->produk->gambar_utama)
                                                        <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" 
                                                             alt="{{ $detail->produk->nama_produk }}" 
                                                             class="rounded-xl w-12 h-12 object-cover">
                                                    @else
                                                        <div class="bg-[#f5f5f7] rounded-xl flex items-center justify-center w-12 h-12">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 font-semibold">{{ $detail->nama_produk }}</h6>
                                                    <small class="text-[#6e6e73]">
                                                        {{ $detail->lama_sewa }} hari x Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}/hari
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->translatedFormat('d M Y') }}<br>
                                            <small class="text-[#6e6e73]">s/d</small><br>
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengembalian)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="text-right py-3 px-2">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right py-2 px-2"><strong>Subtotal:</strong></td>
                                    <td class="text-right py-2 px-2">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($transaksi->diskon > 0)
                                    <tr>
                                        <td colspan="2" class="text-right py-2 px-2"><strong>Diskon:</strong></td>
                                        <td class="text-right py-2 px-2 text-[#6e6e73]">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($transaksi->biaya_asuransi > 0)
                                    <tr>
                                        <td colspan="2" class="text-right py-2 px-2"><strong>Biaya Asuransi:</strong></td>
                                        <td class="text-right py-2 px-2">Rp {{ number_format($transaksi->biaya_asuransi, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="bg-[#f5f5f7]">
                                    <td colspan="2" class="text-right py-2 px-2"><strong>Total:</strong></td>
                                    <td class="text-right py-2 px-2 font-bold text-[#6e6e73]">
                                        Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Mobile: Order Details list -->
                    <div class="md:hidden space-y-3">
                        @foreach($transaksi->detailTransaksis as $detail)
                            <div class="bg-[#f5f5f7] rounded-xl p-3">
                                <div class="flex items-start gap-3">
                                    <div class="shrink-0">
                                        @if($detail->produk->gambar_utama)
                                            <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" 
                                                 alt="{{ $detail->produk->nama_produk }}" 
                                                 class="rounded-xl w-16 h-16 object-cover">
                                        @else
                                            <div class="bg-white rounded-xl flex items-center justify-center w-16 h-16">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="font-semibold text-sm leading-snug">{{ $detail->nama_produk }}</h6>
                                        <p class="text-xs text-[#6e6e73] mt-0.5">{{ $detail->lama_sewa }} hari x Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}/hari</p>
                                        <p class="text-xs text-[#6e6e73] mt-0.5">
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->translatedFormat('d M Y') }}
                                            <span class="mx-1">-</span>
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengembalian)->translatedFormat('d M Y') }}
                                        </p>
                                        <p class="font-bold text-sm mt-1">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="bg-[#f5f5f7] rounded-xl p-3 space-y-1.5">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6e6e73]">Subtotal</span>
                                <span class="font-semibold">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($transaksi->diskon > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-[#6e6e73]">Diskon</span>
                                    <span class="font-semibold text-[#6e6e73]">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($transaksi->biaya_asuransi > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-[#6e6e73]">Biaya Asuransi</span>
                                    <span class="font-semibold">Rp {{ number_format($transaksi->biaya_asuransi, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm font-bold border-t border-[#e5e5e7] pt-1.5 mt-1.5">
                                <span>Total</span>
                                <span class="text-[#6e6e73]">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 flex justify-center">
                <a href="{{ route('customer.transactions.show', $transaksi->id) }}" 
                   class="btn-outline-apple w-full sm:w-auto sm:px-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail Transaksi
                </a>
            </div>
        </div>
    </div>

<!-- Floating Payment Modal -->
<dialog id="midtrans-modal" class="modal">
    <div class="modal-box w-full max-w-md p-0 overflow-hidden rounded-2xl">
        <div class="flex items-center justify-between px-4 py-3 border-b border-[#f0f0f2] bg-white sticky top-0 z-10">
            <h5 class="font-semibold text-sm">Pilih Metode Pembayaran</h5>
            <button type="button" id="close-midtrans-modal" class="text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-[#f5f5f7] rounded-full p-2 transition-all" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="snap-embed" class="w-full min-h-[420px]"></div>
    </div>
</dialog>
@endsection

@push('scripts')
@if($snapToken)
    <!-- Midtrans Snap JS -->
    <script type="text/javascript" 
            src="{{ config('midtrans.snap_js_url') }}" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var payButton = document.getElementById('pay-button');
        var modal = document.getElementById('midtrans-modal');
        var closeBtn = document.getElementById('close-midtrans-modal');
        var embedContainer = document.getElementById('snap-embed');
        var snapEmbedded = false;

        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function() {
                modal.close();
            });
        }

        if (payButton) {
            payButton.addEventListener('click', function() {
                // Loading state: cegah klik ganda selama Snap dibuka
                payButton.disabled = true;
                payButton.innerHTML = '<span class="gooey-loader" style="--gooey-dot:8px;margin-right:8px"><i></i><i></i><i></i></span>Memproses...';

                if (!modal.open) {
                    modal.showModal();
                }

                var token = payButton.dataset.midtransToken;
                var successUrl = payButton.dataset.successUrl;

                if (!snapEmbedded) {
                    snapEmbedded = true;

                    var payOptions = {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            window.location.href = successUrl;
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            window.location.reload();
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            payButton.disabled = false;
                            payButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>Bayar Sekarang';
                            Swal.fire({
                                icon: 'error',
                                title: 'Pembayaran Gagal',
                                text: 'Silakan coba lagi atau gunakan metode pembayaran lain',
                                confirmButtonColor: '#1d1d1f'
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            payButton.disabled = false;
                            payButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>Bayar Sekarang';
                        }
                    };

                    if (typeof snap.embed === 'function') {
                        snap.embed(token, Object.assign({ embedId: 'snap-embed' }, payOptions));
                    } else {
                        snap.pay(token, payOptions);
                    }
                }
            });
        }

        // Copy VA number
        var copyVa = document.getElementById('copy-va');
        if (copyVa) {
            copyVa.addEventListener('click', function() {
                var va = copyVa.dataset.va;
                function fallbackCopy(text) {
                    var ta = document.createElement('textarea');
                    ta.value = text;
                    ta.style.position = 'fixed';
                    ta.style.opacity = '0';
                    document.body.appendChild(ta);
                    ta.select();
                    try { document.execCommand('copy'); } catch (e) {}
                    document.body.removeChild(ta);
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(va).then(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Disalin!',
                            text: 'Nomor Virtual Account telah disalin',
                            timer: 1500,
                            showConfirmButton: false,
                            confirmButtonColor: '#1d1d1f'
                        });
                    }).catch(function() { fallbackCopy(va); });
                } else {
                    fallbackCopy(va);
                }
            });
        }

        // Auto-check payment status every 30 seconds
        function checkPaymentStatus() {
            var checkUrl = payButton.dataset.checkUrl;
            axios.get(checkUrl).then(function (response) {
                var data = response.data;
                if (data.status === 'settlement') {
                    window.location.href = payButton.dataset.successUrl;
                } else if (data.status === 'expire') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pembayaran Kadaluarsa',
                        text: 'Batas waktu pembayaran telah habis. Silakan buat pesanan baru.',
                        confirmButtonColor: '#1d1d1f'
                    }).then(() => {
                        window.location.href = payButton.dataset.transactionsUrl;
                    });
                }
            }).catch(function () {});
        }

        @if($transaksi->status_pembayaran === 'pending')
            setInterval(checkPaymentStatus, 30000);
        @endif
    });
    </script>
@endif
@endpush
