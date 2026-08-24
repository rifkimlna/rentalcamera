@extends('layouts.customer')

@section('title', 'Booking Berhasil')
@section('page-title', 'Booking Berhasil')

@section('content')
<div class="px-4">
    <div class="card bg-white border border-[#e5e5e7]">
        <div class="p-5 text-center p-6">
            @if($booking->payment_status == 'paid')
            <div class="text-[#6e6e73] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-lineflex="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Pembayaran Berhasil!</h4>
            <p class="text-sm text-[#6e6e73] mb-4">Booking layanan kamu telah dikonfirmasi.</p>
            @else
            <div class="text-[#6e6e73] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-lineflex="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Booking Dibuat!</h4>
            <p class="text-sm text-[#6e6e73] mb-4">Booking kamu menunggu pembayaran.</p>
            @endif

            <div class="text-left border border-[#e5e5e7] rounded p-3 mb-4">
                <table class="w-full text-sm">
                    <tr><td class="font-medium">Layanan</td><td>{{ $booking->layanan->nama_layanan }}</td></tr>
                    <tr><td class="font-medium">Tipe</td><td>{{ $booking->tipe_booking_label }}</td></tr>
                    @if($booking->paketLayanan)
                    <tr><td class="font-medium">Paket</td><td>{{ $booking->paketLayanan->nama_paket }}</td></tr>
                    @endif
                    <tr><td class="font-medium">Tanggal</td><td>{{ $booking->tanggal_booking->format('d M Y') }}</td></tr>
                    <tr><td class="font-medium">Jam</td><td>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</td></tr>
                    <tr><td class="font-medium">Durasi</td><td>{{ $booking->durasi_jam }} jam</td></tr>
                    @if($booking->diskon_voucher > 0)
                    <tr><td class="font-medium">Diskon Voucher</td><td class="text-[#6e6e73]">-{{ $booking->diskon_voucher_formatted }}</td></tr>
                    @endif
                    <tr><td class="font-medium">Grand Total</td><td class="font-bold">{{ $booking->grand_total_formatted }}</td></tr>
                    <tr><td class="font-medium">Status</td><td><span class="!text-[10px] !px-2 !py-0.5 {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span></td></tr>
                </table>
            </div>

            @if($booking->payment_status != 'paid')
            <a href="{{ route('customer.layanan.payment', $booking->id) }}" class="btn-dark-apple btn-sm mb-2">Bayar Sekarang</a>
            @endif

            <div class="flex gap-2 justify-center">
                <a href="{{ route('customer.layanan.my-bookings') }}" class="">Booking Saya</a>
                <a href="{{ route('customer.layanan.index') }}" class="">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection

