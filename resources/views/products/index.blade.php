@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Produk</p>
        <h1 class="text-3xl font-light tracking-tight mb-8">Katalog Produk</h1>

        <form method="GET" action="{{ route('products') }}" class="bg-base-100 border border-base-300 rounded-box p-4 mb-8">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs text-base-content/50 block mb-1">Cari</label>
                    <input type="text" name="search" class="input input-bordered w-full input-sm" value="{{ request('search') }}" placeholder="Nama produk...">
                </div>
                <div>
                    <label class="text-xs text-base-content/50 block mb-1">Kategori</label>
                    <select name="category" class="select select-bordered select-sm w-full">
                        <option value="">Semua</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-base-content/50 block mb-1">Brand</label>
                    <select name="brand" class="select select-bordered select-sm w-full">
                        <option value="">Semua</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->slug }}" {{ request('brand') == $b->slug ? 'selected' : '' }}>{{ $b->nama_brand }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-base-content/50 block mb-1">Urutkan</label>
                    <select name="sort" class="select select-bordered select-sm w-full">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-neutral btn-sm">Terapkan Filter</button>
                <a href="{{ route('products') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </form>

        @if($products->count() > 0)
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
            <a href="{{ route('product.detail', $product->slug) }}" class="no-underline group">
                <div class="bg-base-100 border border-base-300 rounded-box overflow-hidden">
                    <div class="aspect-[4/3] bg-base-200 overflow-hidden">
                        @if($product->gambar_utama)
                            <img src="{{ asset('storage/' . $product->gambar_utama) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-1">{{ $product->kategori->nama_kategori ?? 'Kamera' }}</p>
                        <p class="font-medium text-sm mb-1">{{ $product->nama_produk }}</p>
                        <p class="text-lg font-light mb-3">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}<span class="text-xs text-base-content/40"> /hari</span></p>
                        <span class="btn btn-outline btn-neutral btn-sm w-full group-hover:btn-neutral transition-colors">Sewa</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="flex justify-center mt-8">
            {{ $products->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-16">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-base-content/30 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
            </svg>
            <p class="text-base text-base-content/50 mb-2">Produk tidak ditemukan</p>
            <p class="text-sm text-base-content/40 mb-4">Coba gunakan filter yang berbeda</p>
            <a href="{{ route('products') }}" class="btn btn-ghost btn-sm">Reset Filter</a>
        </div>
        @endif
    </div>
</section>
@endsection
