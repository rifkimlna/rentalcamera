@extends('layouts.customer')

@section('title', 'Sewa Kamera - Sewa Kamera Pro')

@section('content')
<div class="p-4" id="productsPage"
     data-availability-url="{{ route('customer.products.check-availability') }}"
     data-cart-summary-url="{{ route('customer.cart.summary') }}"
     data-csrf="{{ csrf_token() }}">
    <!-- Page header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Sewa Kamera & Perlengkapan</h1>
            <p class="text-base-content/60 mb-0">Temukan peralatan fotografi terbaik untuk kebutuhan Anda</p>
        </div>
        <div>
            <a href="{{ route('customer.cart.index') }}" class="btn btn-primary relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                Keranjang
                <span class="badge badge-sm badge-accent ms-1 cart-badge">0</span>
            </a>
        </div>
    </div>

    <!-- Filter section -->
    <div class="card bg-base-100 shadow-md mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.products.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label for="search" class="label">
                        <span class="label-text">Cari Produk</span>
                    </label>
                    <input type="text" class="input input-bordered w-full" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama produk atau brand...">
                </div>
                <div>
                    <label for="kategori" class="label">
                        <span class="label-text">Kategori</span>
                    </label>
                    <select class="select select-bordered w-full" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('kategori') == $category->slug ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="brand" class="label">
                        <span class="label-text">Brand</span>
                    </label>
                    <select class="select select-bordered w-full" id="brand" name="brand">
                        <option value="">Semua Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                                {{ $brand->nama_brand }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="sort" class="label">
                        <span class="label-text">Urutkan</span>
                    </label>
                    <select class="select select-bordered w-full" id="sort" name="sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text">&nbsp;</span></label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="checkbox" type="checkbox" id="featured" name="featured" value="1" 
                               {{ request('featured') ? 'checked' : '' }}>
                        <span class="label-text">Featured</span>
                    </label>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-outline btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products list -->
    @forelse($products as $product)
    <div class="card bg-base-100 border border-base-300 mb-4">
        <div class="card-body p-4">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-48 h-32 flex-shrink-0 relative">
                    @if($product->gambar_utama)
                        <img src="{{ asset('storage/' . $product->gambar_utama) }}"
                             class="w-full h-full object-cover rounded" alt="{{ $product->nama_produk }}">
                    @else
                        <div class="w-full h-full bg-base-200 rounded flex items-center justify-center text-base-content/40 text-sm">No Image</div>
                    @endif

                    @if($product->is_featured)
                        <span class="badge badge-info badge-sm absolute top-1 left-1">Featured</span>
                    @endif
                    @if($product->is_recommended)
                        <span class="badge badge-primary badge-sm absolute top-1 right-1">Recommended</span>
                    @endif
                    @if($product->status == 'maintenance')
                        <div class="absolute inset-0 bg-black/50 rounded flex items-center justify-center">
                            <span class="badge badge-warning">Maintenance</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-sm font-medium">{{ $product->nama_produk }}</h4>
                            <p class="text-xs text-base-content/60">{{ $product->brand->nama_brand ?? '' }}</p>
                        </div>
                        <div class="rating rating-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="rating-{{ $product->id }}" class="mask mask-star-2 bg-orange-400" disabled {{ $i <= round($product->rating) ? 'checked' : '' }} />
                            @endfor
                            <span class="text-xs ms-1">({{ $product->rating }})</span>
                        </div>
                    </div>
                    <p class="text-xs text-base-content/60 mt-1">{{ Str::limit($product->deskripsi_singkat, 120) }}</p>

                    @if($product->kategori)
                        <span class="badge badge-sm badge-outline mt-1">{{ $product->kategori->nama_kategori }}</span>
                    @endif

                    <div class="flex items-center justify-between mt-3">
                        <div>
                            <span class="text-sm font-bold text-primary">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</span>
                            <span class="text-xs text-base-content/60">/ hari</span>
                        </div>
                        @if($product->stok_tersedia > 0)
                            <span class="badge badge-sm badge-success">Stok: {{ $product->stok_tersedia }}</span>
                        @else
                            <span class="badge badge-sm badge-error">Habis</span>
                        @endif
                    </div>

                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('customer.products.show', $product->slug) }}" class="btn btn-sm">Detail</a>
                        @if($product->status == 'available' && $product->stok_tersedia > 0)
                            <button type="button" class="btn btn-success btn-sm add-to-cart" data-product-id="{{ $product->id }}" data-action="checkout">Sewa</button>
                            <button type="button" class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}" data-action="cart">Keranjang</button>
                        @else
                            <button class="btn btn-sm" disabled>Tidak Tersedia</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body text-center py-8">
            <p class="text-base-content/60">Produk tidak ditemukan</p>
            <a href="{{ route('customer.products.index') }}" class="btn btn-sm mt-2">Reset Pencarian</a>
        </div>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="flex justify-center mt-4">
            {{ $products->links() }}
        </div>
    @endif

    <!-- Categories section -->
    <div class="mt-8">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h5 class="card-title mb-4">Kategori Produk</h5>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                        <a href="{{ route('customer.products.index', ['kategori' => $category->slug]) }}" 
                           class="no-underline">
                            <div class="card bg-base-200 hover:shadow-md transition-shadow">
                                <div class="card-body text-center items-center py-6">
                                    <div class="mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h6 class="mb-0">{{ $category->nama_kategori }}</h6>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add to Cart Modal -->
