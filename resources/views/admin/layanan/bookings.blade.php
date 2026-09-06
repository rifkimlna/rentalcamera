@extends('layouts.admin')

@section('title', 'Booking Layanan')
@section('page-title', 'Booking Layanan')

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
    <div class="py-3 px-4">
        <form method="GET" class="flex flex-wrap gap-x-3 gap-y-2 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Cari</span></label>
                <input type="text" name="search" class="input-apple input-sm w-full" placeholder="Nama user atau layanan..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Layanan</span></label>
                <select name="layanan_id" class="select-apple !text-sm">
                    <option value="">Semua</option>
                    @foreach($layanans as $l)
                        <option value="{{ $l->id }}" {{ request('layanan_id') == $l->id ? 'selected' : '' }}>{{ $l->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Status</span></label>
                <select name="status" class="select-apple !text-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Pembayaran</span></label>
                <select name="payment_status" class="select-apple !text-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="expired" {{ request('payment_status') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refund</option>
                </select>
            </div>
            <div class="flex gap-1">
                <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                <a href="{{ route('admin.layanan.bookings') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs uppercase text-[#86868b]">
                        <th class="w-10">#</th>
                        <th>Penyewa</th>
                        <th>Layanan</th>
                        <th class="hidden lg:table-cell">Detail</th>
                        <th class="text-right">Total</th>
                        <th>Pembayaran</th>
                        <th class="hidden md:table-cell">Status</th>
                        <th class="w-[140px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-[#f5f5f7]/50">
                        <td class="text-[#86868b]">{{ $loop->iteration }}</td>
                        <td>
                            <div class="font-medium text-sm">{{ $booking->user->nama ?? '-' }}</div>
                            <div class="text-xs text-[#86868b]">{{ $booking->user->email ?? '' }}</div>
                        </td>
                        <td>
                            <div class="text-sm">{{ $booking->layanan->nama_layanan ?? '-' }}</div>
                            <div class="text-xs text-[#86868b]">{{ $booking->tipe_booking_label }}</div>
                        </td>
                        <td class="hidden lg:table-cell text-xs text-[#6e6e73]">
                            <div>{{ $booking->tanggal_booking->format('d M Y') }}</div>
                            <div>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</div>
                            <div>{{ $booking->durasi_jam }} jam</div>
                        </td>
                        <td class="text-right">
                            <div class="font-medium text-sm">{{ number_format($booking->grand_total, 0, ',', '.') }}</div>
                            @if($booking->admin_fee > 0)
                            <div class="text-xs text-[#86868b]">+{{ number_format($booking->admin_fee, 0, ',', '.') }}</div>
                            @endif
                            @if($booking->diskon_voucher > 0)
                            <div class="text-xs text-[#34c759]">Diskon: -{{ number_format($booking->diskon_voucher, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-xs text-[#6e6e73] leading-tight">{{ $booking->paymentMethod->name ?? '-' }}</div>
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 {{ $booking->status == 'confirmed' ? 'badge-success' : ($booking->status == 'pending' ? 'badge-warning' : ($booking->status == 'completed' ? 'badge-brand' : 'badge-apple')) }}">
                                {{ $booking->status_label }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.layanan.booking.update-status', $booking->id) }}" class="flex gap-1">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="{{ $booking->status }}">
                                <input type="hidden" name="payment_status" value="{{ $booking->payment_status }}">
                                @if($booking->status == 'pending')
                                    <button type="submit" name="status" value="confirmed" class="btn-dark-apple !text-[10px] !px-2 !py-0.5" onclick="return confirm('Konfirmasi booking ini?')">Confirm</button>
                                @endif
                                @if($booking->status == 'confirmed')
                                    <button type="submit" name="status" value="completed" class="btn-dark-apple !text-[10px] !px-2 !py-0.5" onclick="return confirm('Tandai selesai?')">Selesai</button>
                                @endif
                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <button type="submit" name="status" value="cancelled" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]" onclick="return confirm('Batalkan booking ini?')">Batal</button>
                                @endif
                                @if($booking->payment_status == 'pending')
                                    <button type="submit" name="payment_status" value="paid" class="btn-dark-apple !text-[10px] !px-2 !py-0.5" onclick="return confirm('Tandai sudah bayar?')">Bayar</button>
                                @endif
                                @if($booking->payment_status == 'paid')
                                    <button type="submit" name="payment_status" value="refunded" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#ff9500]" onclick="return confirm('Refund pembayaran?')">Refund</button>
                                @endif
                                <a href="{{ route('admin.layanan.booking.print', $booking->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors" target="_blank" title="Cetak Struk">Cetak</a>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-[#86868b] py-8">Belum ada booking layanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="p-4 border-t border-[#f0f0f2]">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
@endsection
