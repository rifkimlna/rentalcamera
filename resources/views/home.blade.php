@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-24 md:py-32">
        <div class="max-w-2xl">
            <p class="text-sm text-base-content/50 mb-4 tracking-wide uppercase">Sewa Kamera Profesional</p>
            <h1 class="text-4xl md:text-5xl font-light tracking-tight text-base-content leading-tight mb-6">
                Sewa kamera untuk<br>
                karya terbaik Anda
            </h1>
            <p class="text-base text-base-content/60 leading-relaxed mb-8 max-w-lg">
                Perlengkapan fotografi berkualitas untuk pemula hingga profesional. Proses mudah, harga transparan.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('customer.products.index') }}" class="btn btn-neutral">Lihat Katalog</a>
                @guest
                <a href="{{ route('register') }}" class="btn btn-outline btn-neutral">Daftar</a>
                @endguest
            </div>
        </div>
    </div>
</section>

<section class="border-t border-base-300 bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-2xl font-light">500+</p>
                <p class="text-sm text-base-content/50">Produk</p>
            </div>
            <div>
                <p class="text-2xl font-light">2.000+</p>
                <p class="text-sm text-base-content/50">Pelanggan</p>
            </div>
            <div>
                <p class="text-2xl font-light">5.000+</p>
                <p class="text-sm text-base-content/50">Transaksi</p>
            </div>
            <div>
                <p class="text-2xl font-light">98%</p>
                <p class="text-sm text-base-content/50">Puas</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-base-200">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="mb-12">
            <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Keunggulan</p>
            <h2 class="text-2xl font-light">Mengapa memilih kami</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-base-100 p-6 rounded-box">
                <p class="font-medium text-sm mb-2">Peralatan Terjamin</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Semua alat diperiksa rutin. Kondisi prima sebelum sampai ke tangan Anda.</p>
            </div>
            <div class="bg-base-100 p-6 rounded-box">
                <p class="font-medium text-sm mb-2">Pengiriman Cepat</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Same-day delivery untuk Jakarta. Gratis ongkir minimal Rp 500.000.</p>
            </div>
            <div class="bg-base-100 p-6 rounded-box">
                <p class="font-medium text-sm mb-2">Bantuan 24/7</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Tim support siap membantu kapan pun melalui WhatsApp, telepon, dan email.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="mb-12">
            <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Kategori</p>
            <h2 class="text-2xl font-light">Pilih kebutuhan Anda</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-4">
            @forelse($categories as $category)
            <a href="{{ route('customer.products.category', $category->slug) }}" class="relative overflow-hidden rounded-box bg-base-200 h-64 group cursor-pointer block">
                @if($category->icon)
                <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->nama_kategori }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-base-300 to-base-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                    <p class="font-medium">{{ $category->nama_kategori }}</p>
                    <p class="text-sm text-white/70">{{ $category->deskripsi ?: $category->available_product_count . ' produk tersedia' }}</p>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-12 text-base-content/60">
                <p>Belum ada kategori tersedia.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-8 text-center">
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline btn-neutral btn-sm">Lihat Semua</a>
        </div>
    </div>
</section>

<section class="bg-base-200">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="mb-12">
            <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Cara Sewa</p>
            <h2 class="text-2xl font-light">Empat langkah mudah</h2>
        </div>
        <div class="grid md:grid-cols-4 gap-6 text-center">
            <div>
                <p class="text-3xl font-light text-base-content/30 mb-3">01</p>
                <p class="font-medium text-sm mb-1">Daftar Akun</p>
                <p class="text-xs text-base-content/50">Buat akun dalam 2 menit</p>
            </div>
            <div>
                <p class="text-3xl font-light text-base-content/30 mb-3">02</p>
                <p class="font-medium text-sm mb-1">Pilih Produk</p>
                <p class="text-xs text-base-content/50">Temukan yang Anda butuhkan</p>
            </div>
            <div>
                <p class="text-3xl font-light text-base-content/30 mb-3">03</p>
                <p class="font-medium text-sm mb-1">Bayar</p>
                <p class="text-xs text-base-content/50">Pilih tanggal dan bayar</p>
            </div>
            <div>
                <p class="text-3xl font-light text-base-content/30 mb-3">04</p>
                <p class="font-medium text-sm mb-1">Ambil / Kirim</p>
                <p class="text-xs text-base-content/50">Ambil di toko atau dikirim</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="mb-12">
            <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Testimoni</p>
            <h2 class="text-2xl font-light">Kata pelanggan</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @php
                $testimonials = [
                    ['name' => 'Budi Santoso', 'role' => 'Fotografer Wedding', 'text' => 'Pelayanan memuaskan. Kamera selalu dalam kondisi prima.'],
                    ['name' => 'Sari Dewi', 'role' => 'Content Creator', 'text' => 'Harga terjangkau untuk kualitas terbaik. Sudah 5 kali sewa.'],
                    ['name' => 'Rizky Pratama', 'role' => 'Videographer', 'text' => 'Pengiriman tepat waktu. Peralatan lengkap. Recommended!']
                ];
            @endphp
            @foreach($testimonials as $t)
            <div class="bg-base-200 p-6 rounded-box">
                <p class="text-sm text-base-content/70 leading-relaxed mb-4 italic">"{{ $t['text'] }}"</p>
                <p class="text-sm font-medium">{{ $t['name'] }}</p>
                <p class="text-xs text-base-content/50">{{ $t['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-base-200">
    <div class="max-w-6xl mx-auto px-4 py-16 text-center">
        <h2 class="text-2xl font-light mb-3">Siap menyewa?</h2>
        <p class="text-sm text-base-content/60 mb-6">Daftar sekarang dan dapatkan diskon 20% untuk sewa pertama.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-neutral">Daftar Sekarang</a>
        @else
        <a href="{{ route('customer.products.index') }}" class="btn btn-neutral">Sewa Sekarang</a>
        @endguest
    </div>
</section>
@endsection
