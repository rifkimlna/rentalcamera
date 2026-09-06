@extends('layouts.admin')

@section('title', 'Laporan Produk')

@section('content')
<x-flash-messages />
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-lg font-semibold">Laporan Produk</h1>
        <form action="{{ route('admin.reports.products') }}" method="GET" class="flex items-center gap-2">
            <input type="date" class="input-apple input-xs w-36" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <span class="text-xs text-[#86868b]">s/d</span>
            <input type="date" class="input-apple input-xs w-36" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Tampilkan</button>
            <a href="{{ route('admin.reports.products') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Reset</a>
            <a href="{{ route('admin.reports.export', ['type' => 'products']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors btn-outline">Export</a>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="pb-2 font-medium">Kode</th>
                        <th class="pb-2 font-medium">Produk</th>
                        <th class="pb-2 font-medium">Kategori</th>
                        <th class="pb-2 font-medium">Brand</th>
                        <th class="pb-2 font-medium text-right">Harga/Hari</th>
                        <th class="pb-2 font-medium text-right">Stok</th>
                        <th class="pb-2 font-medium text-right">Dipesan</th>
                        <th class="pb-2 font-medium text-right">Ulasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-medium">{{ $product->kode_produk }}</td>
                        <td class="py-2">
                            <div class="font-medium">{{ $product->nama_produk }}</div>
                            <div class="text-gray-400">{{ Str::limit($product->deskripsi_singkat, 40) }}</div>
                        </td>
                        <td class="py-2">{{ $product->kategori ? $product->kategori->nama_kategori : '-' }}</td>
                        <td class="py-2">{{ $product->brand ? $product->brand->nama_brand : '-' }}</td>
                        <td class="py-2 text-right">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</td>
                        <td class="py-2 text-right">{{ $product->stok_tersedia }} / {{ $product->stok_total }}</td>
                        <td class="py-2 text-right">{{ $product->jumlah_dipesan }}</td>
                        <td class="py-2 text-right">{{ $product->jumlah_ulasan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
