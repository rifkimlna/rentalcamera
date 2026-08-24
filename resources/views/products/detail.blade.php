@extends('layouts.app')

@php
    $allImages = collect();
    if ($product->gambar_utama) {
        $allImages->push($product->gambar_utama);
    }
    $additional = $product->gambar_tambahan;
    if (!empty($additional)) {
        $additional = is_array($additional) ? $additional : ((json_decode($additional, true)) ?? []);
        foreach ($additional as $img) {
            $allImages->push($img);
        }
    }
    $spesifikasi = $product->spesifikasi;
    if (!is_array($spesifikasi)) {
        $spesifikasi = (json_decode($spesifikasi, true)) ?? [];
    }
    $fitur = $product->fitur;
    if (!is_array($fitur) && !empty($fitur)) {
        $fitur = array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', (string) $fitur)));
    }
    $stokTersedia = (int) $product->stok_tersedia;
@endphp

@section('title', $product->nama_produk . ' - Sewa per Hari')

@section('meta-description', $product->deskripsi_singkat ?: ('Sewa ' . $product->nama_produk . ' mulai Rp ' . number_format($product->harga_per_hari, 0, ',', '.') . ' per hari di Stekpro Multimedia & Broadcast.'))

@section('content')
<div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-6 lg:py-10">

    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="mb-6">
        <ol class="flex items-center gap-1.5 text-xs sm:text-sm text-[#86868b] flex-wrap">
            <li><a href="{{ route('home') }}" class="hover:text-[#1d1d1f] transition-colors">Beranda</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ route('products') }}" class="hover:text-[#1d1d1f] transition-colors">Equipment</a></li>
            <li aria-hidden="true">/</li>
            <li class="text-[#1d1d1f] font-medium truncate max-w-[200px] sm:max-w-none">{{ $product->nama_produk }}</li>
        </ol>
    </nav>

    <div class="grid lg:grid-cols-2 gap-8 lg:gap-14">

        {{-- Gallery --}}
        <div x-data="{ active: 0 }" class="lg:sticky lg:top-24 self-start">
            <div class="relative rounded-3xl overflow-hidden bg-[#f5f5f7] aspect-square mb-3">
                @if($allImages->isNotEmpty())
                    @foreach($allImages as $index => $img)
                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->nama_produk }} - foto {{ $index + 1 }}"
                             x-show="active === {{ $index }}" x-transition.opacity.duration.300ms
                             class="absolute inset-0 w-full h-full object-cover">
                    @endforeach
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                @endif
                @if($stokTersedia <= 0)
                    <span class="absolute top-4 left-4 badge-error">Stok Habis</span>
                @endif
            </div>

            @if($allImages->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($allImages as $index => $img)
                        <button type="button" @click="active = {{ $index }}"
                                :class="active === {{ $index }} ? 'ring-2 ring-[#0071e3]' : 'opacity-60 hover:opacity-100'"
                                class="rounded-xl overflow-hidden bg-[#f5f5f7] aspect-square transition-all">
                            <img src="{{ asset('storage/' . $img) }}" alt="Thumbnail {{ $index + 1 }}" loading="lazy" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div>
            <div class="flex items-center gap-2 mb-3 flex-wrap">
                @if($product->kategori)
                    <span class="badge-apple">{{ $product->kategori->nama_kategori }}</span>
                @endif
                @if($product->brand)
                    <span class="badge-apple">{{ $product->brand->nama_brand }}</span>
                @endif
                @if($product->kondisi)
                    <span class="badge-success capitalize">{{ $product->kondisi }}</span>
                @endif
            </div>

            <h1 class="text-2xl lg:text-4xl font-bold text-[#1d1d1f] tracking-tight leading-tight">{{ $product->nama_produk }}</h1>

            <div class="flex items-center gap-2 mt-3">
                <div class="flex items-center gap-0.5" aria-hidden="true">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= round($product->rating) ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-sm text-[#86868b]">{{ $product->rating }} ({{ $product->jumlah_ulasan }} ulasan)</span>
            </div>

            @if($product->deskripsi_singkat)
                <p class="text-[#6e6e73] mt-4 leading-relaxed">{{ $product->deskripsi_singkat }}</p>
            @endif

            {{-- Price Card --}}
            <div class="card-apple-static p-5 lg:p-6 mt-6">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl lg:text-4xl font-bold text-[#1d1d1f] tracking-tight">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</span>
                    <span class="text-sm text-[#86868b]">/hari</span>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                    <div class="flex items-center gap-2 {{ $stokTersedia > 0 ? 'text-[#34c759]' : 'text-[#d70015]' }}">
                        <span class="w-2 h-2 rounded-full {{ $stokTersedia > 0 ? 'bg-[#34c759]' : 'bg-[#d70015]' }}"></span>
                        {{ $stokTersedia > 0 ? $stokTersedia . ' unit tersedia' : 'Stok habis' }}
                    </div>
                    <div class="text-[#6e6e73]">Min. sewa {{ $product->minimum_sewa }} hari</div>
                    @if($product->maximum_sewa)
                        <div class="text-[#6e6e73] col-span-2">Maks. sewa {{ $product->maximum_sewa }} hari</div>
                    @endif
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-3">
                    @auth
                        @if($stokTersedia > 0 && auth()->user()->isCustomer())
                            <a href="{{ route('customer.products.show', $product->slug) }}" class="btn-primary-apple flex-1 justify-center !py-3.5">Sewa Sekarang</a>
                        @elseif(!auth()->user()->isCustomer())
                            <span class="flex-1 inline-flex items-center justify-center py-3.5 rounded-full bg-[#f5f5f7] text-[#86868b] text-sm font-medium">Akun admin tidak dapat menyewa</span>
                        @else
                            <span class="flex-1 inline-flex items-center justify-center py-3.5 rounded-full bg-[#f5f5f7] text-[#86868b] text-sm font-medium cursor-not-allowed">Stok Habis</span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-apple flex-1 justify-center !py-3.5">Login untuk Menyewa</a>
                        <a href="{{ route('register') }}" class="btn-outline-apple flex-1 justify-center !py-3.5">Daftar Gratis</a>
                    @endauth
                </div>
            </div>

            {{-- Quick Specs --}}
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 mt-6 text-sm">
                @if($product->kode_produk)
                    <div class="flex justify-between border-b border-[#f0f0f2] pb-2"><dt class="text-[#86868b]">Kode</dt><dd class="font-medium text-[#1d1d1f]">{{ $product->kode_produk }}</dd></div>
                @endif
                @if($product->tahun_pembuatan)
                    <div class="flex justify-between border-b border-[#f0f0f2] pb-2"><dt class="text-[#86868b]">Tahun</dt><dd class="font-medium text-[#1d1d1f]">{{ $product->tahun_pembuatan }}</dd></div>
                @endif
                @if($product->berat)
                    <div class="flex justify-between border-b border-[#f0f0f2] pb-2"><dt class="text-[#86868b]">Berat</dt><dd class="font-medium text-[#1d1d1f]">{{ $product->berat }} kg</dd></div>
                @endif
                @if($product->dimensi)
                    <div class="flex justify-between border-b border-[#f0f0f2] pb-2"><dt class="text-[#86868b]">Dimensi</dt><dd class="font-medium text-[#1d1d1f]">{{ $product->dimensi }}</dd></div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Description & Specs --}}
    <div class="grid lg:grid-cols-2 gap-8 mt-12 lg:mt-16">
        @if($product->deskripsi_lengkap)
            <section class="card-apple-static p-6 lg:p-8">
                <h2 class="text-lg font-semibold text-[#1d1d1f] mb-4">Deskripsi Produk</h2>
                <div class="prose prose-sm prose-neutral max-w-none text-[#494950]">{!! nl2br(e($product->deskripsi_lengkap)) !!}</div>
            </section>
        @endif

        @if(count($spesifikasi) > 0 || !empty($fitur))
            <section class="card-apple-static p-6 lg:p-8">
                @if(count($spesifikasi) > 0)
                    <h2 class="text-lg font-semibold text-[#1d1d1f] mb-4">Spesifikasi</h2>
                    <table class="w-full text-sm mb-6">
                        <tbody>
                            @foreach($spesifikasi as $key => $value)
                                <tr class="border-b border-[#f0f0f2] last:border-0">
                                    <td class="py-2.5 pr-4 text-[#86868b] align-top w-2/5">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                    <td class="py-2.5 font-medium text-[#1d1d1f]">{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if(!empty($fitur))
                    <h2 class="text-lg font-semibold text-[#1d1d1f] mb-4">Fitur Utama</h2>
                    <ul class="space-y-2.5">
                        @foreach($fitur as $item)
                            <li class="flex items-start gap-2.5 text-sm text-[#494950]">
                                <svg class="w-4 h-4 text-[#34c759] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endif
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <section class="mt-12 lg:mt-16">
            <h2 class="text-xl lg:text-2xl font-bold text-[#1d1d1f] tracking-tight mb-6">Equipment Serupa</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
