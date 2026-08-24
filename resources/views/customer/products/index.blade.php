@extends('layouts.customer')

@section('title', 'Sewa Kamera - Stekpro Multimedia & Broadcast')
@section('page-title', 'Equipment')

@section('content')
<div id="productsPage"
     data-availability-url="{{ route('customer.products.check-availability') }}"
     data-cart-summary-url="{{ route('customer.cart.summary') }}"
     data-csrf="{{ csrf_token() }}">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-4 sm:mb-6 gap-2">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-[#1d1d1f] tracking-tight">Equipment</h1>
            <p class="text-xs sm:text-sm text-[#6e6e73] mt-0.5 hidden sm:block">Temukan peralatan fotografi terbaik untuk kebutuhan Anda</p>
        </div>
        <a href="{{ route('customer.cart.index') }}" class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-full bg-[#f5f5f7] hover:bg-[#e5e5e7] transition-colors shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-4.5 sm:w-4.5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="text-xs sm:text-sm font-medium text-[#1d1d1f] hidden sm:inline">Keranjang</span>
            <span class="cart-badge text-[10px] sm:text-xs font-semibold bg-[#1d1d1f] text-white w-4 h-4 sm:w-5 sm:h-5 rounded-full flex items-center justify-center">0</span>
        </a>
    </div>

    {{-- Filter Section --}}
    <div class="card-apple-static p-3 sm:p-4 lg:p-5 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('customer.products.index') }}">
            {{-- Desktop --}}
            <div class="hidden lg:grid lg:grid-cols-6 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Cari Produk</label>
                    <input type="text" class="input-apple" name="search" value="{{ request('search') }}" placeholder="Cari produk...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Kategori</label>
                    <select class="select-apple" name="kategori">
                        <option value="">Semua</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('kategori') == $category->slug ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Brand</label>
                    <select class="select-apple" name="brand">
                        <option value="">Semua</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>{{ $brand->nama_brand }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Urutkan</label>
                    <select class="select-apple" name="sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-dark-apple !px-4 !py-2.5 flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                        Cari
                    </button>
                    <a href="{{ route('customer.products.index') }}" class="btn-outline-apple !px-3 !py-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </div>

            {{-- Mobile --}}
            <div class="lg:hidden space-y-3">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#86868b]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                        </span>
                        <input type="text" class="input-apple !pl-9" name="search" value="{{ request('search') }}" placeholder="Cari produk...">
                    </div>
                    <button type="submit" class="btn-dark-apple !px-5 !py-3">Cari</button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <select class="select-apple !text-xs !py-2" name="kategori">
                        <option value="">Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('kategori') == $category->slug ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <select class="select-apple !text-xs !py-2" name="brand">
                        <option value="">Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>{{ $brand->nama_brand }}</option>
                        @endforeach
                    </select>
                    <select class="select-apple !text-xs !py-2" name="sort">
                        <option value="newest">Terbaru</option>
                        <option value="price_low">Harga Rendah</option>
                        <option value="price_high">Harga Tinggi</option>
                        <option value="popular">Populer</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    {{-- Products Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
    @forelse($products as $product)
        @include('customer.products._product_card', ['product' => $product])
    @empty
        <div class="col-span-full card-apple-static p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#d1d1d6] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
            <p class="text-[#6e6e73] mb-3">Produk tidak ditemukan</p>
            <a href="{{ route('customer.products.index') }}" class="btn-outline-apple !px-5 !py-2 !text-sm">Reset Pencarian</a>
        </div>
    @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="flex justify-center mt-8">
            {{ $products->links() }}
        </div>
    @endif

    {{-- Categories Section --}}
    <div class="mt-12 card-apple-static p-6 lg:p-8">
        <h3 class="text-lg font-semibold text-[#1d1d1f] mb-5">Kategori Produk</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('customer.products.index', ['kategori' => $category->slug]) }}" class="group flex flex-col items-center p-4 rounded-2xl hover:bg-[#f5f5f7] transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-[#f5f5f7] flex items-center justify-center mb-3 group-hover:bg-[#e5e5e7] transition-colors">
                        @php
                            $icons = [
                                'kamera-dslr' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'kamera-mirrorless' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                                'lensa-kamera' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm-7 4a7 7 0 1114 0 7 7 0 01-14 0z"/><circle cx="12" cy="12" r="3"/></svg>',
                                'lighting-equipment' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
                                'audio-equipment' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m-4 0h8m-8-8a4 4 0 014-4m0 0a4 4 0 014 4"/></svg>',
                                'tripod-stabilizer' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'drone' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'aksesoris' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
                            ];
                        @endphp
                        {!! $icons[$category->slug] ?? $icons['kamera-dslr'] !!}
                    </div>
                    <span class="text-sm font-medium text-[#1d1d1f] text-center">{{ $category->nama_kategori }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Add to Cart Modal --}}
<dialog id="addToCartModal" class="backdrop:bg-black/40">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-[#1d1d1f]" id="modalTitle">Sewa Equipment</h3>
                <p class="text-sm text-[#86868b] mt-0.5" id="modalSubtitle">Pilih tanggal dan jumlah</p>
            </div>
            <button onclick="addToCartModal.close()" class="w-8 h-8 rounded-full bg-[#f5f5f7] flex items-center justify-center hover:bg-[#e5e5e7] transition-colors">
                <svg class="w-4 h-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="addToCartForm" method="POST" action="{{ route('customer.cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" id="modal_product_id">

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Mulai</label>
                    <input type="date" class="input-apple datepicker" name="tanggal_sewa" id="start_date" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Jam Mulai</label>
                    <input type="time" class="input-apple" name="jam_mulai" id="jam_mulai" value="08:00" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Kembali</label>
                    <input type="date" class="input-apple datepicker" name="tanggal_kembali" id="end_date" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Jumlah</label>
                    <input type="number" class="input-apple" name="jumlah" id="quantity" min="1" value="1" required>
                    <p class="text-[10px] text-[#86868b] mt-1" id="stock_info"></p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-[#f5f5f7] mb-4">
                <div class="flex-1 text-sm">
                    <span class="text-[#86868b]">Lama Sewa:</span>
                    <strong class="text-[#1d1d1f]" id="rental_days">1 hari</strong>
                </div>
                <div class="text-right text-sm">
                    <span class="text-[#86868b]">Rp</span>
                    <strong class="text-[#1d1d1f]" id="price_per_day">0</strong>
                    <span class="text-[#86868b]">/hari</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-3.5 rounded-xl border border-[#e5e5e7] mb-5">
                <span class="text-sm font-medium text-[#1d1d1f]">Subtotal Sewa</span>
                <strong class="text-base font-bold text-[#1d1d1f]" id="subtotal">Rp 0</strong>
            </div>

            <div class="flex gap-3">
                <button type="button" class="btn-outline-apple flex-1 !py-3" onclick="addToCartModal.close()">Batal</button>
                <button type="submit" class="btn-dark-apple flex-1 !py-3" id="modalSubmitBtn">Lanjutkan ke Checkout</button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calculateRentalDays() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                document.getElementById('rental_days').textContent = diffDays + ' hari';
                updatePrice();
            }
        }

        function updatePrice() {
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const pricePerDay = parseFloat(document.getElementById('price_per_day').dataset.price) || 0;
            const daysText = document.getElementById('rental_days').textContent;
            const days = parseInt(daysText) || 1;
            const subtotal = pricePerDay * days * quantity;
            document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }

        document.getElementById('start_date').addEventListener('change', calculateRentalDays);
        document.getElementById('end_date').addEventListener('change', calculateRentalDays);
        document.getElementById('quantity').addEventListener('change', updatePrice);

        // Add to cart button click
        document.querySelectorAll('.add-to-cart').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const action = this.dataset.action || 'cart';
                document.getElementById('addToCartForm').dataset.action = action;
                if (action === 'checkout') {
                    document.getElementById('modalTitle').textContent = 'Lanjut ke Checkout';
                    document.getElementById('modalSubmitBtn').textContent = 'Lanjut ke Checkout';
                } else {
                    document.getElementById('modalTitle').textContent = 'Tambah ke Keranjang';
                    document.getElementById('modalSubmitBtn').textContent = 'Tambah ke Keranjang';
                }

                fetch(document.getElementById('productsPage').dataset.availabilityUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.getElementById('productsPage').dataset.csrf
                    },
                    body: new URLSearchParams({
                        product_id: productId,
                        tanggal_sewa: document.getElementById('start_date').value || new Date().toISOString().split('T')[0],
                        tanggal_kembali: document.getElementById('end_date').value || new Date(Date.now() + 86400000).toISOString().split('T')[0],
                        quantity: 1
                    })
                })
                .then(r => r.json())
                .then(function(response) {
                    if (response.available) {
                        document.getElementById('modal_product_id').value = productId;
                        if (response.start_date) document.getElementById('start_date').value = response.start_date;
                        if (response.end_date) document.getElementById('end_date').value = response.end_date;
                        document.getElementById('quantity').value = 1;
                        document.getElementById('price_per_day').textContent = response.harga_formatted || 'Rp ' + response.price_per_day.toLocaleString('id-ID');
                        document.getElementById('price_per_day').dataset.price = response.price_per_day;
                        document.getElementById('stock_info').textContent = 'Stok tersedia: ' + (response.available_stock || '?');
                        updatePrice();
                        addToCartModal.showModal();
                    } else {
                        alert(response.message);
                    }
                });
            });
        });

        // Handle form submission
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const action = this.dataset.action || 'cart';
            const formData = new FormData(this);

            if (action === 'checkout') {
                fetch('{{ route("customer.cart.direct-rent") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                    body: formData
                })
                .then(r => r.json())
                .then(function(data) {
                    addToCartModal.close();
                    if (data.success) window.location.href = '{{ route("customer.checkout.index") }}';
                    else alert(data.message || 'Terjadi kesalahan');
                });
            } else {
                fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                    body: formData
                })
                .then(r => r.json())
                .then(function(data) {
                    addToCartModal.close();
                    if (data.message) alert(data.message);
                    updateCartBadge();
                });
            }
        });

        function updateCartBadge() {
            fetch(document.getElementById('productsPage').dataset.cartSummaryUrl)
            .then(r => r.json())
            .then(function(response) {
                if (response.success) document.querySelectorAll('.cart-badge').forEach(function(b) { b.textContent = response.data.items_count; });
            });
        }

        updateCartBadge();
    });
</script>
@endpush
