@extends('layouts.admin')

@section('title', 'Laporan Produk')
@section('page-title', 'Laporan Produk')
@section('page-subtitle', $startDate->format('d M Y') . ' — ' . $endDate->format('d M Y'))

@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-1 p-1 bg-[#f5f5f7] rounded-full w-fit border border-[#e5e5e7]/60 overflow-x-auto no-scrollbar max-w-full">
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Ringkasan</a>
        <a href="{{ route('admin.reports.transactions') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Transaksi</a>
        <a href="{{ route('admin.reports.products') }}" class="px-4 py-1.5 text-xs font-medium rounded-full bg-[#1d1d1f] text-white shadow-sm whitespace-nowrap">Produk</a>
        <a href="{{ route('admin.reports.users') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Customer</a>
    </div>

    <form action="{{ route('admin.reports.products') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white border border-[#e5e5e7] rounded-full px-2 py-2 shadow-sm w-fit max-w-full">
        <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
            <x-admin.icon name="calendar" :size="14" color="text-[#86868b]" />
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
        </div>
        <span class="text-xs text-[#86868b] hidden sm:inline">—</span>
        <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
        </div>
        <button type="submit" class="bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition inline-flex items-center gap-1.5"><x-admin.icon name="filter" :size="14"/> Tampilkan</button>
        <a href="{{ route('admin.reports.products') }}" class="bg-[#f5f5f7] text-[#6e6e73] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#e8e8ed] transition">Reset</a>
        <a href="{{ route('admin.reports.export', ['type' => 'products']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="inline-flex items-center gap-1.5 bg-white border border-[#e5e5e7] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#f5f5f7] transition"><x-admin.icon name="download" :size="14"/> Export</a>
    </form>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">Produk</th>
                        <th class="px-5 py-3 font-medium">Kategori</th>
                        <th class="px-5 py-3 font-medium text-right">Harga/Hari</th>
                        <th class="px-5 py-3 font-medium text-right">Stok</th>
                        <th class="px-5 py-3 font-medium text-right">Dipesan</th>
                        <th class="px-5 py-3 font-medium text-right">Ulasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($products as $p)
                    <tr class="hover:bg-[#fbfbfd] transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center text-[#86868b] shrink-0 overflow-hidden">
                                    @if($p->gambar_utama)
                                        <img src="{{ asset('storage/' . $p->gambar_utama) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <x-admin.icon name="package" :size="16" />
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-[#1d1d1f] truncate max-w-[220px]">{{ $p->nama_produk }}</div>
                                    <div class="text-xs font-mono text-[#86868b]">{{ $p->kode_produk }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-50 text-violet-700 border border-violet-100">{{ $p->kategori?->nama_kategori ?? '-' }}</span></td>
                        <td class="px-5 py-3 text-right font-semibold text-[#1d1d1f] text-xs">Rp {{ number_format($p->harga_per_hari,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right text-xs"><span class="text-emerald-600 font-medium">{{ $p->stok_tersedia }}</span><span class="text-[#86868b]"> / {{ $p->stok_total }}</span></td>
                        <td class="px-5 py-3 text-right text-xs font-medium text-[#1d1d1f]">{{ $p->jumlah_dipesan }}</td>
                        <td class="px-5 py-3 text-right text-xs text-[#6e6e73]">{{ $p->jumlah_ulasan }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada produk" icon="package" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($products as $p)
                <div class="p-4 flex gap-3">
                    <div class="w-12 h-12 rounded-xl bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center shrink-0 overflow-hidden">
                        @if($p->gambar_utama)<img src="{{ asset('storage/' . $p->gambar_utama) }}" class="w-full h-full object-cover">@else<x-admin.icon name="package" :size="16" color="text-[#86868b]" />@endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $p->nama_produk }}</div>
                        <div class="text-xs font-mono text-[#86868b]">{{ $p->kode_produk }} • {{ $p->kategori?->nama_kategori ?? '-' }}</div>
                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                            <span class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($p->harga_per_hari,0,',','.') }}/hari</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $p->stok_tersedia }}/{{ $p->stok_total }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada produk" icon="package" /></div>
            @endforelse
        </div>
        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-[#f0f0f2] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-xs text-[#86868b]">Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }}</span>
                <div>{{ $products->links() }}</div>
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
