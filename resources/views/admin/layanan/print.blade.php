@extends('layouts.admin')

@section('title', 'Cetak Booking Layanan')
@section('page-title', 'Cetak Booking')

@section('content')
<x-flash-messages />
<div class="max-w-2xl mx-auto" id="print-area">
    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-6">
            <div class="text-center mb-6">
                <h3 class="text-base font-bold">Stekpro Multimedia & Broadcast</h3>
                <p class="text-xs text-[#6e6e73]">Booking Layanan</p>
            </div>

            <table class="w-full text-sm mb-4">
                <tr>
                    <td class="font-medium w-32">Nama Penyewa</td>
                    <td>{{ $booking->user->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-medium">Layanan</td>
                    <td>{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-medium">Tipe</td>
                    <td>{{ $booking->tipe_booking_label }}</td>
                </tr>
                @if($booking->paketLayanan)
                <tr>
                    <td class="font-medium">Paket</td>
                    <td>{{ $booking->paketLayanan->nama_paket }}</td>
                </tr>
                @endif
                <tr>
                    <td class="font-medium">Tanggal</td>
                    <td>{{ $booking->tanggal_booking->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="font-medium">Jam</td>
                    <td>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</td>
                </tr>
                <tr>
                    <td class="font-medium">Durasi</td>
                    <td>{{ $booking->durasi_jam }} jam</td>
                </tr>
                <tr>
                    <td class="font-medium">Total Harga</td>
                    <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                </tr>
                @if($booking->diskon_voucher > 0)
                <tr>
                    <td class="font-medium">Diskon Voucher</td>
                    <td class="text-[#34c759]">-Rp {{ number_format($booking->diskon_voucher, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($booking->admin_fee > 0)
                <tr>
                    <td class="font-medium">Biaya Admin</td>
                    <td>Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="font-bold">
                    <td>Grand Total</td>
                    <td>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="font-medium">Status Pembayaran</td>
                    <td>{{ $booking->payment_status_label }}</td>
                </tr>
                <tr>
                    <td class="font-medium">Status</td>
                    <td>{{ $booking->status_label }}</td>
                </tr>
            </table>

            @if($booking->catatan)
            <div class="mb-3">
                <p class="text-xs font-medium">Catatan:</p>
                <p class="text-xs">{{ $booking->catatan }}</p>
            </div>
            @endif

            <div class="text-center text-xs text-[#86868b] mt-6">
                Dicetak pada {{ now()->format('d M Y H:i') }}
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <button onclick="window.print()" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cetak</button>
    <a href="{{ route('admin.layanan.bookings') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Kembali</a>
</div>

@push('scripts')
<script>
    window.onload = function() { window.print(); }
</script>
@endpush
@endsection
