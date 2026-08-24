@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- HERO --}}
<section class="relative overflow-hidden bg-[#f5f5f7]">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 md:py-36 lg:py-44 text-center">
        <p class="text-sm font-medium text-[#86868b] mb-4 tracking-wide">Stekpro Multimedia & Broadcast</p>
        <h1 class="text-5xl md:text-7xl lg:text-[5.5rem] font-bold tracking-tight text-[#1d1d1f] leading-[1.05] mb-6 text-balance">
            Sewa kamera untuk<br class="hidden sm:block"> karya terbaik Anda
        </h1>
        <p class="text-lg md:text-xl text-[#6e6e73] leading-relaxed mb-10 max-w-xl mx-auto">
            Perlengkapan fotografi berkualitas untuk pemula hingga profesional. Proses mudah, harga transparan.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('customer.products.index') }}" class="btn-primary-apple !px-8 !py-3.5 !text-base">Lihat Katalog</a>
            @guest
            <a href="{{ route('register') }}" class="btn-outline-apple !px-8 !py-3.5 !text-base">Daftar Gratis</a>
            @endguest
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-16 lg:py-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            <div class="text-center reveal">
                <p class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f]">{{ number_format($stats['products']) }}+</p>
                <p class="text-sm text-[#86868b] mt-2">Produk</p>
            </div>
            <div class="text-center reveal reveal-delay-1">
                <p class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f]">{{ number_format($stats['customers']) }}+</p>
                <p class="text-sm text-[#86868b] mt-2">Pelanggan</p>
            </div>
            <div class="text-center reveal reveal-delay-2">
                <p class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f]">{{ number_format($stats['transactions']) }}+</p>
                <p class="text-sm text-[#86868b] mt-2">Transaksi</p>
            </div>
            <div class="text-center reveal reveal-delay-3">
                <p class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f]">{{ $stats['satisfaction'] }}%</p>
                <p class="text-sm text-[#86868b] mt-2">Puas</p>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
@if($categories->isNotEmpty())
<section class="section-light">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Pilih kebutuhan Anda</h2>
            <p class="text-lg text-[#6e6e73] max-w-lg mx-auto">Jelajahi kategori peralatan yang tersedia untuk disewa</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($categories->take(3) as $category)
                @php $categoryImage = $category->products->first()->gambar_utama ?? null; @endphp
                <a href="{{ route('customer.products.category', $category->slug) }}" class="group relative overflow-hidden rounded-2xl bg-[#f5f5f7] h-72 lg:h-80 block transition-all duration-500 hover:shadow-xl hover:-translate-y-1 reveal reveal-delay-{{ $loop->iteration }}">
                    @if($categoryImage)
                        <img src="{{ asset('storage/' . $categoryImage) }}" alt="{{ $category->nama_kategori }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <span class="absolute top-4 right-4 inline-flex items-center text-xs font-medium text-white/80 bg-white/15 backdrop-blur-md px-3 py-1.5 rounded-full">
                        {{ $category->available_product_count }} produk
                    </span>
                    <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8 text-white">
                        <h3 class="text-xl lg:text-2xl font-bold mb-1">{{ $category->nama_kategori }}</h3>
                        <p class="text-sm text-white/60 group-hover:text-white/80 transition-colors">Lihat peralatan kategori ini &rarr;</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('customer.products.index') }}" class="btn-dark-apple">Lihat Semua Equipment</a>
        </div>
    </div>
</section>
@else
<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-16 text-center">
        <p class="text-[#86868b]">Belum ada kategori tersedia.</p>
    </div>
</section>
@endif

{{-- HOW IT WORKS --}}
<section class="section-light">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Empat langkah mudah</h2>
            <p class="text-lg text-[#6e6e73]">Proses sewa yang simpel dan cepat</p>
        </div>
        <div class="grid md:grid-cols-4 gap-8 md:gap-6">
            @php
                $steps = [
                    ['num' => '01', 'title' => 'Daftar Akun', 'desc' => 'Buat akun dalam 2 menit. Gratis tanpa kartu kredit.'],
                    ['num' => '02', 'title' => 'Pilih Produk', 'desc' => 'Temukan peralatan yang Anda butuhkan dari katalog kami.'],
                    ['num' => '03', 'title' => 'Bayar', 'desc' => 'Pilih tanggal sewa dan lakukan pembayaran.'],
                    ['num' => '04', 'title' => 'Ambil Equipment', 'desc' => 'Ambil langsung di toko kami. Proses cepat.'],
                ];
            @endphp
            @foreach($steps as $i => $step)
                <div class="text-center reveal reveal-delay-{{ $i + 1 }}">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-[#f5f5f7] flex items-center justify-center">
                        <span class="text-2xl font-bold text-[#1d1d1f]">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#1d1d1f] mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-[#6e6e73] leading-relaxed max-w-xs mx-auto">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STUDIO --}}
@if($studios->isNotEmpty())
<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Studio kami</h2>
            <p class="text-lg text-[#6e6e73]">Ruang studio profesional untuk kebutuhan Anda</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($studios->take(3) as $studio)
                <a href="{{ route('customer.studio.show', $studio->slug) }}" class="group relative overflow-hidden rounded-2xl bg-[#e5e5e7] h-72 lg:h-80 block transition-all duration-500 hover:shadow-xl hover:-translate-y-1 reveal reveal-delay-{{ $loop->iteration }}">
                    @if($studio->gambar_utama)
                        <img src="{{ asset('storage/' . $studio->gambar_utama) }}" alt="{{ $studio->nama_studio }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#e5e5e7] to-[#d1d1d6]">
                            <svg class="h-16 w-16 text-[#c7c7cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <span class="absolute top-4 right-4 inline-flex items-center text-xs font-medium text-white/80 bg-white/15 backdrop-blur-md px-3 py-1.5 rounded-full">
                        Rp {{ number_format($studio->harga_per_jam, 0, ',', '.') }}/jam
                    </span>
                    <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8 text-white">
                        <h3 class="text-xl lg:text-2xl font-bold mb-1">{{ $studio->nama_studio }}</h3>
                        <p class="text-sm text-white/60 group-hover:text-white/80 transition-colors">Lihat studio ini &rarr;</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('customer.studio.index') }}" class="btn-dark-apple">Lihat Semua Studio</a>
        </div>
    </div>
