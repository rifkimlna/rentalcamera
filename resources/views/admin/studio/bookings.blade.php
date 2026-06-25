@extends('layouts.admin')

@section('title', 'Booking Studio')
@section('page-title', 'Booking Studio')

@section('content')
<div class="card bg-base-100 shadow-md mb-4">
    <div class="card-body py-3 px-4">
        <form method="GET" class="flex flex-wrap gap-x-3 gap-y-2 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="label py-0 mb-0.5"><span class="label-text text-xs">Cari</span></label>
                <input type="text" name="search" class="input input-bordered input-sm w-full" placeholder="Nama user atau studio..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="label py-0 mb-0.5"><span class="label-text text-xs">Studio</span></label>
                <select name="studio_id" class="select select-bordered select-sm">
                    <option value="">Semua</option>
                    @foreach($studios as $s)
                        <option value="{{ $s->id }}" {{ request('studio_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_studio }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label py-0 mb-0.5"><span class="label-text text-xs">Status</span></label>
                <select name="status" class="select select-bordered select-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="label py-0 mb-0.5"><span class="label-text text-xs">Pembayaran</span></label>
                <select name="payment_status" class="select select-bordered select-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="expired" {{ request('payment_status') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refund</option>
                </select>
            </div>
            <div class="flex gap-1">
                <button type="submit" class="btn btn-sm">Cari</button>
                <a href="{{ route('admin.studio.bookings') }}" class="btn btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card bg-base-100 shadow-md">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table text-sm">
                <thead>
                    <tr class="text-xs uppercase text-base-content/50">
                        <th class="w-10">#</th>
                        <th>Penyewa</th>
                        <th>Studio</th>
                        <th class="hidden lg:table-cell">Detail</th>
                        <th class="text-right">Total</th>
                        <th>Pembayaran</th>
                        <th class="hidden md:table-cell">Status</th>
                        <th class="w-[140px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-base-200/50">
                        <td class="text-base-content/40">{{ $loop->iteration }}</td>
                        <td>
                            <div class="font-medium text-sm">{{ $booking->user->nama ?? '-' }}</div>
                            <div class="text-xs text-base-content/50">{{ $booking->user->email ?? '' }}</div>
                        </td>
                        <td>
                            <div class="text-sm">{{ $booking->studio->nama_studio ?? '-' }}</div>
                            <div class="text-xs text-base-content/50">{{ $booking->tipe_booking_label }}</div>
                        </td>
                        <td class="hidden lg:table-cell text-xs text-base-content/60">
                            <div>{{ $booking->tanggal_booking->format('d M Y') }}</div>
                            <div>{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</div>
                            <div>{{ $booking->durasi_jam }} jam</div>
                        </td>
                        <td class="text-right">
                            <div class="font-medium text-sm">{{ number_format($booking->grand_total, 0, ',', '.') }}</div>
                            @if($booking->admin_fee > 0)
                            <div class="text-xs text-base-content/40">+{{ number_format($booking->admin_fee, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-xs text-base-content/60 leading-tight">{{ $booking->paymentMethod->name ?? '-' }}</div>
                            <span class="badge badge-sm {{ $booking->payment_status_badge }}">{{ $booking->payment_status_label }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="badge badge-sm {{ $booking->status == 'confirmed' ? 'badge-success' : ($booking->status == 'pending' ? 'badge-warning' : ($booking->status == 'completed' ? 'badge-info' : 'badge-ghost')) }}">
                                {{ $booking->status_label }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.studio.booking.update-status', $booking->id) }}" class="flex gap-1">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="{{ $booking->status }}">
                                <input type="hidden" name="payment_status" value="{{ $booking->payment_status }}">
                                @if($booking->status == 'pending')
                                    <button type="submit" name="status" value="confirmed" class="btn btn-xs btn-success" onclick="return confirm('Konfirmasi booking ini?')">Confirm</button>
                                @endif
                                @if($booking->status == 'confirmed')
                                    <button type="submit" name="status" value="completed" class="btn btn-xs btn-info" onclick="return confirm('Tandai selesai?')">Selesai</button>
                                @endif
                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <button type="submit" name="status" value="cancelled" class="btn btn-xs btn-ghost text-error" onclick="return confirm('Batalkan booking ini?')">Batal</button>
                                @endif
                                @if($booking->payment_status == 'pending')
                                    <button type="submit" name="payment_status" value="paid" class="btn btn-xs btn-primary" onclick="return confirm('Tandai sudah bayar?')">Bayar</button>
                                @endif
                                @if($booking->payment_status == 'paid')
                                    <button type="submit" name="payment_status" value="refunded" class="btn btn-xs btn-ghost text-warning" onclick="return confirm('Refund pembayaran?')">Refund</button>
                                @endif
                                <a href="{{ route('admin.studio.booking.print', $booking->id) }}" class="btn btn-xs" target="_blank" title="Cetak Struk">Cetak</a>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-base-content/40 py-8">Belum ada booking studio</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="p-4 border-t border-base-200">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
@endsection
