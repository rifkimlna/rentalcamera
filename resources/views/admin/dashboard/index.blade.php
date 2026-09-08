@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan bisnis bulan ' . Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY'))

@section('content')
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <x-admin.kpi label="Total Pendapatan" :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')" hint="Semua settlement" icon="money" color="emerald" />
        <x-admin.kpi label="Total Transaksi" :value="number_format($totalOrders, 0, ',', '.')" hint="Transaksi tercatat" icon="transactions" color="blue" />
        <x-admin.kpi label="Total Pelanggan" :value="number_format($totalCustomers, 0, ',', '.')" hint="Customer terdaftar" icon="users" color="violet" />
        <x-admin.kpi label="Total Produk" :value="number_format($totalProducts, 0, ',', '.')" :hint="$availableProducts . ' tersedia'" icon="package" color="amber" />
    </div>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#1d1d1f]">Transaksi Terbaru</h2>
                <p class="text-xs text-[#86868b] mt-0.5">5 transaksi terkini • Auto-layout tabel ke card di HP</p>
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] rounded-full px-3 py-1.5 hover:bg-[#e8e8ed] transition shrink-0">Lihat Semua <x-admin.icon name="chevron-down" :size="12" /></a>
        </div>

        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-y border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">Kode</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium text-right">Total</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($recentTransactions as $t)
                    <tr class="hover:bg-[#f5f5f7]/50 transition">
                        <td class="px-5 py-3 font-mono text-xs font-semibold text-[#1d1d1f]">{{ $t->kode_transaksi }}</td>
                        <td class="px-5 py-3">
                            <div class="text-sm font-medium text-[#1d1d1f]">{{ $t->nama_customer }}</div>
                            <div class="text-xs text-[#86868b]">{{ $t->email_customer }}</div>
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-[#1d1d1f] text-xs">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @if($t->status_pembayaran == 'settlement')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Sukses</span>
                            @elseif($t->status_pembayaran == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                            @elseif($t->status_pembayaran == 'expire')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">Expired</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-[#d70015] border border-red-100">Gagal</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-[#6e6e73] whitespace-nowrap">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada transaksi" icon="transactions" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($recentTransactions as $t)
                <div class="p-4 flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-semibold text-[#1d1d1f]">{{ $t->kode_transaksi }}</span>
                        @if($t->status_pembayaran == 'settlement')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Sukses</span>
                        @elseif($t->status_pembayaran == 'pending')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-50 text-[#d70015] border border-red-100">{{ $t->status_pembayaran }}</span>
                        @endif
                    </div>
                    <div class="text-sm font-medium text-[#1d1d1f]">{{ $t->nama_customer }}</div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</span>
                        <span class="text-xs text-[#86868b]">{{ $t->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada transaksi" icon="transactions" /></div>
            @endforelse
        </div>
    </x-admin.card>
</div>
@endsection
