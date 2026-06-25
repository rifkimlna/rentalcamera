@extends('layouts.customer')

@section('title', 'Pembayaran - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <!-- Transaction Info -->
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informasi Pembayaran
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-3">
                                <small class="text-base-content/60 block">No. Transaksi</small>
                                <h5 class="font-bold text-primary">{{ $transaksi->kode_transaksi }}</h5>
                            </div>
                            <div class="mb-3">
                                <small class="text-base-content/60 block">Tanggal Transaksi</small>
                                <h6>{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y H:i') }}</h6>
                            </div>
                        </div>
                        <div>
                            <div class="mb-3">
                                <small class="text-base-content/60 block">Total Pembayaran</small>
                                <h3 class="font-bold text-success">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</h3>
                            </div>
                            <div class="mb-3">
                                <small class="text-base-content/60 block">Status</small>
                                <span class="badge badge-{{ $transaksi->status_pembayaran == 'pending' ? 'warning' : 'success' }}">
                                    {{ ucfirst($transaksi->status_pembayaran) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Expiry -->
                    @if($transaksi->payment_expired_at)
                        <div class="alert alert-warning mt-3">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h6 class="font-semibold mb-1">Batas Waktu Pembayaran</h6>
                                    <p class="mb-0">
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
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Pembayaran Aman dengan Midtrans
                    </h5>

                    <!-- Payment Instructions (if available) -->
                    @if($transaksi->payment_type == 'bank_transfer' && $transaksi->va_number)
                        <div class="alert alert-info mb-4">
                            <h6 class="font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Transfer Bank
                            </h6>
                            <p class="mb-2">Silakan transfer ke Virtual Account berikut:</p>
                            <div class="bg-base-200 p-4 rounded mb-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                    <div>
                                        <small class="text-base-content/60 block">Bank</small>
                                        <h5 class="font-bold mb-0">{{ strtoupper($transaksi->bank) }}</h5>
                                    </div>
                                    <div>
                                        <small class="text-base-content/60 block">Nomor Virtual Account</small>
                                        <h5 class="font-bold mb-0">{{ $transaksi->va_number }}</h5>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 text-sm text-base-content/60">
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
                                <button id="pay-button" class="btn btn-primary btn-lg"
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
                            <p class="text-base-content/60 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Pembayaran diproses secara aman oleh Midtrans
                            </p>
                        @else
                            <div class="alert alert-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <strong>Menunggu Pembayaran</strong><br>
                                <span class="text-sm">Silakan selesaikan pembayaran untuk melanjutkan. Halaman ini akan otomatis mendeteksi pembayaran yang masuk.</span>
                            </div>
                            <div class="mb-4">
                                <a href="{{ route('customer.checkout.payment', $transaksi->id) }}" class="btn btn-primary btn-lg">
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
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Detail Pesanan
                    </h5>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Tanggal Sewa</th>
                                    <th class="text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->detailTransaksis as $detail)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="shrink-0">
                                                    @if($detail->produk->gambar_utama)
                                                        <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" 
                                                             alt="{{ $detail->produk->nama_produk }}" 
                                                             class="rounded w-12 h-12 object-cover">
                                                    @else
                                                        <div class="bg-base-200 rounded flex items-center justify-center w-12 h-12">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 font-semibold">{{ $detail->nama_produk }}</h6>
                                                    <small class="text-base-content/60">
                                                        {{ $detail->lama_sewa }} hari × Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}/hari
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->translatedFormat('d M Y') }}<br>
                                            <small class="text-base-content/60">s/d</small><br>
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_pengembalian)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="text-right">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><strong>Subtotal:</strong></td>
                                    <td class="text-right">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($transaksi->diskon > 0)
                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Diskon:</strong></td>
                                        <td class="text-right text-error">-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($transaksi->biaya_pengiriman > 0)
                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Biaya Pengiriman:</strong></td>
                                        <td class="text-right">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if($transaksi->biaya_asuransi > 0)
                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Biaya Asuransi:</strong></td>
                                        <td class="text-right">Rp {{ number_format($transaksi->biaya_asuransi, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="bg-base-200">
                                    <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right font-bold text-success">
                                        Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4 flex gap-2 justify-center">
                <a href="{{ route('customer.transactions.show', $transaksi->id) }}" 
                   class="btn btn-outline btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail Transaksi
                </a>
                <a href="{{ route('customer.transactions.index') }}" 
                   class="btn btn-outline btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Kembali ke Daftar Transaksi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($snapToken)
    <!-- Midtrans Snap JS -->
    <script type="text/javascript" 
            src="{{ config('midtrans.snap_js_url', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var payButton = document.getElementById('pay-button');

        if (payButton) {
            payButton.addEventListener('click', function() {
                $(this).prop('disabled', true).html(
                    '<span class="loading loading-spinner loading-sm me-2"></span>Memproses...'
                );

                var token = payButton.dataset.midtransToken;
                var successUrl = payButton.dataset.successUrl;

                snap.pay(token, {
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Silakan coba lagi atau gunakan metode pembayaran lain',
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        payButton.disabled = false;
                        payButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>Bayar Sekarang';
                    }
                });
            });
        }

        // Auto-check payment status every 30 seconds
        function checkPaymentStatus() {
            var checkUrl = payButton.dataset.checkUrl;
            $.ajax({
                url: checkUrl,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'settlement') {
                        window.location.href = payButton.dataset.successUrl;
                    } else if (response.status === 'expire') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pembayaran Kadaluarsa',
                            text: 'Batas waktu pembayaran telah habis. Silakan buat pesanan baru.',
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            window.location.href = payButton.dataset.transactionsUrl;
                        });
                    }
                }
            });
        }

        @if($transaksi->status_pembayaran === 'pending')
            setInterval(checkPaymentStatus, 30000);
        @endif
    });
    </script>
@endif
@endpush
