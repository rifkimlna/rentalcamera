@extends('layouts.customer')

@section('title', 'Pembayaran Studio')

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex flex-col justify-center p-4 sm:p-6">
    <div class="mx-auto w-full max-w-2xl">
        <!-- Transaction Info -->
        <div class="card bg-white shadow-md mb-4">
            <div class="p-5">
                <h5 class="font-semibold text-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-lineflex="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Informasi Pembayaran
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-3">
                            <small class="text-[#6e6e73] block">Studio</small>
                            <h5 class="font-bold text-[#0071e3]">{{ $booking->studio->nama_studio }}</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-[#6e6e73] block">Tanggal & Jam</small>
                            <h6>{{ $booking->tanggal_booking->format('d F Y') }}</h6>
                            <h6 class="text-[#6e6e73]">{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }} ({{ $booking->durasi_jam }} jam)</h6>
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <small class="text-[#6e6e73] block">Total Pembayaran</small>
                            <h3 class="font-bold text-[#6e6e73]">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</h3>
                        </div>
                        <div class="mb-3">
                            <small class="text-[#6e6e73] block">Status</small>
                            <span class="badge {{ $booking->payment_status_badge }}">
                                {{ $booking->payment_status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Reminder -->
                <div class="rounded-xl bg-[#ff9500]/10 border border-[#ff9500]/20 p-3 flex items-center gap-2 text-sm text-[#ff9500] mt-3">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-lineflex="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h6 class="font-semibold mb-1">Selesaikan Pembayaran</h6>
                            <p class="mb-0 text-sm">
                                Silakan selesaikan pembayaran sebelum batas waktu untuk mengunci jadwal studio Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Midtrans Payment -->
        <div class="card bg-white shadow-md mb-4">
            <div class="p-5">
                <h5 class="font-semibold text-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-lineflex="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Pembayaran Aman dengan Midtrans
                </h5>

                <div id="midtrans-payment" class="text-center">
                    @if($snapToken)
                        <div class="mb-4">
                            <button id="pay-button" class="btn-dark-apple btn-lg w-full md:w-auto md:px-8"
        data-midtrans-token="{{ $snapToken }}"
        data-success-url="{{ route('customer.studio.booking.success', $booking->id) }}"
        data-check-url="{{ route('customer.studio.check-status', $booking->id) }}"
        data-fallback-url="{{ route('customer.studio.my-bookings') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-lineflex="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Bayar Sekarang
                            </button>
                        </div>
                        <p class="text-[#6e6e73] text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-lineflex="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Pembayaran diproses secara aman oleh Midtrans
                        </p>
                    @else
                        <div class="rounded-xl bg-[#0071e3]/10 border border-[#0071e3]/20 p-3 flex items-center gap-2 text-sm text-[#0071e3] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-lineflex="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <strong>Menunggu Pembayaran</strong><br>
                            <span class="text-sm">Silakan selesaikan pembayaran untuk melanjutkan. Halaman ini akan otomatis mendeteksi pembayaran yang masuk.</span>
                        </div>
                        <div class="mb-4">
                            <a href="{{ route('customer.studio.payment', $booking->id) }}" class="btn-dark-apple btn-lg w-full md:w-auto md:px-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-lineflex="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Muat Ulang Halaman
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="card bg-white shadow-md">
            <div class="p-5">
                <h5 class="font-semibold text-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-lineflex="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Detail Pesanan
                </h5>

                <!-- Desktop: Order Details table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Detail Booking</th>
                                <th>Tanggal & Jam</th>
                                <th class="text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h6 class="mb-1 font-semibold">{{ $booking->studio->nama_studio }}</h6>
                                    <small class="text-[#6e6e73]">
                                        {{ $booking->tipe_booking_label }}
                                        @if($booking->paketStudio)
                                            - {{ $booking->paketStudio->nama_paket }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    {{ $booking->tanggal_booking->format('d M Y') }}<br>
                                    <small class="text-[#6e6e73]">{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }} ({{ $booking->durasi_jam }} jam)</small>
                                </td>
                                <td class="text-right">
                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Subtotal:</strong></td>
                                <td class="text-right">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            @if($booking->diskon_voucher > 0)
                                <tr>
                                    <td colspan="2" class="text-right"><strong>Diskon Voucher:</strong></td>
                                    <td class="text-right text-[#6e6e73]">-Rp {{ number_format($booking->diskon_voucher, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if($booking->admin_fee > 0)
                                <tr>
                                    <td colspan="2" class="text-right"><strong>Biaya Admin:</strong></td>
                                    <td class="text-right">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="bg-[#f5f5f7]">
                                <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                <td class="text-right font-bold text-[#6e6e73]">
                                    Rp {{ number_format($booking->grand_total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Mobile: Order Details list -->
                <div class="md:hidden space-y-3">
                    <div class="bg-[#f5f5f7] rounded-lg p-3">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                @if($booking->studio->gambar_utama)
                                    <img src="{{ asset('storage/' . $booking->studio->gambar_utama) }}"
                                         alt="{{ $booking->studio->nama_studio }}"
                                         class="rounded w-16 h-16 object-cover">
                                @else
                                    <div class="bg-white rounded flex items-center justify-center w-16 h-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-lineflex="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h6 class="font-semibold text-sm leading-snug">{{ $booking->studio->nama_studio }}</h6>
                                <p class="text-xs text-[#6e6e73] mt-0.5">
                                    {{ $booking->tipe_booking_label }}
                                    @if($booking->paketStudio)
                                        - {{ $booking->paketStudio->nama_paket }}
                                    @endif
                                </p>
                                <p class="text-xs text-[#6e6e73] mt-0.5">
                                    {{ $booking->tanggal_booking->format('d M Y') }} - {{ $booking->jam_mulai->format('H:i') }} s/d {{ $booking->jam_selesai->format('H:i') }}
                                </p>
                                <p class="font-bold text-sm mt-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#f5f5f7] rounded-lg p-3 space-y-1.5">
                        <div class="flex justify-between text-sm">
                            <span class="text-[#6e6e73]">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>
                        @if($booking->diskon_voucher > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6e6e73]">Diskon Voucher</span>
                                <span class="font-semibold text-[#6e6e73]">-Rp {{ number_format($booking->diskon_voucher, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($booking->admin_fee > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6e6e73]">Biaya Admin</span>
                                <span class="font-semibold">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm font-bold border-t border-[#e5e5e7] pt-1.5 mt-1.5">
                            <span>Total</span>
                            <span class="text-[#6e6e73]">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 flex justify-center">
            <a href="{{ route('customer.studio.my-bookings') }}"
               class="btn-outline-apple w-full sm:w-auto sm:px-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-lineflex="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-lineflex="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Detail Booking
            </a>
        </div>
    </div>
</div>

<!-- Floating Payment Modal -->
<dialog id="midtrans-modal" class="modal">
    <div class="modal-box rounded-2xl w-full max-w-md p-0 overflow-hidden rounded-xl">
        <div class="flex items-center justify-between px-4 py-3 border-b border-[#f0f0f2] bg-white sticky top-0 z-10">
            <h5 class="font-semibold text-sm">Pilih Metode Pembayaran</h5>
            <button type="button" id="close-midtrans-modal" class="rounded-full btn-ghost btn-sm" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-lineflex="round" d="M6 18L18 6M6 6l12 12" />
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
                            payButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-lineflex="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>Bayar Sekarang';
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

        // Auto-check payment status every 30 seconds
        function checkPaymentStatus() {
            var checkUrl = payButton.dataset.checkUrl;
            var fallbackUrl = payButton.dataset.fallbackUrl;
            axios.get(checkUrl).then(function (response) {
                var data = response.data;
                if (data.payment_status === 'paid') {
                    window.location.href = payButton.dataset.successUrl;
                } else if (data.payment_status === 'expired' || data.payment_status === 'failed') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pembayaran Gagal',
                        text: 'Batas waktu pembayaran telah habis atau pembayaran gagal.',
                        confirmButtonColor: '#1d1d1f'
                    }).then(() => {
                        window.location.href = fallbackUrl;
                    });
                }
            }).catch(function () {});
        }

        @if($booking->payment_status === 'pending')
            setInterval(checkPaymentStatus, 30000);
        @endif
    });
    </script>
@endif
@endpush
