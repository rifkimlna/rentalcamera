@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<x-flash-messages />
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-[#6e6e73]">Ringkasan bisnis bulan {{ Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-5">
            <div class="text-[#0071e3]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-2xl font-bold text-[#0071e3]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="text-xs text-[#86868b]">Total Pendapatan</div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-5">
            <div class="text-[#34c759]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <div class="text-2xl font-bold text-[#34c759]">{{ number_format($totalOrders, 0, ',', '.') }}</div>
            <div class="text-xs text-[#86868b]">Total Transaksi</div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-5">
            <div class="text-[#0071e3]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            </div>
            <div class="text-2xl font-bold text-[#0071e3]">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
            <div class="text-xs text-[#86868b]">Total Pelanggan</div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-5">
            <div class="text-[#ff9500]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
            </div>
            <div class="text-2xl font-bold text-[#ff9500]">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            <div class="text-xs text-[#86868b]">Total Produk</div>
            <div class="text-xs text-[#86868b]">{{ $availableProducts }} tersedia</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-[#1d1d1f]">Transaksi Terbaru</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $t)
                        <tr class="hover">
                            <td><strong>{{ $t->kode_transaksi }}</strong></td>
                            <td>
                                <div>{{ $t->nama_customer }}</div>
                                <div class="text-xs opacity-60">{{ $t->email_customer }}</div>
                            </td>
                            <td><strong>Rp {{ number_format($t->grand_total, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($t->status_pembayaran == 'settlement')
                                    <span class="badge-success !text-[10px] !px-2 !py-0.5">Sukses</span>
                                @elseif($t->status_pembayaran == 'pending')
                                    <span class="badge-warning !text-[10px] !px-2 !py-0.5">Pending</span>
                                @elseif($t->status_pembayaran == 'expire')
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5">Expired</span>
                                @else
                                    <span class="badge-error !text-[10px] !px-2 !py-0.5">Gagal</span>
                                @endif
                            </td>
                            <td class="text-sm">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 opacity-60">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
