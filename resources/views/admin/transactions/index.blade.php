@extends('layouts.admin')

@section('title', 'Manajemen Transaksi - Sewa Kamera Pro')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Manajemen Transaksi</h1>
        <div>
            <a href="{{ route('admin.transactions.create.manual') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Transaksi Manual
            </a>
            <a href="{{ route('admin.transactions.export') }}" class="btn btn-outline btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export
            </a>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="label"><span class="label-text">Cari Transaksi</span></label>
                    <input type="text" class="input input-bordered w-full" id="search" name="search" value="{{ request('search') }}" placeholder="Kode atau nama customer...">
                </div>
                <div>
                    <label for="status_transaksi" class="label"><span class="label-text">Status Transaksi</span></label>
                    <select class="select select-bordered w-full" id="status_transaksi" name="status_transaksi">
                        <option value="">Semua Status</option>
                        @foreach($statusTransaksi as $key => $value)
                            <option value="{{ $key }}" {{ request('status_transaksi') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status_pembayaran" class="label"><span class="label-text">Status Pembayaran</span></label>
                    <select class="select select-bordered w-full" id="status_pembayaran" name="status_pembayaran">
                        <option value="">Semua Status</option>
                        @foreach($statusPembayaran as $key => $value)
                            <option value="{{ $key }}" {{ request('status_pembayaran') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_date" class="label"><span class="label-text">Tanggal Mulai</span></label>
                    <input type="date" class="input input-bordered w-full" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label for="end_date" class="label"><span class="label-text">Tanggal Akhir</span></label>
                    <input type="date" class="input input-bordered w-full" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID Transaksi</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status Transaksi</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                                <td>
                                    <strong>{{ $transaction->kode_transaksi }}</strong>
                                    <div class="text-xs text-base-content/60">
                                        @if($transaction->user){{ $transaction->user->email }}@else{{ $transaction->email_customer }}@endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $transaction->nama_customer }}</strong>
                                    <div class="text-xs text-base-content/60">{{ $transaction->telepon_customer }}</div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                        <div class="text-base-content/60">{{ $transaction->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        @php $details = $transaction->detailTransaksis ? $transaction->detailTransaksis->take(2) : collect(); @endphp
                                        @if($details->count() > 0)
                                            @foreach($details as $detail)
                                                <div>{{ $detail->nama_produk }} ({{ $detail->jumlah }})</div>
                                            @endforeach
                                            @if($transaction->detailTransaksis && $transaction->detailTransaksis->count() > 2)
                                                <div class="text-base-content/60">+{{ $transaction->detailTransaksis->count() - 2 }} produk lainnya</div>
                                            @endif
                                        @else
                                            <div class="text-base-content/60">Tidak ada produk</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-primary">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong>
                                    <div class="text-xs text-base-content/60">{{ $transaction->lama_sewa }} hari</div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'badge-ghost', 'menunggu_pembayaran' => 'badge-warning', 'diproses' => 'badge-info',
                                            'dikonfirmasi' => 'badge-primary', 'dikemas' => 'badge-primary', 'dikirim' => 'badge-success',
                                            'dalam_perjalanan' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'
                                        ];
                                        $statusTexts = [
                                            'draft' => 'Draft', 'menunggu_pembayaran' => 'Menunggu Pembayaran', 'diproses' => 'Diproses',
                                            'dikonfirmasi' => 'Dikonfirmasi', 'dikemas' => 'Dikemas', 'dikirim' => 'Dikirim',
                                            'dalam_perjalanan' => 'Dalam Perjalanan', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$transaction->status_transaksi] ?? 'badge-ghost' }}">
                                        {{ $statusTexts[$transaction->status_transaksi] ?? $transaction->status_transaksi }}
                                    </span>
                                    @if($transaction->paid_at)
                                        <div class="text-xs text-base-content/60 mt-1">{{ $transaction->paid_at->format('d M') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $paymentColors = [
                                            'pending' => 'badge-warning', 'capture' => 'badge-info', 'settlement' => 'badge-success',
                                            'deny' => 'badge-error', 'cancel' => 'badge-ghost', 'expire' => 'badge-ghost',
                                            'failure' => 'badge-error', 'refund' => 'badge-info', 'partial_refund' => 'badge-info', 'chargeback' => 'badge-error'
                                        ];
                                    @endphp
                                    <span class="badge {{ $paymentColors[$transaction->status_pembayaran] ?? 'badge-ghost' }}">
                                        {{ ucfirst($transaction->status_pembayaran) }}
                                    </span>
                                    @if($transaction->paymentMethod)
                                        <div class="text-xs text-base-content/60 mt-1">{{ $transaction->paymentMethod->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-ghost" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        @if($transaction->status_transaksi == 'selesai')
                                            <a href="{{ route('admin.transactions.print', $transaction->id) }}" target="_blank" class="btn btn-sm btn-ghost text-success" title="Print Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-base-content/60">Tidak ada transaksi ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-4">
                <div>
                    <p class="text-sm text-base-content/60">
                        Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                    </p>
                </div>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
