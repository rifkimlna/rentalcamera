<div class="card bg-base-100 shadow-md">
    @if($product->is_featured)
        <div class="absolute top-2 left-2 z-10">
            <span class="badge badge-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Featured
            </span>
        </div>
    @endif

    <figure>
        @if($product->gambar_utama)
            <img src="{{ asset('storage/' . $product->gambar_utama) }}" 
                 class="w-full h-48 object-cover" alt="{{ $product->nama_produk }}">
        @else
            <div class="bg-base-200 w-full h-48 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        @endif
    </figure>

    <div class="card-body">
        <div class="flex items-start justify-between mb-2">
            <h5 class="card-title text-sm">{{ Str::limit($product->nama_produk, 30) }}</h5>
            @if($product->status != 'available')
                <span class="badge badge-error badge-sm">Tidak Tersedia</span>
            @endif
        </div>

        <div class="flex gap-1 mb-2">
            <span class="badge badge-ghost badge-sm">{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</span>
            @if($product->brand)
                <span class="badge badge-ghost badge-sm">{{ $product->brand->nama_brand }}</span>
            @endif
        </div>

        <div class="rating rating-xs mb-2">
            @for($i = 1; $i <= 5; $i++)
                <input type="radio" name="card-rating-{{ $product->id }}" class="mask mask-star-2 bg-orange-400" disabled {{ $i <= round($product->rating) ? 'checked' : '' }} />
            @endfor
            <small class="text-base-content/60 ms-1">({{ $product->rating }})</small>
            <small class="text-base-content/60 ms-2">{{ $product->jumlah_ulasan }} ulasan</small>
        </div>

        <p class="text-base-content/60 text-xs mb-3">
            {{ Str::limit($product->deskripsi_singkat, 80) }}
        </p>

        <div class="flex items-center justify-between">
            <div class="price">
                <strong>Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</strong>
                <small class="text-base-content/60 block">per hari</small>
            </div>

            @if($product->status == 'available' && $product->stok_tersedia > 0)
                <a href="{{ route('customer.products.show', $product->slug) }}" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Detail
                </a>
            @else
                <button class="btn btn-outline btn-secondary btn-sm" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Habis
                </button>
            @endif
        </div>

        @if($product->stok_tersedia > 0)
            <div class="mt-2">
                <small class="text-success flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Stok tersedia: {{ $product->stok_tersedia }}
                </small>
            </div>
        @endif
    </div>
</div>