<dialog id="addToCartModal" class="modal">
    <div class="modal-box max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Tambah ke Keranjang</h3>
        <form id="addToCartForm" method="POST" action="{{ route('customer.cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" id="modal_product_id">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <div id="product_image" class="bg-base-200 rounded mb-3 h-48"></div>
                    <div id="product_info"></div>
                </div>

                <div class="md:col-span-3">
                    <div class="mb-3">
                        <label class="label">
                            <span class="label-text">Jumlah</span>
                        </label>
                        <input type="number" class="input input-bordered w-full" name="jumlah" id="quantity" min="1" value="1" required>
                        <div class="label">
                            <span class="label-text-alt text-base-content/60" id="stock_info"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="label">
                                <span class="label-text">Tanggal Mulai Sewa</span>
                            </label>
                            <input type="date" class="input input-bordered w-full datepicker" name="tanggal_sewa" id="start_date" required>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text">Tanggal Kembali</span>
                            </label>
                            <input type="date" class="input input-bordered w-full datepicker" name="tanggal_kembali" id="end_date" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="label">
                            <span class="label-text">Lama Sewa</span>
                        </label>
                        <input type="text" class="input input-bordered w-full" id="rental_days" readonly value="1 hari">
                    </div>

                    <div class="card bg-base-200">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Ringkasan Biaya</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Harga per Hari</td>
                                        <td class="text-right" id="price_per_day">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td>Subtotal Sewa</td>
                                        <td class="text-right" id="subtotal">Rp 0</td>
                                    </tr>
                                    <tr>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="addToCartModal.close()">Batal</button>
                <button type="submit" class="btn btn-primary" id="modalSubmitBtn">
                    Tambah ke Keranjang
                </button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate rental days
        function calculateRentalDays() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                $('#rental_days').val(diffDays + ' hari');
                updatePrice();
            }
        }

        $('#start_date, #end_date').on('change', calculateRentalDays);

        // Update price
        function updatePrice() {
            const quantity = parseInt($('#quantity').val()) || 1;
            const pricePerDay = parseFloat($('#price_per_day').data('price')) || 0;
            const days = parseInt($('#rental_days').val()) || 1;

            const subtotal = pricePerDay * days * quantity;

            $('#subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            $('#total').text('Rp ' + subtotal.toLocaleString('id-ID'));
        }

        $('#quantity').on('change', updatePrice);

        // Add to cart button click
        $('.add-to-cart').click(function() {
            const productId = $(this).data('product-id');
            const action = $(this).data('action') || 'cart';
            $('#addToCartForm').data('action', action);
            if (action === 'checkout') {
                $('#modalTitle').text('Lanjut ke Checkout');
                $('#modalSubmitBtn').text('Lanjut ke Checkout');
            } else {
                $('#modalTitle').text('Tambah ke Keranjang');
                $('#modalSubmitBtn').text('Tambah ke Keranjang');
            }

            $.ajax({
                url: document.getElementById('productsPage').dataset.availabilityUrl,
                method: 'POST',
                data: {
                    _token: document.getElementById('productsPage').dataset.csrf,
                    product_id: productId,
                    tanggal_sewa: $('#start_date').val() || new Date().toISOString().split('T')[0],
                    tanggal_kembali: $('#end_date').val() || new Date(Date.now() + 86400000).toISOString().split('T')[0],
                    quantity: 1
                },
                success: function(response) {
                    if (response.available) {
                        $('#modal_product_id').val(productId);
                        if (response.start_date) {
                            $('#start_date').val(response.start_date);
                        }
                        if (response.end_date) {
                            $('#end_date').val(response.end_date);
                        }
                        $('#quantity').val(1);
                        $('#price_per_day').text('Rp ' + response.price_per_day.toLocaleString('id-ID'));
                        $('#price_per_day').data('price', response.price_per_day);
                        $('#stock_info').text('Stok tersedia: ' + (response.available_stock || '?'));

                        updatePrice();
                        addToCartModal.showModal();
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });

        // Handle form submission
        $('#addToCartForm').submit(function(e) {
            e.preventDefault();
            const action = $(this).data('action') || 'cart';

            if (action === 'checkout') {
                $.ajax({
                    url: '{{ route("customer.cart.direct-rent") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        addToCartModal.close();
                        window.location.href = '{{ route("customer.checkout.index") }}';
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Terjadi kesalahan');
                    }
                });
            } else {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        addToCartModal.close();
                        toastr.success(response.message || 'Produk berhasil ditambahkan ke keranjang');
                        updateCartBadge();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Terjadi kesalahan');
                    }
                });
            }
        });

        // Update cart badge
        function updateCartBadge() {
            $.ajax({
                url: document.getElementById('productsPage').dataset.cartSummaryUrl,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('.cart-badge').text(response.data.items_count);
                    }
                }
            });
        }

        // Initial cart badge update
        updateCartBadge();
    });
</script>
@endpush
