@extends('layouts.customer')

@section('title', 'Booking Studio Saya')
@section('page-title', 'Booking Studio Saya')

@section('content')
<div class="card bg-base-100 border border-base-300">
    <div class="card-body p-4">
        @forelse($bookings as $booking)
        <div class="border border-base-300 rounded p-3 mb-3">
            <div class="flex justify-between items-start">
                <div>
                    <h5 class="text-sm font-medium">{{ $booking->studio->nama_studio }}</h5>
                    <p class="text-xs text-base-content/60">
                        {{ $booking->tanggal_booking->format('d M Y') }} |
                        {{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }} |
                        {{ $booking->durasi_jam }} jam
                    </p>
                    <p class="text-xs text-base-content/60">
                        {{ $booking->tipe_booking_label }}
                        @if($booking->paketStudio)
                            - {{ $booking->paketStudio->nama_paket }}
                        @endif
                    </p>
                    @if($booking->catatan)
                        <p class="text-xs text-base-content/40 mt-1">Catatan: {{ $booking->catatan }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold">{{ $booking->total_harga_formatted }}</span><br>
                    <span class="badge badge-sm mt-1 {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span><br>
                    <span class="badge badge-sm mt-1
                        {{ $booking->status == 'confirmed' ? 'badge-success' :
                           ($booking->status == 'pending' ? 'badge-warning' :
                           ($booking->status == 'completed' ? 'badge-info' : 'badge-ghost')) }}">
                        {{ $booking->status_label }}
                    </span>
                    @if($booking->payment_status == 'pending' && $booking->status == 'pending')
                        <div class="mt-1 flex gap-1">
                            <a href="{{ route('customer.studio.payment', $booking->id) }}" class="btn btn-primary btn-xs">Bayar</a>
                            <form method="POST" action="{{ route('customer.studio.booking.cancel', $booking->id) }}"
                                  onsubmit="return confirm('Batalkan booking ini?')">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-ghost btn-xs text-error">Batal</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <p class="text-base-content/60">Belum ada booking studio.</p>
            <a href="{{ route('customer.studio.index') }}" class="btn btn-sm mt-2">Lihat Studio</a>
        </div>
        @endforelse
        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
