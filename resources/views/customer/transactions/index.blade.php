@extends('layouts.customer')

@section('title', 'Transaksi Saya - Sewa Kamera Pro')
@section('page-title', 'Transaksi Saya')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        <p class="text-sm text-base-content/60">Kelola dan lihat riwayat transaksi Anda</p>
    </div>

    <div class="card bg-base-100 border border-base-300">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('customer.transactions.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi atau produk..." class="input input-bordered w-full text-sm">
                </div>
                <div class="w-full sm:w-44">
                    <select name="status" class="select select-bordered w-full text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-neutral btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('customer.transactions.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-300">
        <div class="overflow-x-auto">
            <table class="table table-zebra text-sm">
                <thead>
                    <tr class="text-xs text-base-content/60 uppercase tracking-wider">
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <span class="font-mono text-xs tracking-tight">{{ $transaction->kode_transaksi }}</span>
                            </td>
                            <td>
                                <span class="text-xs">{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="badge badge-outline text-xs">{{ $transaction->status_transaksi_label }}</span>
                            </td>
                            <td class="text-right text-xs">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('customer.transactions.show', $transaction->id) }}" class="btn btn-ghost btn-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <div class="text-base-content/40">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm mb-3">Belum ada transaksi</p>
                                    <a href="{{ route('customer.products.index') }}" class="btn btn-neutral btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Sewa Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($transactions->hasPages())
        <div class="flex justify-center">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
