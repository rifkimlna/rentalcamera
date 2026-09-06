@extends('layouts.customer')

@section('title', 'Booking Berhasil')
@section('page-title', 'Booking Berhasil')

@section('content')
<x-flash-messages />
<div class="px-4">
    <div class="card-apple-static">
        <div class="p-6 text-center">
            @if($booking->payment_status == 'paid')
            <div class="text-[#6e6e73] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Pembayaran Berhasil!</h4>
            <p class="text-sm text-[#6e6e73] mb-4">Booking studio kamu telah dikonfirmasi.</p>
            @else
            <div class="text-[#6e6e73] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Booking Dibuat!</h4>
            <p class="text-sm text-[#6e6e73] mb-4">Booking kamu menunggu pembayaran.</p>
            @endif

            <div class="text-left border border-[#e5e5e7] rounded-xl p-3 mb-4">
                <table class="w-full text-sm">
                    <tr><td class="font-medium py-1">Studio</td><td class="py-1">{{ $booking->studio->nama_studio }}</td></tr>
                    <tr><td class="font-medium py-1">Tipe</td><td class="py-1">{{ $booking->tipe_booking_label }}</td></tr>
                    @if($booking->paketStudio)
                    <tr><td class="font-medium py-1">Paket</td><td class="py-1">{{ $booking->paketStudio->nama_paket }}</td></tr>
                    @endif
                    <tr><td class="font-medium py-1">Tanggal</td><td class="py-1">{{ $booking->tanggal_booking->format('d M Y') }}</td></tr>
                    <tr><td class="font-medium py-1">Jam</td><td class="py-1">{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</td></tr>
                    <tr><td class="font-medium py-1">Durasi</td><td class="py-1">{{ $booking->durasi_jam }} jam</td></tr>
                    @if($booking->diskon_voucher > 0)
                    <tr><td class="font-medium py-1">Diskon Voucher</td><td class="text-[#6e6e73] py-1">-{{ $booking->diskon_voucher_formatted }}</td></tr>
                    @endif
                    <tr><td class="font-medium py-1">Grand Total</td><td class="font-bold py-1">{{ $booking->grand_total_formatted }}</td></tr>
                    <tr><td class="font-medium py-1">Status</td><td class="py-1"><span class="badge-apple {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span></td></tr>
                </table>
            </div>

            @if($booking->payment_status != 'paid')
            <a href="{{ route('customer.studio.payment', $booking->id) }}" class="btn-dark-apple mb-2">Bayar Sekarang</a>
            @endif

            <div class="flex gap-2 justify-center">
                <a href="{{ route('customer.studio.my-bookings') }}" class="btn-outline-apple">Booking Saya</a>
                <a href="{{ route('customer.studio.index') }}" class="btn-outline-apple">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
