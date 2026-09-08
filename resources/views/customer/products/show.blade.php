@extends('layouts.app')

@section('title', $product->nama_produk)

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 overflow-hidden">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1 sm:gap-1.5 text-[11px] sm:text-xs text-[#86868b] mb-4 sm:mb-6 overflow-x-auto">
        <a href="{{ route('home') }}" class="hover:text-[#1d1d1f] transition-colors shrink-0">Beranda</a>
        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('customer.products.index') }}" class="hover:text-[#1d1d1f] transition-colors shrink-0">Equipment</a>
        @if($product->kategori)
            <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('customer.products.index', ['kategori' => $product->kategori->slug]) }}" class="hover:text-[#1d1d1f] transition-colors shrink-0">{{ $product->kategori->nama_kategori }}</a>
        @endif
        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-[#1d1d1f] font-medium truncate">{{ $product->nama_produk }}</span>
    </nav>

    {{-- Product Hero — auto layout HP --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 lg:gap-8">

        {{-- Image Gallery --}}
        <div class="space-y-3 sm:space-y-3 sm:space-y-4 min-w-0">
            <div class="relative rounded-xl sm:rounded-2xl overflow-hidden bg-[#f5f5f7] aspect-[4/3] sm:aspect-square max-h-[60vh] sm:max-h-none flex items-center justify-center">
                @if($product->gambar_utama)
                    <img id="main-image" src="{{ asset('storage/' . $product->gambar_utama) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover sm:object-contain max-h-[380px] sm:max-h-none">
                @else
                    <div class="flex items-center justify-center w-full h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 sm:h-16 sm:w-16 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                @endif

                @if($product->is_featured || $product->is_recommended)
                    <div class="absolute top-2 right-2 sm:top-4 sm:right-4 flex flex-col items-end gap-1 sm:gap-1.5">
                        @if($product->is_featured)
                            <span class="inline-flex items-center text-[10px] sm:text-xs font-medium text-white/90 bg-white/15 backdrop-blur-md px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">Featured</span>
                        @endif
                        @if($product->is_recommended)
                            <span class="inline-flex items-center text-[10px] sm:text-xs font-medium text-white/90 bg-white/15 backdrop-blur-md px-2 sm:px-3 py-1 sm:py-1.5 rounded-full">Recommended</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @php
                $allImages = [$product->gambar_utama];
                if ($product->gambar_tambahan) {
                    $additionalImages = is_array($product->gambar_tambahan) ? $product->gambar_tambahan : (json_decode($product->gambar_tambahan, true) ?? []);
                    $allImages = array_merge($allImages, $additionalImages);
                }
                $allImages = array_filter($allImages);
            @endphp

            @if(count($allImages) > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($allImages as $index => $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="Gambar {{ $index + 1 }}"
                             class="rounded-xl aspect-square object-cover cursor-pointer border-2 transition-all duration-200 thumbnail-image {{ $index == 0 ? 'border-[#1d1d1f]' : 'border-transparent hover:border-[#d1d1d6]' }}"
                             data-image="{{ asset('storage/' . $image) }}">
                    @endforeach
                </div>
            @endif

            {{-- Share & Save --}}
            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-1">
                    <span class="text-xs text-[#86868b] mr-1">Bagikan:</span>
                    <a href="https://wa.me/?text={{ urlencode($product->nama_produk . ' - ' . request()->url()) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#f5f5f7] transition-colors text-[#25D366]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#f5f5f7] transition-colors text-[#1877F2]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href).then(() => { this.innerHTML = '<svg class=\'h-4 w-4 text-[#34c759]\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M5 13l4 4L19 7\'/></svg>'; setTimeout(() => this.innerHTML = '<svg class=\'h-4 w-4 text-[#86868b]\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1\'/></svg>'; }, 2000) })" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#f5f5f7] transition-colors">
                        <svg class="h-4 w-4 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </button>
                </div>
                <button id="saveProductBtn" class="w-9 h-9 rounded-full border border-[#e5e5e7] flex items-center justify-center hover:bg-[#f5f5f7] transition-colors">
                    <svg id="heartIcon" class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </div>
        </div>

        {{-- Product Info --}}
        <div class="lg:py-4 min-w-0 overflow-hidden">
            {{-- Badges --}}
            <div class="flex items-center gap-2 mb-4 flex-wrap min-w-0">
                @if($product->brand)
                    <span class="badge-brand">{{ $product->brand->nama_brand }}</span>
                @endif
                @if($product->kategori)
                    <span class="badge-apple">{{ $product->kategori->nama_kategori }}</span>
                @endif
            </div>

            <h1 class="text-base sm:text-lg lg:text-xl font-bold tracking-tight text-[#1d1d1f] mb-4 break-words [overflow-wrap:anywhere] whitespace-normal w-full max-w-full line-clamp-2 sm:line-clamp-none" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h1>

            {{-- Rating --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= $product->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-sm text-[#6e6e73]">{{ $product->rating }} ({{ $product->jumlah_ulasan }} ulasan)</span>
                <span class="text-sm text-[#86868b]">{{ $product->jumlah_dipesan }}x disewa</span>
            </div>

            {{-- Price --}}
            <div class="mb-6">
                <p class="text-base sm:text-lg lg:text-xl font-bold text-[#1d1d1f] tracking-tight">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}<span class="text-base font-normal text-[#86868b] ml-1">/hari</span></p>
            </div>

            <div class="divider-apple mb-6"></div>

            {{-- Description --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-2">Deskripsi</h3>
                <p class="text-sm text-[#6e6e73] leading-relaxed break-words [overflow-wrap:anywhere]">{{ $product->deskripsi_singkat }}</p>
                @if($product->deskripsi_lengkap)
                    <div id="descriptionCollapse" class="hidden mt-2">
                        <p class="text-sm text-[#6e6e73] leading-relaxed break-words [overflow-wrap:anywhere] whitespace-pre-wrap">{!! nl2br(e($product->deskripsi_lengkap)) !!}</p>
                    </div>
                    <button type="button" class="text-sm text-[#0071e3] hover:underline mt-2" onclick="document.getElementById('descriptionCollapse').classList.toggle('hidden'); this.textContent = document.getElementById('descriptionCollapse').classList.contains('hidden') ? 'Baca selengkapnya...' : 'Tutup'">Baca selengkapnya...</button>
                @endif
            </div>

            {{-- Features --}}
            @if($product->fitur)
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-3">Fitur Utama</h3>
                <ul class="space-y-2">
                    @foreach(preg_split('/\r\n|\n|,/', $product->fitur) as $fitur)
                        @if(trim($fitur))
                            <li class="flex items-start gap-2.5">
                                <svg class="h-4 w-4 mt-0.5 text-[#34c759] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-[#6e6e73] break-words [overflow-wrap:anywhere] min-w-0">{{ $fitur }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Specs --}}
            @php
                $spesifikasi = is_array($product->spesifikasi) ? $product->spesifikasi : (json_decode($product->spesifikasi, true) ?? []);
                $importantSpecs = array_slice($spesifikasi, 0, 3);
            @endphp

            @if(count($importantSpecs) > 0)
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-3">Spesifikasi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($importantSpecs as $key => $value)
                        @if($value)
                            <div class="p-3 rounded-xl bg-[#f5f5f7]">
                                <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">{{ str_replace('_', ' ', $key) }}</p>
                                <p class="text-sm font-medium text-[#1d1d1f] break-words [overflow-wrap:anywhere] min-w-0">{{ $value }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
                @if(count($spesifikasi) > 3)
                    <button type="button" class="text-sm text-[#0071e3] hover:underline mt-3" onclick="document.getElementById('specsCollapse').classList.toggle('hidden'); this.textContent = document.getElementById('specsCollapse').classList.contains('hidden') ? 'Lihat spesifikasi lengkap...' : 'Tutup'">Lihat spesifikasi lengkap...</button>
                    <div id="specsCollapse" class="hidden mt-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach(array_slice($spesifikasi, 3) as $key => $value)
                                @if($value)
                                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">{{ str_replace('_', ' ', $key) }}</p>
                                        <p class="text-sm font-medium text-[#1d1d1f] break-words [overflow-wrap:anywhere] min-w-0">{{ $value }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif

            {{-- Condition Info — auto HP 1 col --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                <div class="p-3 rounded-xl bg-[#f5f5f7]">
                    <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Kondisi</p>
                    <p class="text-sm font-medium text-[#1d1d1f] break-words [overflow-wrap:anywhere] min-w-0">{{ $product->kondisi_label }}</p>
                </div>
                @if($product->tahun_pembuatan)
                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Tahun</p>
                        <p class="text-sm font-medium text-[#1d1d1f] break-words min-w-0">{{ $product->tahun_pembuatan }}</p>
                    </div>
                @endif
                @if($product->berat)
                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Berat</p>
                        <p class="text-sm font-medium text-[#1d1d1f] break-words min-w-0">{{ $product->berat }} gram</p>
                    </div>
                @endif
                @if($product->dimensi)
                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Dimensi</p>
                        <p class="text-sm font-medium text-[#1d1d1f] break-words [overflow-wrap:anywhere] min-w-0">{{ $product->dimensi }}</p>
                    </div>
                @endif
            </div>

            {{-- Rental Form --}}
            @if($product->status == 'available' && $product->stok_tersedia > 0)
            <div class="card-apple-static p-3 sm:p-4 mb-4">
                <h3 class="text-xs sm:text-sm font-semibold text-[#1d1d1f] mb-3 sm:mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sewa Sekarang
                </h3>
                @auth
                <form action="{{ route('customer.checkout.direct-rent') }}" method="POST" id="rentalForm"
                      data-price="{{ $product->harga_per_hari }}"
                      data-stock="{{ $product->stok_tersedia }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Sewa</label>
                            <input type="date" class="input-apple rental-date !py-2 sm:!py-2.5 !text-xs sm:!text-sm w-full min-w-0" id="tanggal_sewa" name="tanggal_sewa" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Kembali</label>
                            <input type="date" class="input-apple rental-date !py-2 sm:!py-2.5 !text-xs sm:!text-sm w-full min-w-0" id="tanggal_kembali" name="tanggal_kembali" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-3 sm:mb-4">
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Jumlah</label>
                            <div class="flex items-center gap-0 rounded-xl border border-transparent bg-[#f5f5f7] overflow-hidden">
                                <button type="button" class="btn-minus w-10 h-10 flex items-center justify-center text-[#6e6e73] hover:bg-[#e5e5e7] transition-colors" onclick="var i=document.getElementById('jumlah'); if(parseInt(i.value)>1){i.value=parseInt(i.value)-1; calculateRental();}">-</button>
                                <input type="number" class="flex-1 text-center bg-transparent text-sm font-medium outline-none" id="jumlah" name="jumlah" value="1" min="1" max="{{ $product->stok_tersedia }}">
                                <button type="button" class="btn-plus w-10 h-10 flex items-center justify-center text-[#6e6e73] hover:bg-[#e5e5e7] transition-colors" onclick="var i=document.getElementById('jumlah'); if(parseInt(i.value)<{{ $product->stok_tersedia }}){i.value=parseInt(i.value)+1; calculateRental();}">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Lama Sewa</label>
                            <div class="input-apple bg-[#f5f5f7] cursor-default">
                                <span id="lama_sewa_display" class="font-medium text-[#1d1d1f]">1 hari</span>
                                <input type="hidden" id="lama_sewa" name="lama_sewa" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-2.5 sm:p-3 rounded-xl bg-[#f5f5f7] mb-3 sm:mb-4">
                        <span class="text-xs sm:text-sm text-[#6e6e73]">Estimasi Total:</span>
                        <strong class="text-sm sm:text-base font-bold text-[#1d1d1f]" id="estimated_total">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</strong>
                    </div>

                    <button type="submit" class="btn-dark-apple w-full !py-2.5 sm:!py-3 text-sm sm:text-base">Sewa Sekarang</button>
                </form>
                @else
                <div class="p-4 rounded-xl bg-[#eff6ff] border border-[#bfdbfe] mb-4">
                    <p class="text-sm text-[#1d4ed8] mb-3">Silakan login terlebih dahulu untuk menyewa produk ini. Anda tetap bisa lihat-lihat detail, harga, dan ulasan.</p>
                    <a href="{{ route('login') }}" class="btn-dark-apple w-full !py-3 text-center block">Masuk untuk Sewa</a>
                    <a href="{{ route('register') }}" class="btn-outline-apple w-full !py-3 mt-2 text-center block">Belum punya akun? Daftar</a>
                </div>
                @endauth

                @php $waPhone = '6281234567890'; $waText = rawurlencode('Halo, saya tertarik dengan produk ' . $product->nama_produk . ' - ' . request()->url()); @endphp
                <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn-outline-apple w-full !py-2.5 sm:!py-3 mt-2 sm:mt-3 text-sm sm:text-base text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Tanya via WhatsApp
                </a>
            </div>
            @else
            <div class="p-4 rounded-xl bg-[#fff8ee] border border-[#ffe4b5] mb-6">
                <p class="text-sm text-[#c93400] flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Produk ini sedang tidak tersedia untuk disewa.
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Reviews Section --}}
    @php $approvedReviews = optional($product->ulasan)->where('status', 'approved') ?? collect(); @endphp

    @if($approvedReviews->count() > 0)
    <div class="mt-6 sm:mt-8 lg:mt-12">
        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-[#1d1d1f] mb-4 sm:mb-6">Ulasan Pelanggan</h2>

        {{-- Rating Summary --}}
        <div class="card-apple-static p-4 sm:p-3 sm:p-4 lg:p-5 mb-6 sm:mb-4 sm:mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1d1d1f]">{{ number_format($product->rating, 1) }}</p>
                    <div class="flex items-center justify-center gap-0.5 mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $product->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm text-[#86868b] mt-1">{{ $product->jumlah_ulasan }} ulasan</p>
                </div>
                <div class="md:col-span-2 space-y-2">
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $count = $approvedReviews->where('rating', $i)->count();
                            $percentage = $product->jumlah_ulasan > 0 ? ($count / $product->jumlah_ulasan) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-[#6e6e73] w-4 text-right">{{ $i }}</span>
                            <div class="flex-1 progress-apple">
                                <div class="progress-apple-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-xs text-[#86868b] w-6">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Reviews List --}}
        <div class="space-y-3 sm:space-y-4">
            @foreach($approvedReviews->take(5) as $ulasan)
                <div class="card-apple-static p-4 sm:p-3 sm:p-4 lg:p-5">
                    <div class="flex items-start gap-3 mb-3">
                        @if(optional($ulasan->user)->foto_profil)
                            <img src="{{ asset('storage/' . $ulasan->user->foto_profil) }}" alt="{{ optional($ulasan->user)->nama }}" class="w-9 h-9 rounded-full object-cover border border-[#e5e5e7] shrink-0">
                        @else
                            <div class="w-9 h-9 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-xs font-semibold shrink-0">
                                {{ strtoupper(substr(optional($ulasan->user)->nama ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-[#1d1d1f]">{{ optional($ulasan->user)->nama ?? 'Anonim' }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-3 w-3 {{ $i <= $ulasan->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-[10px] text-[#86868b]">{{ $ulasan->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @if($ulasan->judul)
                        <p class="text-sm font-semibold text-[#1d1d1f] mb-1.5">{{ $ulasan->judul }}</p>
                    @endif
                    <p class="text-sm text-[#6e6e73] leading-relaxed mb-3">{{ $ulasan->komentar }}</p>
                    @if($ulasan->foto_ulasan)
                        <div class="flex gap-2 mb-3">
                            @foreach(json_decode($ulasan->foto_ulasan, true) as $foto)
                                <img src="{{ asset('storage/' . $foto) }}" alt="Review photo" class="rounded-xl w-16 h-16 sm:w-20 sm:h-20 object-cover border border-[#f0f0f2]">
                            @endforeach
                        </div>
                    @endif
                    @if($ulasan->balasan)
                        <div class="p-3 bg-[#f5f5f7] rounded-xl text-sm flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <div class="min-w-0">
                                <p class="text-[10px] font-medium text-[#86868b] uppercase tracking-wider mb-0.5">Balasan dari Admin</p>
                                <p class="text-sm text-[#6e6e73]">{{ $ulasan->balasan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-6 sm:mt-8 lg:mt-12">
        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-tight text-[#1d1d1f] mb-4 sm:mb-6">Produk Serupa</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 lg:gap-5">
            @foreach($relatedProducts as $relatedProduct)
                @include('customer.products._product_card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Save (heart) toggle
        const saveBtn = document.getElementById('saveProductBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                const icon = document.getElementById('heartIcon');
                const isSaved = icon.getAttribute('fill') !== 'none';
                if (isSaved) {
                    icon.setAttribute('fill', 'none');
                    icon.setAttribute('stroke', 'currentColor');
                } else {
                    icon.setAttribute('fill', '#d70015');
                    icon.setAttribute('stroke', 'none');
                    icon.classList.add('text-[#d70015]');
                }
            });
        }

        // Thumbnail image click
        document.querySelectorAll('.thumbnail-image').forEach(img => {
            img.addEventListener('click', function() {
                const mainImage = document.getElementById('main-image');
                if (mainImage.tagName === 'IMG') {
                    mainImage.src = this.dataset.image;
                } else {
                    const newImg = document.createElement('img');
                    newImg.id = 'main-image';
                    newImg.src = this.dataset.image;
                    newImg.alt = {!! json_encode($product->nama_produk) !!};
                    newImg.className = 'w-full h-full object-cover';
                    mainImage.parentNode.replaceChild(newImg, mainImage);
                }
                document.querySelectorAll('.thumbnail-image').forEach(i => { i.classList.remove('border-[#1d1d1f]'); i.classList.add('border-transparent'); });
                this.classList.add('border-[#1d1d1f]');
                this.classList.remove('border-transparent');
            });
        });

        // Calculate rental days and estimated total (hanya jika form sewa ada = user login)
        function calculateRental() {
            const startEl = document.getElementById('tanggal_sewa');
            const endEl = document.getElementById('tanggal_kembali');
            const qtyEl = document.getElementById('jumlah');
            const formEl = document.getElementById('rentalForm');
            if (!startEl || !endEl || !qtyEl || !formEl) return;
            const start = new Date(startEl.value);
            const end = new Date(endEl.value);
            const quantity = parseInt(qtyEl.value);
            const pricePerDay = parseFloat(formEl.dataset.price);

            if (start && end && end > start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                document.getElementById('lama_sewa').value = diffDays;
                document.getElementById('lama_sewa_display').textContent = diffDays + ' hari';
                const total = pricePerDay * diffDays * quantity;
                document.getElementById('estimated_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }

        document.querySelectorAll('.rental-date, #jumlah').forEach(el => el.addEventListener('change', calculateRental));

        const sewaInput = document.getElementById('tanggal_sewa');
        if (sewaInput) {
        sewaInput.addEventListener('change', function() {
            const returnDate = document.getElementById('tanggal_kembali');
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            returnDate.min = nextDay.toISOString().split('T')[0];
            if (!returnDate.value || new Date(returnDate.value) <= new Date(this.value)) {
                returnDate.value = nextDay.toISOString().split('T')[0];
            }
            calculateRental();
        });

        // Initialize
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0];
        const tomorrow = new Date(today); tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        sewaInput.min = todayStr;
        document.getElementById('tanggal_kembali').min = tomorrowStr;
        document.getElementById('tanggal_kembali').value = tomorrowStr;
        calculateRental();
        }

        // Sewa Sekarang via AJAX (hanya untuk user login)
        const rentalForm = document.getElementById('rentalForm');
        if (rentalForm) {
        rentalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            formData.append('ajax', '1');
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                body: formData
            })
            .then(r => {
                if (r.status === 401) { window.location.href = '{{ route("login") }}'; return null; }
                return r.json();
            })
            .then(data => {
                if (!data) return;
                if (data.success) window.location.href = '{{ route("customer.checkout.index") }}';
                else alert(data.message || 'Terjadi kesalahan');
            })
            .catch(() => alert('Terjadi kesalahan. Silakan coba lagi.'));
        });
        }
    });
</script>
@endpush
@endsection
