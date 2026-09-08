@extends('layouts.admin')

@section('title', 'Manajemen Transaksi')
@section('page-title', 'Manajemen Transaksi')
@section('page-subtitle', 'Gabungan sewa, studio & layanan • Filter & export')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Manajemen Transaksi</h1>
            <p class="text-xs text-[#86868b] mt-1">Semua tipe transaksi — auto layout ke card di HP</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('admin.transactions.create.manual') }}" class="inline-flex items-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition"><x-admin.icon name="plus" :size="14" /> Transaksi Manual</a>
            <a href="{{ route('admin.transactions.export') }}" class="inline-flex items-center gap-1.5 bg-white border border-[#e5e5e7] text-xs font-medium rounded-full px-4 py-2 hover:bg-[#f5f5f7] transition"><x-admin.icon name="download" :size="14" /> Export</a>
        </div>
    </div>

    <x-admin.card>
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Cari</label>
                <div class="flex items-center gap-2 bg-[#f5f5f7] border border-transparent rounded-full px-3 py-2 focus-within:bg-white focus-within:border-[#1d1d1f] transition">
                    <x-admin.icon name="search" :size="14" color="text-[#86868b]" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau kode..." class="bg-transparent border-0 p-0 text-xs w-full focus:ring-0 outline-none placeholder:text-[#86868b]">
                </div>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status Transaksi</label>
                <select name="status_transaksi" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua</option>
                    @foreach($statusTransaksi as $key => $value)
                        <option value="{{ $key }}" {{ request('status_transaksi') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status Pembayaran</label>
                <select name="status_pembayaran" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua</option>
                    @foreach($statusPembayaran as $key => $value)
                        <option value="{{ $key }}" {{ request('status_pembayaran') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-apple-sm !rounded-full">
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-apple-sm !rounded-full">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2.5 hover:bg-black transition">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73] rounded-full px-3 py-2.5 hover:bg-[#e8e8ed] transition"><x-admin.icon name="x" :size="16" /></a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-4 py-3 font-medium">#</th>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Tipe</th>
                        <th class="px-4 py-3 font-medium">Pelanggan</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium text-right">Total</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Bayar</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($paginated as $transaction)
                        <tr class="hover:bg-[#f5f5f7]/50 transition">
                            <td class="px-4 py-3 text-xs text-[#86868b]">{{ $loop->iteration + ($paginated->currentPage() - 1) * $paginated->perPage() }}</td>
                            <td class="px-4 py-3 font-mono text-xs font-semibold text-[#1d1d1f]">{{ $transaction->kode }}</td>
                            <td class="px-4 py-3">
                                @php $tipeColor = $transaction->tipe === 'studio' ? 'bg-violet-50 text-violet-700 border-violet-100' : ($transaction->tipe === 'sewa_kamera' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]'); @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $tipeColor }}">{{ $transaction->tipe_label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-[#1d1d1f]">{{ $transaction->nama_pelanggan }}</div>
                                <div class="text-xs text-[#86868b] truncate max-w-[160px]">{{ $transaction->user->email ?? ($transaction->email_customer ?? '') }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-[#6e6e73] whitespace-nowrap">{{ $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-[#1d1d1f] text-xs">Rp {{ number_format($transaction->grand_total ?? $transaction->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $sMap = ['pending' => 'bg-amber-50 text-amber-700 border-amber-100', 'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'completed' => 'bg-blue-50 text-blue-700 border-blue-100', 'cancelled' => 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]', 'menunggu_pembayaran' => 'bg-amber-50 text-amber-700 border-amber-100', 'dikonfirmasi' => 'bg-blue-50 text-blue-700 border-blue-100', 'siap_diambil' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'dibatalkan' => 'bg-red-50 text-[#d70015] border-red-100', 'ditolak' => 'bg-red-50 text-[#d70015] border-red-100'];
                                    $sLabels = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'menunggu_pembayaran' => 'Menunggu Bayar', 'dikonfirmasi' => 'Dikonfirmasi', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $sMap[$transaction->status_global] ?? 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]' }}">{{ $sLabels[$transaction->status_global] ?? $transaction->status_global }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php $pMap = ['pending' => 'bg-amber-50 text-amber-700 border-amber-100', 'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'settlement' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'capture' => 'bg-blue-50 text-blue-700 border-blue-100', 'failed' => 'bg-red-50 text-[#d70015] border-red-100', 'expired' => 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]']; @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $pMap[$transaction->status_bayar] ?? 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]' }}">{{ ucfirst($transaction->status_bayar ?? 'pending') }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ $transaction->detail_link }}" class="inline-flex items-center gap-1 bg-white border border-[#e5e5e7] rounded-full px-2.5 py-1 text-xs font-medium text-[#6e6e73] hover:bg-[#f5f5f7] transition"><x-admin.icon name="eye" :size="12" /> Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-5 py-10 text-center"><x-admin.empty title="Tidak ada transaksi ditemukan" icon="transactions" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden divide-y divide-[#f5f5f7]">
            @forelse($paginated as $transaction)
                <div class="p-4 flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-semibold text-[#1d1d1f]">{{ $transaction->kode }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full border {{ $transaction->tipe === 'studio' ? 'bg-violet-50 text-violet-700 border-violet-100' : 'bg-blue-50 text-blue-700 border-blue-100' }}">{{ $transaction->tipe_label }}</span>
                    </div>
                    <div class="text-sm font-medium text-[#1d1d1f]">{{ $transaction->nama_pelanggan }}</div>
                    <div class="text-xs text-[#86868b]">{{ $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-' }} • Rp {{ number_format($transaction->grand_total ?? 0,0,',','.') }}</div>
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex gap-1 flex-wrap">
                            @php $s = $transaction->status_global; $p = $transaction->status_bayar; @endphp
                            <span class="text-[11px] px-2 py-0.5 rounded-full border bg-amber-50 border-amber-100">{{ $s }}</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full border bg-emerald-50 border-emerald-100">{{ $p }}</span>
                        </div>
                        <a href="{{ $transaction->detail_link }}" class="text-xs bg-white border border-[#e5e5e7] rounded-full px-2.5 py-1">Detail</a>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Tidak ada transaksi" icon="transactions" /></div>
            @endforelse
        </div>

        <div class="px-5 py-4 border-t border-[#f0f0f2] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <span class="text-xs text-[#86868b]">Menampilkan {{ $paginated->firstItem() }}–{{ $paginated->lastItem() }} dari {{ $paginated->total() }} transaksi</span>
            <div>{{ $paginated->links() }}</div>
        </div>
    </x-admin.card>
</div>
@endsection
