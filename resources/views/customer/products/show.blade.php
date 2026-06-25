@extends('layouts.app')

@section('title', $product->nama_produk)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm breadcrumbs mb-6">
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <li><a href="{{ route('customer.products.index') }}">Kamera</a></li>
            @if($product->kategori)
                <li>
                    <a href="{{ route('customer.products.index', ['kategori' => $product->kategori->slug]) }}">
                        {{ $product->kategori->nama_kategori }}
                    </a>
                </li>
            @endif
            <li>{{ $product->nama_produk }}</li>
        </ul>
    </nav>

    <!-- Product Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Product Images -->
        <div>
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <!-- Main Image -->
                    <div class="text-center mb-3">
                        @if($product->gambar_utama)
                            <img id="main-image" src="{{ asset('storage/' . $product->gambar_utama) }}" 
                                 alt="{{ $product->nama_produk }}" 
                                 class="img-fluid rounded" style="max-height: 400px; object-fit: contain;">
                        @else
                            <div id="main-image" class="bg-base-200 rounded flex items-center justify-center" 
                                 style="height: 400px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @php
                        $allImages = [$product->gambar_utama];
                        if ($product->gambar_tambahan) {
                            $additionalImages = json_decode($product->gambar_tambahan, true);
                            $allImages = array_merge($allImages, $additionalImages);
                        }
                        $allImages = array_filter($allImages);
                    @endphp

                    @if(count($allImages) > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($allImages as $index => $image)
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="Gambar {{ $index + 1 }}" 
                                     class="rounded border-2 cursor-pointer h-20 object-cover thumbnail-image {{ $index == 0 ? 'border-primary' : 'border-transparent' }}" 
                                     data-image="{{ asset('storage/' . $image) }}">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Share & Save -->
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-base-content/60">Bagikan:</span>
                            <a href="#" class="link">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <a href="#" class="link">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                            <a href="#" class="link">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </a>
                            <a href="#" class="link">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </a>
                        </div>
                        <div>
                            <button class="btn btn-outline btn-secondary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">{{ $product->nama_produk }}</h1>
                            <div class="flex gap-2 mb-2">
                                @if($product->brand)
                                    <span class="badge badge-ghost">{{ $product->brand->nama_brand }}</span>
                                @endif
                                @if($product->kategori)
                                    <span class="badge badge-primary">{{ $product->kategori->nama_kategori }}</span>
                                @endif
                            </div>
                        </div>
                        @if($product->is_featured)
                            <span class="badge badge-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Featured
                            </span>
                        @endif
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="rating rating-sm">
                            <input type="radio" name="show-rating" class="mask mask-star-2 bg-orange-400" disabled checked />
                            <input type="radio" name="show-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 2 ? 'checked' : '' }} />
                            <input type="radio" name="show-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 3 ? 'checked' : '' }} />
                            <input type="radio" name="show-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 4 ? 'checked' : '' }} />
                            <input type="radio" name="show-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 5 ? 'checked' : '' }} />
                        </div>
                        <span class="text-base-content/60">{{ $product->rating }} ({{ $product->jumlah_ulasan }} ulasan)</span>
                        <span class="text-base-content/60">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            {{ $product->jumlah_dipesan }}x disewa
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <h2 class="text-success text-2xl mb-1">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}/hari</h2>
                        @if($product->harga_per_minggu)
                            <div class="text-base-content/60">
                                Rp {{ number_format($product->harga_per_minggu, 0, ',', '.') }}/minggu
                            </div>
                        @endif
                        @if($product->harga_per_bulan)
                            <div class="text-base-content/60">
                                Rp {{ number_format($product->harga_per_bulan, 0, ',', '.') }}/bulan
                            </div>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="alert {{ $product->status == 'available' ? 'alert-success' : 'alert-error' }} mb-4">
                        <div class="flex items-center gap-2">
                            @if($product->status == 'available')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                            <div>
                                <strong>
                                    {{ $product->status == 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                </strong>
                                @if($product->status == 'available')
                                    <div class="text-sm">Stok: {{ $product->stok_tersedia }} unit</div>
                                @else
                                    <div class="text-sm">Produk sedang tidak tersedia untuk disewa</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="font-semibold mb-2">Deskripsi</h5>
                        <p>{{ $product->deskripsi_singkat }}</p>
                        @if($product->deskripsi_lengkap)
                            <div id="descriptionCollapse" class="hidden">
                                {!! nl2br(e($product->deskripsi_lengkap)) !!}
                            </div>
                            <button type="button" class="link" onclick="document.getElementById('descriptionCollapse').classList.toggle('hidden'); this.classList.toggle('hidden')">
                                Baca selengkapnya...
                            </button>
                        @endif
                    </div>

                    <!-- Features -->
                    @if($product->fitur)
                    <div class="mb-4">
                        <h5 class="font-semibold mb-2">Fitur Utama</h5>
                        <ul class="list-none space-y-1">
                            @foreach(explode("\n", $product->fitur) as $fitur)
                                @if(trim($fitur))
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 text-success shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $fitur }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Specs Summary -->
                    @php
                        $spesifikasi = json_decode($product->spesifikasi, true) ?? [];
                        $importantSpecs = array_slice($spesifikasi, 0, 3);
                    @endphp

                    @if(count($importantSpecs) > 0)
                    <div class="mb-4">
                        <h5 class="font-semibold mb-2">Spesifikasi</h5>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($importantSpecs as $key => $value)
                                @if($value)
                                    <div>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                        <span class="text-base-content/60">{{ $value }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if(count($spesifikasi) > 3)
                            <button type="button" class="link" onclick="document.getElementById('specsCollapse').classList.toggle('hidden'); this.classList.toggle('hidden')">
                                Lihat spesifikasi lengkap...
                            </button>
                            <div id="specsCollapse" class="hidden">
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    @foreach(array_slice($spesifikasi, 3) as $key => $value)
                                        @if($value)
                                            <div>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                <span class="text-base-content/60">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- Condition & Additional Info -->
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div>
                            <strong>Kondisi:</strong>
                            <span class="text-base-content/60">{{ $product->kondisi_label }}</span>
                        </div>
                        @if($product->tahun_pembuatan)
                        <div>
                            <strong>Tahun:</strong>
                            <span class="text-base-content/60">{{ $product->tahun_pembuatan }}</span>
                        </div>
                        @endif
                        @if($product->berat)
                        <div>
                            <strong>Berat:</strong>
                            <span class="text-base-content/60">{{ $product->berat }} gram</span>
                        </div>
                        @endif
                        @if($product->dimensi)
                        <div>
                            <strong>Dimensi:</strong>
                            <span class="text-base-content/60">{{ $product->dimensi }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Rental Form -->
                    @if($product->status == 'available' && $product->stok_tersedia > 0)
                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Sewa Sekarang</h5>
                            <form action="{{ route('customer.cart.direct-rent') }}" method="POST" id="rentalForm"
                                  data-price="{{ $product->harga_per_hari }}"
                                  data-stock="{{ $product->stok_tersedia }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label for="tanggal_sewa" class="label">
                                            <span class="label-text">Tanggal Sewa</span>
                                        </label>
                                        <input type="date" class="input input-bordered w-full rental-date" 
                                               id="tanggal_sewa" name="tanggal_sewa" required
                                               min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label for="tanggal_kembali" class="label">
                                            <span class="label-text">Tanggal Kembali</span>
                                        </label>
                                        <input type="date" class="input input-bordered w-full rental-date" 
                                               id="tanggal_kembali" name="tanggal_kembali" required
                                               min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label for="jumlah" class="label">
                                            <span class="label-text">Jumlah</span>
                                        </label>
                                        <div class="join w-full">
                                            <button class="join-item btn btn-outline btn-secondary btn-minus" type="button">-</button>
                                            <input type="number" class="join-item input input-bordered w-full text-center" 
                                                   id="jumlah" name="jumlah" value="1" min="1" 
                                                   max="{{ $product->stok_tersedia }}">
                                            <button class="join-item btn btn-outline btn-secondary btn-plus" type="button">+</button>
                                        </div>
                                        <small class="text-base-content/60">Stok tersedia: {{ $product->stok_tersedia }}</small>
                                    </div>
                                    <div>
                                        <label class="label">
                                            <span class="label-text">Lama Sewa</span>
                                        </label>
                                        <div class="input input-bordered w-full bg-base-100 flex items-center">
                                            <span id="lama_sewa_display">1 hari</span>
                                            <input type="hidden" id="lama_sewa" name="lama_sewa" value="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info mb-3">
                                    <div class="flex items-center justify-between w-full">
                                        <span>Estimasi Total:</span>
                                        <strong id="estimated_total">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</strong>
                                    </div>
                                    <small class="text-base-content/60">* Harga belum termasuk biaya pengiriman</small>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <button type="submit" class="btn btn-success btn-block">
                                        Sewa Sekarang
                                    </button>
                                    <a href="#" class="btn btn-outline btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                        </svg>
                                        Tanya via WhatsApp
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        Produk ini sedang tidak tersedia untuk disewa.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-6">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h3 class="text-xl font-bold mb-4">Ulasan Pelanggan</h3>

                @php $approvedReviews = optional($product->ulasan)->where('status', 'approved') ?? collect(); @endphp

                @if($approvedReviews->count() > 0)
                    <!-- Rating Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-warning">{{ number_format($product->rating, 1) }}</div>
                            <div class="rating rating-md mb-2">
                                <input type="radio" name="summary-rating" class="mask mask-star-2 bg-orange-400" disabled checked />
                                <input type="radio" name="summary-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 2 ? 'checked' : '' }} />
                                <input type="radio" name="summary-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 3 ? 'checked' : '' }} />
                                <input type="radio" name="summary-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 4 ? 'checked' : '' }} />
                                <input type="radio" name="summary-rating" class="mask mask-star-2 bg-orange-400" disabled {{ $product->rating >= 5 ? 'checked' : '' }} />
                            </div>
                            <small class="text-base-content/60">{{ $product->jumlah_ulasan }} ulasan</small>
                        </div>
                        <div class="md:col-span-2">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $approvedReviews->where('rating', $i)->count();
                                    $percentage = $product->jumlah_ulasan > 0 ? ($count / $product->jumlah_ulasan) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-12 text-sm text-right">{{ $i }} <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline text-warning" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.164L12 18.896l-7.334 3.865 1.4-8.164L.132 9.21l8.2-1.192L12 .587z" />
                                    </svg></div>
                                    <div class="flex-1">
                                        <progress class="progress progress-warning w-full" value="{{ $percentage }}" max="100"></progress>
                                    </div>
                                    <div class="w-8 text-sm">{{ $count }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-4">
                        @foreach($approvedReviews->take(5) as $ulasan)
                            <div class="border-b border-base-200 pb-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h6 class="font-semibold mb-1">{{ optional($ulasan->user)->nama ?? 'Anonim' }}</h6>
                                        <div class="rating rating-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" name="review-rating-{{ $ulasan->id }}" class="mask mask-star-2 bg-orange-400" disabled {{ $i <= $ulasan->rating ? 'checked' : '' }} />
                                            @endfor
                                            <span class="text-base-content/60 text-xs ms-2">{{ $ulasan->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($ulasan->judul)
                                    <h6 class="font-semibold mb-2">{{ $ulasan->judul }}</h6>
                                @endif
                                <p class="mb-2">{{ $ulasan->komentar }}</p>
                                @if($ulasan->foto_ulasan)
                                    <div class="flex gap-2 mt-2">
                                        @foreach(json_decode($ulasan->foto_ulasan, true) as $foto)
                                            <img src="{{ asset('storage/' . $foto) }}" 
                                                 alt="Review photo" 
                                                 class="rounded w-20 h-20 object-cover">
                                        @endforeach
                                    </div>
                                @endif
                                @if($ulasan->balasan)
                                    <div class="mt-3 p-3 bg-base-200 rounded text-sm">
                                        <strong>Balasan dari Admin:</strong> {{ $ulasan->balasan }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($approvedReviews->count() > 5)
                        <div class="text-center mt-4">
                            <button class="btn btn-outline btn-primary" id="loadMoreReviews">
                                Muat Lebih Banyak Ulasan
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h5 class="mt-3">Belum ada ulasan</h5>
                        <p class="text-base-content/60">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Produk Serupa</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($relatedProducts as $relatedProduct)
                @include('customer.products._product_card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
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
                newImg.className = 'img-fluid rounded';
                newImg.style.maxHeight = '400px';
                newImg.style.objectFit = 'contain';

                mainImage.parentNode.replaceChild(newImg, mainImage);
            }

            document.querySelectorAll('.thumbnail-image').forEach(i => i.classList.remove('border-primary'));
            this.classList.add('border-primary');
        });
    });

    // Calculate rental days and estimated total
    function calculateRental() {
        const start = new Date(document.getElementById('tanggal_sewa').value);
        const end = new Date(document.getElementById('tanggal_kembali').value);
        const quantity = parseInt(document.getElementById('jumlah').value);
        const pricePerDay = parseFloat(document.getElementById('rentalForm').dataset.price);

        if (start && end && start <= end) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            document.getElementById('lama_sewa').value = diffDays;
            document.getElementById('lama_sewa_display').textContent = diffDays + ' hari';

            const total = pricePerDay * diffDays * quantity;
            document.getElementById('estimated_total').textContent = 
                'Rp ' + total.toLocaleString('id-ID');
        }
    }

    // Event listeners for rental calculation
    document.querySelectorAll('.rental-date, #jumlah').forEach(element => {
        element.addEventListener('change', calculateRental);
    });

    document.querySelector('.btn-minus').addEventListener('click', function() {
        const input = document.getElementById('jumlah');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            calculateRental();
        }
    });

    document.querySelector('.btn-plus').addEventListener('click', function() {
        const input = document.getElementById('jumlah');
        const maxStock = parseFloat(document.getElementById('rentalForm').dataset.stock);
        if (parseInt(input.value) < maxStock) {
            input.value = parseInt(input.value) + 1;
            calculateRental();
        }
    });

    // Set minimum return date
    document.getElementById('tanggal_sewa').addEventListener('change', function() {
        const returnDate = document.getElementById('tanggal_kembali');
        returnDate.min = this.value;
        if (returnDate.value && new Date(returnDate.value) < new Date(this.value)) {
            returnDate.value = this.value;
        }
        calculateRental();
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_sewa').min = today;
        document.getElementById('tanggal_kembali').min = today;
        calculateRental();
    });

    // Sewa Sekarang via AJAX (bypass cart)
    document.getElementById('rentalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        formData.append('ajax', '1');
        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("customer.checkout.index") }}';
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    });
</script>
@endpush
@endsection
