@extends('layouts.customer')

@section('title', 'Pembayaran Studio')
@section('page-title', 'Pembayaran Studio')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card bg-base-100 border border-base-300 mb-4">
        <div class="card-body p-4">
            <h4 class="text-sm font-medium mb-3">Informasi Booking</h4>
            <table class="table table-xs">
                <tr><td class="font-medium">Studio</td><td>{{ $booking->studio->nama_studio }}</td></tr>
                <tr><td class="font-medium">Tipe</td><td>{{ $booking->tipe_booking_label }}</td></tr>
                @if($booking->paketStudio)
                <tr><td class="font-medium">Paket</td><td>{{ $booking->paketStudio->nama_paket }}</td></tr>
                @endif
                <tr><td class="font-medium">Tanggal</td><td>{{ $booking->tanggal_booking->format('d M Y') }}</td></tr>
                <tr><td class="font-medium">Jam</td><td>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</td></tr>
                <tr><td class="font-medium">Durasi</td><td>{{ $booking->durasi_jam }} jam</td></tr>
                <tr><td class="font-medium">Pembayaran</td><td>{{ $booking->paymentMethod->name ?? '-' }}</td></tr>
                <tr><td class="font-medium">Status</td><td><span class="badge badge-sm badge-warning">{{ $booking->payment_status_label }}</span></td></tr>
            </table>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-300 mb-4">
        <div class="card-body p-4">
            <h4 class="text-sm font-medium mb-3">Rincian Pembayaran</h4>
            <table class="table table-xs">
                <tr><td>Biaya Studio</td><td class="text-right">{{ number_format($booking->total_harga, 0, ',', '.') }}</td></tr>
                @if($booking->admin_fee > 0)
                <tr><td>Biaya Admin ({{ $booking->paymentMethod->name ?? '' }})</td><td class="text-right">{{ number_format($booking->admin_fee, 0, ',', '.') }}</td></tr>
                @endif
                <tr class="font-bold">
                    <td>Grand Total</td>
                    <td class="text-right text-success">{{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                </tr>
            </table>

            @if($snapToken)
                <button id="pay-button" class="btn btn-primary w-full mt-4">
                    Bayar Sekarang
                </button>
                <p class="text-xs text-base-content/40 text-center mt-2">Pembayaran aman via Midtrans</p>
            @else
                <div role="alert" class="alert alert-error mt-4">
                    <span>Gagal memproses pembayaran. Silakan coba lagi.</span>
                </div>
                <a href="{{ route('customer.studio.payment', $booking->id) }}" class="btn btn-sm mt-2">Coba Lagi</a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.snap_js_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    @if($snapToken)
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route("customer.studio.booking.success", $booking->id) }}';
            },
            onPending: function(result) {
                document.getElementById('pay-button').textContent = 'Menunggu Pembayaran...';
                document.getElementById('pay-button').disabled = true;
                startPolling();
            },
            onError: function(result) {
                alert('Pembayaran gagal!');
                window.location.reload();
            },
            onClose: function() {
                startPolling();
            }
        });
    };

    function startPolling() {
        setTimeout(function poll() {
            fetch('{{ route("customer.studio.check-status", $booking->id) }}')
                .then(r => r.json())
                .then(data => {
                    if (data.payment_status === 'paid') {
                        window.location.href = '{{ route("customer.studio.booking.success", $booking->id) }}';
                    } else if (data.payment_status === 'expired' || data.payment_status === 'failed') {
                        window.location.reload();
                    } else {
                        setTimeout(poll, 3000);
                    }
                });
        }, 3000);
    }
    @endif
</script>
@endpush
