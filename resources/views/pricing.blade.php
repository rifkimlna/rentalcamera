@extends('layouts.app')

@section('title', 'Harga Sewa')

@section('content')
<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Harga Sewa</p>
        <h1 class="text-3xl font-light tracking-tight mb-3">Harga Sewa Kamera</h1>
        <p class="text-sm text-base-content/50 mb-10 max-w-lg">Harga transparan tanpa biaya tersembunyi. Pilih durasi sewa sesuai kebutuhan Anda.</p>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-xs text-base-content/40 uppercase tracking-wider border-b border-base-300">
                        <th class="font-normal pb-3">Produk</th>
                        <th class="font-normal pb-3">Kategori</th>
                        <th class="font-normal pb-3 text-right">Per Hari</th>
                        <th class="font-normal pb-3 text-right">Per Minggu</th>
                        <th class="font-normal pb-3 text-right">Per Bulan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-base-200 text-sm">
                        <td class="py-3">
                            <a href="{{ route('product.detail', $product->slug) }}" class="link link-hover font-medium">{{ $product->nama_produk }}</a>
                        </td>
                        <td class="py-3 text-base-content/50">{{ $product->kategori->nama_kategori ?? '-' }}</td>
                        <td class="py-3 text-right">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</td>
                        <td class="py-3 text-right">
                            @if($product->harga_per_minggu)
                                Rp {{ number_format($product->harga_per_minggu, 0, ',', '.') }}
                            @else
                                <span class="text-base-content/30">-</span>
                            @endif
                        </td>
                        <td class="py-3 text-right">
                            @if($product->harga_per_bulan)
                                Rp {{ number_format($product->harga_per_bulan, 0, ',', '.') }}
                            @else
                                <span class="text-base-content/30">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-base-content/40 text-sm">Belum ada produk tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-10 bg-base-200 border border-base-300 rounded-box p-6 max-w-2xl">
            <p class="font-medium text-sm mb-2">Informasi Deposit</p>
            <p class="text-sm text-base-content/60 leading-relaxed">
                Setiap penyewaan dikenakan deposit sebesar 20% dari total biaya sewa. 
                Deposit akan dikembalikan sepenuhnya setelah peralatan dikembalikan dalam 
                kondisi baik dan lengkap sesuai dengan pemeriksaan. Pembayaran deposit 
                dapat dilakukan melalui transfer bank atau dompet digital.
            </p>
        </div>
    </div>
</section>
@endsection
