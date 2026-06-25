@extends('layouts.customer')

@section('title', 'Booking Berhasil')
@section('page-title', 'Booking Berhasil')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body text-center p-6">
            @if($booking->payment_status == 'paid')
            <div class="text-success mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Pembayaran Berhasil!</h4>
            <p class="text-sm text-base-content/60 mb-4">Booking studio kamu telah dikonfirmasi.</p>
            @else
            <div class="text-warning mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Booking Dibuat!</h4>
            <p class="text-sm text-base-content/60 mb-4">Booking kamu menunggu pembayaran.</p>
            @endif

            <div class="text-left border border-base-300 rounded p-3 mb-4">
                <table class="table table-xs">
                    <tr><td class="font-medium">Studio</td><td>{{ $booking->studio->nama_studio }}</td></tr>
                    <tr><td class="font-medium">Tipe</td><td>{{ $booking->tipe_booking_label }}</td></tr>
                    @if($booking->paketStudio)
                    <tr><td class="font-medium">Paket</td><td>{{ $booking->paketStudio->nama_paket }}</td></tr>
                    @endif
                    <tr><td class="font-medium">Tanggal</td><td>{{ $booking->tanggal_booking->format('d M Y') }}</td></tr>
                    <tr><td class="font-medium">Jam</td><td>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</td></tr>
                    <tr><td class="font-medium">Durasi</td><td>{{ $booking->durasi_jam }} jam</td></tr>
                    <tr><td class="font-medium">Total</td><td class="font-bold">{{ $booking->total_harga_formatted }}</td></tr>
                    <tr><td class="font-medium">Status</td><td><span class="badge badge-sm {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span></td></tr>
                </table>
            </div>

            @if($booking->payment_status != 'paid')
            <a href="{{ route('customer.studio.payment', $booking->id) }}" class="btn btn-primary btn-sm mb-2">Bayar Sekarang</a>
            @endif

            <div class="flex gap-2 justify-center">
                <a href="{{ route('customer.studio.my-bookings') }}" class="btn btn-sm">Booking Saya</a>
                <a href="{{ route('customer.studio.index') }}" class="btn btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
