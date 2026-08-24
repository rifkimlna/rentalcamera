@extends('layouts.app')

@section('title', 'Harga Sewa')

@section('content')
<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <p class="text-sm font-medium text-[#86868b] mb-4 tracking-wide">Harga Sewa</p>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Harga Sewa Kamera</h1>
        <p class="text-base text-[#6e6e73] mb-12 max-w-lg">Harga transparan tanpa biaya tersembunyi. Pilih durasi sewa sesuai kebutuhan Anda.</p>

        <div class="card-apple-static overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#f0f0f2]">
                            <th class="text-left text-xs font-semibold text-[#86868b] uppercase tracking-wider px-6 py-4">Produk</th>
                            <th class="text-left text-xs font-semibold text-[#86868b] uppercase tracking-wider px-6 py-4">Kategori</th>
                            <th class="text-right text-xs font-semibold text-[#86868b] uppercase tracking-wider px-6 py-4">Per Hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b border-[#f0f0f2] last:border-0 hover:bg-[#fafafa] transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('product.detail', $product->slug) }}" class="text-sm font-medium text-[#1d1d1f] hover:text-[#0071e3] transition-colors">{{ $product->nama_produk }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#6e6e73]">{{ $product->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-[#1d1d1f]">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center text-sm text-[#86868b]">Belum ada produk tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section-darker">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-20 text-center">
        <h2 class="text-3xl font-bold tracking-tight text-white mb-4">Butuh penawaran khusus?</h2>
        <p class="text-base text-white/50 mb-8">Hubungi kami untuk harga sewa dalam jumlah banyak atau durasi panjang.</p>
        <a href="{{ route('contact') }}" class="btn-primary-apple">Hubungi Kami</a>
    </div>
</section>
@endsection
