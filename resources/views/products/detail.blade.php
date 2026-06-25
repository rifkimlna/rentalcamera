@extends('layouts.app')

@section('title', $product->nama_produk)

@section('content')
<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <nav class="text-sm text-base-content/50 mb-6">
            <a href="{{ route('home') }}" class="link link-hover">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products') }}" class="link link-hover">Produk</a>
            <span class="mx-2">/</span>
            <span class="text-base-content">{{ $product->nama_produk }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <div class="bg-base-200 rounded-box overflow-hidden mb-4">
                    @if($product->gambar_utama)
                        <img src="{{ asset('storage/' . $product->gambar_utama) }}" alt="{{ $product->nama_produk }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </div>
                    @endif
                </div>
                @php
                    $allImages = [$product->gambar_utama];
                    if ($product->gambar_tambahan) {
                        $additionalImages = json_decode($product->gambar_tambahan, true);
                        $allImages = array_merge($allImages, $additionalImages);
                    }
                    $allImages = array_filter($allImages);
                @endphp
                @if(count($allImages) > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($allImages as $img)
                    <div class="bg-base-200 rounded-box overflow-hidden cursor-pointer">
                        <img src="{{ asset('storage/' . $img) }}" alt="" class="w-full h-16 object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div>
                <div class="mb-4">
                    <div class="flex flex-wrap gap-2 mb-2">
                        @if($product->kategori)
                        <span class="text-xs bg-base-200 px-2 py-0.5 rounded-box text-base-content/60">{{ $product->kategori->nama_kategori }}</span>
                        @endif
                        @if($product->brand)
                        <span class="text-xs bg-base-200 px-2 py-0.5 rounded-box text-base-content/60">{{ $product->brand->nama_brand }}</span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-light tracking-tight mb-1">{{ $product->nama_produk }}</h1>
                    <p class="text-base-content/60 text-xs">
                        Rating {{ number_format($product->rating, 1) }} · {{ $product->jumlah_ulasan }} ulasan · {{ $product->jumlah_dipesan }}× disewa
                    </p>
                </div>

                <div class="mb-6">
                    <p class="text-3xl font-light">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }} <span class="text-sm text-base-content/40">/hari</span></p>
                    @if($product->harga_per_minggu)
                    <p class="text-sm text-base-content/50">Rp {{ number_format($product->harga_per_minggu, 0, ',', '.') }}/minggu</p>
                    @endif
                    @if($product->harga_per_bulan)
                    <p class="text-sm text-base-content/50">Rp {{ number_format($product->harga_per_bulan, 0, ',', '.') }}/bulan</p>
                    @endif
                </div>

                <div class="mb-6">
                    <p class="font-medium text-sm mb-2">Deskripsi</p>
                    <p class="text-sm text-base-content/60 leading-relaxed">{{ $product->deskripsi_singkat }}</p>
                </div>

                @php
                    $spesifikasi = json_decode($product->spesifikasi, true) ?? [];
                @endphp
                @if(count($spesifikasi) > 0)
                <div class="mb-6">
                    <p class="font-medium text-sm mb-2">Spesifikasi</p>
                    <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm">
                        @foreach($spesifikasi as $key => $value)
                        @if($value)
                        <div>
                            <span class="text-base-content/50">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="text-base-content">{{ $value }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

                @if($product->status == 'available' && $product->stok_tersedia > 0)
                <div class="bg-base-200 border border-base-300 rounded-box p-6">
                    <p class="font-medium text-sm mb-4">Sewa Produk Ini</p>
                    @auth
                    <form method="POST" action="{{ route('customer.cart.add', $product) }}">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $product->id }}">

                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="text-xs text-base-content/50 block mb-1">Tanggal Sewa</label>
                                <input type="date" name="tanggal_sewa" class="input input-bordered w-full input-sm" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="text-xs text-base-content/50 block mb-1">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" class="input input-bordered w-full input-sm" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs text-base-content/50 block mb-1">Jumlah</label>
                            <input type="number" name="jumlah" class="input input-bordered w-full input-sm" value="1" min="1" max="{{ $product->stok_tersedia }}">
                            <p class="text-xs text-base-content/40 mt-1">Stok: {{ $product->stok_tersedia }} unit</p>
                        </div>
                        <button type="submit" class="btn btn-neutral w-full">
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-base-200 border border-base-300 rounded-box p-6 text-center">
                    <a href="{{ route('login') }}" class="btn btn-neutral w-full">
                        Masuk untuk Menyewa
                    </a>
                </div>
                @endauth
                @else
                <div class="bg-base-200 border border-base-300 rounded-box p-6 text-center">
                    <p class="text-sm text-base-content/50">Produk sedang tidak tersedia</p>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-12">
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-6">Ulasan Pelanggan</p>
                @php
                    $approvedReviews = optional($product->ulasan)->where('status', 'approved') ?? collect();
                @endphp
                @if($approvedReviews->count() > 0)
                <div class="space-y-4">
                    @foreach($approvedReviews->take(5) as $ulasan)
                    <div class="border-b border-base-200 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-medium">{{ optional($ulasan->user)->nama ?? 'Anonim' }}</span>
                            <span class="text-xs text-base-content/40">·</span>
                            <span class="text-xs text-base-content/40">{{ $ulasan->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-sm text-base-content/60">{{ $ulasan->komentar }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-base-content/40 text-center py-8">Belum ada ulasan untuk produk ini.</p>
                @endif
            </div>
        </div>

        @if($relatedProducts->count() > 0)
        <div class="mt-10">
            <p class="font-medium text-sm mb-4">Produk Terkait</p>
            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                <a href="{{ route('product.detail', $related->slug) }}" class="no-underline group">
                    <div class="bg-base-100 border border-base-300 rounded-box overflow-hidden">
                        <div class="aspect-4/3 bg-base-200 overflow-hidden">
                            @if($related->gambar_utama)
                                <img src="{{ asset('storage/' . $related->gambar_utama) }}" alt="" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-medium">{{ $related->nama_produk }}</p>
                            <p class="text-xs text-base-content/40">Rp {{ number_format($related->harga_per_hari, 0, ',', '.') }}/hari</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