</section>
@endif

{{-- LAYANAN --}}
@if($layanans->isNotEmpty())
<section class="section-light">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Layanan kami</h2>
            <p class="text-lg text-[#6e6e73]">Solusi lengkap untuk kebutuhan kreatif Anda</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($layanans->take(3) as $layanan)
                <a href="{{ route('customer.layanan.show', $layanan->slug) }}" class="group relative overflow-hidden rounded-2xl bg-[#f5f5f7] h-72 lg:h-80 block transition-all duration-500 hover:shadow-xl hover:-translate-y-1 reveal reveal-delay-{{ $loop->iteration }}">
                    @if($layanan->gambar_utama)
                        <img src="{{ asset('storage/' . $layanan->gambar_utama) }}" alt="{{ $layanan->nama_layanan }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                            <svg class="h-16 w-16 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <span class="absolute top-4 right-4 inline-flex items-center text-xs font-medium text-white/80 bg-white/15 backdrop-blur-md px-3 py-1.5 rounded-full">
                        Mulai Rp {{ number_format($layanan->harga_mulai, 0, ',', '.') }}
                    </span>
                    <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8 text-white">
                        <h3 class="text-xl lg:text-2xl font-bold mb-1">{{ $layanan->nama_layanan }}</h3>
                        <p class="text-sm text-white/60 group-hover:text-white/80 transition-colors">Lihat layanan ini &rarr;</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('customer.layanan.index') }}" class="btn-dark-apple">Lihat Semua Layanan</a>
        </div>
    </div>
</section>
@endif

{{-- TESTIMONIALS --}}
@if($testimonials->count() > 0)
<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Kata pelanggan</h2>
            <p class="text-lg text-[#6e6e73]">Apa kata mereka setelah menyewa bersama kami</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($testimonials as $t)
                <div class="card-apple-static p-6 lg:p-8 reveal reveal-delay-{{ $loop->iteration > 3 ? 3 : $loop->iteration }}">
                    <div class="flex items-center gap-0.5 mb-5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $t->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm text-[#6e6e73] leading-relaxed mb-6">"{{ $t->komentar }}"</p>
                    <div class="flex items-center gap-3 pt-5 border-t border-[#f0f0f2]">
                        <div class="w-10 h-10 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-sm font-semibold shrink-0">
                            {{ strtoupper(substr($t->user->nama ?? 'A', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-[#1d1d1f] truncate">{{ $t->user->nama ?? 'Anonymous' }}</p>
                            <p class="text-xs text-[#86868b] truncate">{{ $t->user->email ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                    <p class="text-[#86868b]">Belum ada testimoni.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
@if(count($faqs) > 0)
<section class="section-light">
    <div class="max-w-3xl mx-auto px-5 sm:px-8 py-24 lg:py-32">
        <div class="text-center mb-16 reveal">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-4">Pertanyaan umum</h2>
        </div>
        <div class="space-y-0 divide-y divide-[#e5e5e7] reveal">
            @foreach($faqs as $faq)
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between py-5 text-left group">
                        <span class="text-base font-medium text-[#1d1d1f] group-hover:text-[#0071e3] transition-colors pr-4">{{ $faq['question'] }}</span>
                        <svg :class="open ? 'rotate-45' : ''" class="w-5 h-5 text-[#86868b] shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
                    </button>
                    <div x-show="open" x-collapse x-cloak>
                        <p class="text-sm text-[#6e6e73] leading-relaxed pb-5">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA FINALE --}}
<section class="section-darker">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32 text-center reveal">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-white mb-4">Siap menyewa?</h2>
        <p class="text-lg text-white/50 mb-10 max-w-md mx-auto">Daftar sekarang dan mulai karya terbaik Anda.</p>
        @guest
            <a href="{{ route('register') }}" class="btn-primary-apple !px-10 !py-4 !text-base">Daftar Sekarang</a>
        @else
            <a href="{{ route('customer.products.index') }}" class="btn-primary-apple !px-10 !py-4 !text-base">Sewa Sekarang</a>
        @endguest
    </div>
</section>
@endsection
