@extends('layouts.customer')

@section('title', 'Booking Studio Saya')
@section('page-title', 'Booking Studio Saya')

@php
    $waPhone = config('app.wa_phone', '6281234567890');
@endphp

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <p class="text-sm text-[#6e6e73]">Semua booking studio Anda</p>
        <a href="{{ route('customer.studio.index') }}" class="btn-dark-apple btn-sm">
            <svg class="h-4 w-4 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-lineflex="round" d="M12 4v16m8-8H4" />
            </svg>
            Booking Baru
        </a>
    </div>

    <div class="space-y-3">
        @forelse($bookings as $booking)
            @php
                $isPending = $booking->payment_status == 'pending' && $booking->status == 'pending';
                $isActive = $booking->status == 'confirmed';
                $isDone = $booking->status == 'completed';
                $isCancelled = $booking->status == 'cancelled';

                $waText = rawurlencode(
                    "Halo, saya mau tanya tentang booking studio:\n" .
                    "Studio: {$booking->studio->nama_studio}\n" .
                    "Tanggal: {$booking->tanggal_booking->format('d M Y')}\n" .
                    "Jam: {$booking->jam_mulai->format('H:i')} - {$booking->jam_selesai->format('H:i')}\n" .
                    "Status: {$booking->status_label}"
                );

                $imgUrl = $booking->studio?->gambar_utama
                    ? asset('storage/' . $booking->studio->gambar_utama)
                    : null;
            @endphp

            <div class="card bg-white border border-[#e5e5e7]">
                <div class="p-4">
                    <div class="flex items-start gap-4">
                        <!-- Image -->
                        <div class="shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-[#f5f5f7] hidden sm:block">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" class="w-full h-full object-cover" alt="{{ $booking->studio->nama_studio }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-lineflex="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="text-sm font-medium">{{ $booking->studio->nama_studio }}</h4>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        <span class="!text-[10px] !px-2 !py-0.5 badge-outline">Booking Studio</span>
                                        <span class="text-xs text-[#86868b]">{{ $booking->tanggal_booking->format('d M Y') }} &middot; {{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-sm font-bold">{{ $booking->grand_total_formatted ?? $booking->total_harga_formatted }}</span>
                                </div>
                            </div>

                            <!-- Detail -->
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="text-xs text-[#6e6e73]">
                                    {{ $booking->tipe_booking_label }}
                                    @if($booking->paketStudio)
                                        &middot; {{ $booking->paketStudio->nama_paket }}
                                    @endif
                                    &middot; {{ $booking->durasi_jam }} jam
                                </span>
                            </div>

                            @if($booking->catatan)
                                <p class="text-xs text-[#86868b] mt-1">Catatan: {{ $booking->catatan }}</p>
                            @endif

                            <!-- Status badges -->
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <span class="!text-[10px] !px-2 !py-0.5 {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span>
                                <span class="!text-[10px] !px-2 !py-0.5
                                    {{ $booking->status == 'confirmed' ? 'badge-brand' : '' }}
                                    {{ $booking->status == 'pending' ? 'badge-warning' : '' }}
                                    {{ $booking->status == 'completed' ? 'badge-success' : '' }}
                                    {{ $booking->status == 'cancelled' ? 'badge-error' : '' }}">
                                    {{ $booking->status_label }}
                                </span>
                                @if($booking->diskon_voucher > 0)
                                    <span class="text-xs text-[#34c759]">Diskon: -{{ $booking->diskon_voucher_formatted }}</span>
                                @endif
                            </div>

                            <!-- Progress bar for active bookings -->
                            @if(!$isDone && !$isCancelled)
                                @php
                                    $steps = ['pending', 'confirmed', 'completed'];
                                    $cIdx = array_search($booking->status, $steps);
                                    $pct = $cIdx > 0 ? ($cIdx / (count($steps) - 1)) * 100 : 0;
                                @endphp
                                <div class="w-full mt-2">
                                    <div class="w-full bg-[#f5f5f7] rounded-full h-1.5">
                                        <div class="bg-[#1d1d1f] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] text-[#86868b] mt-0.5">
                                        <span>Booking</span>
                                        <span>Selesai</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                @if($isPending)
                                    <a href="{{ route('customer.studio.payment', $booking->id) }}" class="btn-dark-apple btn-xs">
                                        <svg class="h-3.5 w-3.5 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-lineflex="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Bayar
                                    </a>
                                    <form method="POST" action="{{ route('customer.studio.booking.cancel', $booking->id) }}" class="inline" onsubmit="return confirm('Batalkan booking ini?')">
                                        @csrf @method('PUT')
                                        <button type="submit" class="hover:bg-[#f5f5f7] rounded-xl p-2 transition-all btn-xs text-[#d70015]">Batal</button>
                                    </form>
                                @endif
                                <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="!text-xs text-[#25D366] hover:bg-[#25D366]/10 border-[#25D366]/30">
                                    <svg class="h-3.5 w-3.5 me-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                    Tanya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card bg-white border border-[#e5e5e7]">
                <div class="p-5 text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-[#86868b] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-lineflex="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-[#6e6e73] mb-4">Belum ada booking studio.</p>
                    <a href="{{ route('customer.studio.index') }}" class="btn-dark-apple btn-sm">Lihat Studio</a>
                </div>
            </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
        <div class="flex justify-center">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

