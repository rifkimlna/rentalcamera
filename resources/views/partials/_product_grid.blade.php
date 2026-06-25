<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($products as $product)
    <div class="card bg-base-100 shadow-md product-card">
        @if($product->is_featured)
            <div class="badge-featured">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Featured
            </div>
        @endif
        
        @if($product->gambar_utama)
            <figure>
                <img src="{{ asset('storage/' . $product->gambar_utama) }}" 
                     alt="{{ $product->nama_produk }}"
                     class="w-full h-48 object-cover">
            </figure>
        @else
            <div class="bg-base-200 flex items-center justify-center h-48">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/60" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
            </div>
        @endif
        
        <div class="card-body">
            <div class="flex justify-between items-start mb-2">
                <h5 class="card-title mb-0">{{ Str::limit($product->nama_produk, 30) }}</h5>
                @if($product->status != 'available')
                    <span class="badge badge-error">Tidak Tersedia</span>
                @endif
            </div>
            
            <div class="mb-2">
                <span class="category-badge">{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                @if($product->brand)
                    <span class="badge badge-secondary ml-1">{{ $product->brand->nama_brand }}</span>
                @endif
            </div>
            
            <div class="flex items-center gap-0 mb-2">
                <div class="rating rating-sm gap-0">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->rating))
                            <svg class="w-4 h-4 text-warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @elseif($i - 0.5 <= $product->rating)
                            <div class="relative w-4 h-4">
                                <svg class="w-4 h-4 text-warning absolute inset-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-4 h-4 text-warning absolute inset-0" style="clip-path: inset(0 50% 0 0)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        @else
                            <svg class="w-4 h-4 text-warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-base-content/60 text-sm ml-1">({{ $product->rating }})</span>
                <span class="text-base-content/60 text-sm ml-2">{{ $product->jumlah_ulasan }} ulasan</span>
            </div>
            
            <p class="text-base-content/60 text-sm mb-3">
                {{ Str::limit($product->deskripsi_singkat, 80) }}
            </p>
            
            <div class="flex justify-between items-center">
                <div class="price">
                    <strong>Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</strong>
                    <small class="text-base-content/60 block">per hari</small>
                </div>
                
                @if($product->status == 'available' && $product->stok_tersedia > 0)
                    <a href="{{ route('customer.products.show', $product) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Detail
                    </a>
                @else
                    <button class="btn btn-outline btn-sm" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Habis
                    </button>
                @endif
            </div>
            
            @if($product->stok_tersedia > 0)
                <div class="mt-2">
                    <small class="text-success flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Stok tersedia: {{ $product->stok_tersedia }}
                    </small>
                </div>
            @endif
        </div>
        
        <div class="card-footer bg-base-100 border-t-0 pt-0">
            @if($product->status == 'available' && $product->stok_tersedia > 0)
                <form action="{{ route('customer.cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $product->id }}">
                    <input type="hidden" name="jumlah" value="1">
                    <input type="hidden" name="tanggal_sewa" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="tanggal_kembali" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    <button type="submit" class="btn btn-success w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Tambah ke Keranjang
                    </button>
                </form>
            @else
                <button class="btn btn-outline w-full" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Tidak Tersedia
                </button>
            @endif
        </div>
    </div>
    @endforeach
</div>
