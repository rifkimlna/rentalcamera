@extends('layouts.admin')

@section('title', 'Manajemen Transaksi - Stekpro Multimedia & Broadcast')
@section('page-title', 'Manajemen Transaksi')

@section('content')
<x-flash-messages />
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Manajemen Transaksi</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.transactions.create.manual') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Transaksi Manual
            </a>
            <a href="{{ route('admin.transactions.export') }}" class="btn-dark-apple-outline-apple btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
        <div class="p-5">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Cari</span></label>
                    <input type="text" class="input-apple w-full input-sm" name="search" value="{{ request('search') }}" placeholder="Nama atau kode...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status Transaksi</span></label>
                    <select class="select-apple w-full select-sm" name="status_transaksi">
                        <option value="">Semua</option>
                        @foreach($statusTransaksi as $key => $value)
                            <option value="{{ $key }}" {{ request('status_transaksi') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status Pembayaran</span></label>
                    <select class="select-apple w-full select-sm" name="status_pembayaran">
                        <option value="">Semua</option>
                        @foreach($statusPembayaran as $key => $value)
                            <option value="{{ $key }}" {{ request('status_pembayaran') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Mulai</span></label>
                    <input type="date" class="input-apple w-full input-sm" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Akhir</span></label>
                    <input type="date" class="input-apple w-full input-sm" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Filter</button>
                    <a href="{{ route('admin.transactions.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Tipe</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginated as $transaction)
                            <tr>
                                <td>{{ $loop->iteration + ($paginated->currentPage() - 1) * $paginated->perPage() }}</td>
                                <td>
                                    <strong class="text-xs font-mono">{{ $transaction->kode }}</strong>
                                </td>
                                <td>
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 {{ $transaction->tipe == 'sewa_kamera' ? 'badge-brand' : ($transaction->tipe == 'studio' ? 'badge-brand' : 'badge-apple') }}">
                                        {{ $transaction->tipe_label }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-sm">{{ $transaction->nama_pelanggan }}</strong>
                                    <div class="text-xs text-[#6e6e73]">{{ $transaction->user->email ?? ($transaction->email_customer ?? '') }}</div>
                                </td>
                                <td>
                                    <div class="text-xs">{{ $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-' }}</div>
                                </td>
                                <td>
                                    <strong class="text-[#0071e3] text-xs">Rp {{ number_format($transaction->grand_total ?? $transaction->total_harga ?? 0, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $sColors = ['pending' => 'badge-warning', 'confirmed' => 'badge-success', 'completed' => 'badge-brand', 'cancelled' => 'badge-apple', 'menunggu_pembayaran' => 'badge-warning', 'dikonfirmasi' => 'badge-brand', 'siap_diambil' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                                        $sLabels = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'menunggu_pembayaran' => 'Menunggu Bayar', 'dikonfirmasi' => 'Dikonfirmasi', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'];
                                    @endphp
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 {{ $sColors[$transaction->status_global] ?? 'badge-apple' }}">
                                        {{ $sLabels[$transaction->status_global] ?? $transaction->status_global }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $pColors = ['pending' => 'badge-warning', 'paid' => 'badge-success', 'settlement' => 'badge-success', 'capture' => 'badge-brand', 'failed' => 'badge-error', 'expired' => 'badge-apple', 'cancelled' => 'badge-apple', 'deny' => 'badge-error'];
                                    @endphp
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 {{ $pColors[$transaction->status_bayar] ?? 'badge-apple' }}">
                                        {{ ucfirst($transaction->status_bayar ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ $transaction->detail_link }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-[#6e6e73]">Tidak ada transaksi ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-4">
                <div>
                    <p class="text-sm text-[#6e6e73]">
                        Menampilkan {{ $paginated->firstItem() }} - {{ $paginated->lastItem() }} dari {{ $paginated->total() }} transaksi
                    </p>
                </div>
                <div>
                    {{ $paginated->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
