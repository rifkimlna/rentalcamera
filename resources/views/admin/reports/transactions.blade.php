@extends('layouts.admin')

@section('title', 'Laporan Transaksi')
@section('page-title', 'Laporan Transaksi')
@section('page-subtitle', $startDate->format('d M Y') . ' — ' . $endDate->format('d M Y') . ' • ' . number_format($transactions->total(),0,',','.') . ' transaksi')

@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-1 p-1 bg-[#f5f5f7] rounded-full w-fit border border-[#e5e5e7]/60 overflow-x-auto no-scrollbar max-w-full">
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Ringkasan</a>
        <a href="{{ route('admin.reports.transactions') }}" class="px-4 py-1.5 text-xs font-medium rounded-full bg-[#1d1d1f] text-white shadow-sm whitespace-nowrap">Transaksi</a>
        <a href="{{ route('admin.reports.products') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Produk</a>
        <a href="{{ route('admin.reports.users') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Customer</a>
    </div>

    <form action="{{ route('admin.reports.transactions') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white border border-[#e5e5e7] rounded-full px-2 py-2 shadow-sm w-fit max-w-full">
        <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
            <x-admin.icon name="calendar" :size="14" color="text-[#86868b]" />
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
        </div>
        <span class="text-xs text-[#86868b] hidden sm:inline">—</span>
        <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
        </div>
        <button type="submit" class="bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition inline-flex items-center gap-1.5"><x-admin.icon name="filter" :size="14"/> Tampilkan</button>
        <a href="{{ route('admin.reports.transactions') }}" class="bg-[#f5f5f7] text-[#6e6e73] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#e8e8ed] transition">Reset</a>
        <a href="{{ route('admin.reports.export', ['type' => 'transactions']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="inline-flex items-center gap-1.5 bg-white border border-[#e5e5e7] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#f5f5f7] transition"><x-admin.icon name="download" :size="14"/> Export</a>
    </form>

    @php
        $totalPendapatan = $transactions->getCollection()->sum(fn($t) => in_array($t->status_pembayaran, ['settlement','capture','paid']) ? $t->grand_total : 0);
    @endphp
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white border border-[#e5e5e7] rounded-[20px] p-4">
            <div class="text-[10px] font-semibold tracking-widest uppercase text-[#86868b]">Transaksi</div>
            <div class="text-[20px] font-semibold tracking-tight text-[#1d1d1f] mt-1">{{ number_format($transactions->total(),0,',','.') }}</div>
            <div class="text-xs text-[#86868b]">periode ini</div>
        </div>
        <div class="bg-white border border-[#e5e5e7] rounded-[20px] p-4">
            <div class="text-[10px] font-semibold tracking-widest uppercase text-[#86868b]">Pendapatan (halaman)</div>
            <div class="text-[20px] font-semibold tracking-tight text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
            <div class="text-xs text-[#86868b]">settlement / paid</div>
        </div>
        <div class="bg-white border border-[#e5e5e7] rounded-[20px] p-4">
            <div class="text-[10px] font-semibold tracking-widest uppercase text-[#86868b]">Rata-rata</div>
            <div class="text-[20px] font-semibold tracking-tight text-[#1d1d1f] mt-1">Rp {{ $transactions->total() ? number_format($totalPendapatan / max(1,$transactions->count()),0,',','.') : 0 }}</div>
            <div class="text-xs text-[#86868b]">per trx halaman</div>
        </div>
    </div>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">Kode</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Metode</th>
                        <th class="px-5 py-3 font-medium text-right">Total</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-[#fbfbfd] transition">
                        <td class="px-5 py-3 font-mono text-xs font-semibold text-[#1d1d1f]">{{ $t->kode_transaksi }}</td>
                        <td class="px-5 py-3 text-xs text-[#6e6e73] whitespace-nowrap">{{ $t->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-3">
                            <div class="text-sm font-medium text-[#1d1d1f]">{{ $t->nama_customer }}</div>
                            <div class="text-xs text-[#86868b] truncate max-w-[180px]">{{ $t->email_customer }}</div>
                        </td>
                        <td class="px-5 py-3"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">{{ $t->paymentMethod?->name ?? '-' }}</span></td>
                        <td class="px-5 py-3 text-right font-semibold text-[#1d1d1f] text-xs">Rp {{ number_format($t->grand_total,0,',','.') }}</td>
                        <td class="px-5 py-3">
                            @php $ok = in_array($t->status_pembayaran, ['settlement','capture','paid']); @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $ok ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">{{ $t->status_pembayaran }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center"><x-admin.empty title="Tidak ada transaksi pada periode ini" icon="transactions" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($transactions as $t)
                <div class="p-4 flex flex-col gap-2">
                    <div class="flex items-start justify-between gap-3">
                        <span class="font-mono text-xs font-semibold text-[#1d1d1f]">{{ $t->kode_transaksi }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ in_array($t->status_pembayaran,['settlement','capture','paid']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">{{ $t->status_pembayaran }}</span>
                    </div>
                    <div class="text-sm font-medium text-[#1d1d1f]">{{ $t->nama_customer }}</div>
                    <div class="text-xs text-[#86868b]">{{ $t->email_customer }} • {{ $t->created_at->format('d M Y H:i') }}</div>
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($t->grand_total,0,',','.') }}</span>
                        <span class="text-xs text-[#6e6e73] bg-[#f5f5f7] border border-[#e5e5e7] rounded-full px-2 py-0.5">{{ $t->paymentMethod?->name ?? '-' }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Tidak ada transaksi" icon="transactions" /></div>
            @endforelse
        </div>
        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-[#f0f0f2] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-xs text-[#86868b]">Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} dari {{ $transactions->total() }}</span>
                <div>{{ $transactions->links() }}</div>
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
