@extends('layouts.customer')

@section('title', 'Sewa Kamera - Stekpro Multimedia & Broadcast')
@section('page-title', 'Equipment')

@section('content')
<div id="productsPage"
     data-availability-url="{{ route('customer.products.check-availability') }}"
     data-csrf="{{ csrf_token() }}">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-4 sm:mb-6 gap-2">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-[#1d1d1f] tracking-tight">Equipment</h1>
            <p class="text-xs sm:text-sm text-[#6e6e73] mt-0.5 hidden sm:block">Temukan peralatan fotografi terbaik untuk kebutuhan Anda</p>
        </div>
    </div>

    {{-- Search minimalis --}}
    <form method="GET" action="{{ route('customer.products.index') }}" class="mb-4 sm:mb-6">
        <div class="flex items-center gap-2">
            <div class="relative flex-1 min-w-0">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                </span>
                <input type="text" class="input-apple !rounded-full !pl-11" name="search" value="{{ request('search') }}" placeholder="Cari equipment...">
            </div>
            <button type="submit" class="btn-dark-apple shrink-0 !px-5 sm:!px-7">Cari</button>
            <a href="{{ route('customer.products.index') }}" class="btn-outline-apple shrink-0 !p-3" title="Reset">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-3 gap-2 mt-2">
            <select class="select-apple !rounded-full !py-2.5 !text-xs sm:!text-sm" name="kategori">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('kategori') == $category->slug ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                @endforeach
            </select>
            <select class="select-apple !rounded-full !py-2.5 !text-xs sm:!text-sm" name="brand">
                <option value="">Semua Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>{{ $brand->nama_brand }}</option>
                @endforeach
            </select>
            <select class="select-apple !rounded-full !py-2.5 !text-xs sm:!text-sm" name="sort">
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            </select>
        </div>
    </form>

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

</div>

{{-- Add to Cart Modal — Minimalis Apple --}}
<dialog id="addToCartModal" class="p-0 bg-transparent backdrop:bg-black/30 backdrop:backdrop-blur-[2px] open:animate-[fadeIn_0.2s_ease] max-w-none w-full h-full max-h-none items-center justify-center p-3">
    <div class="bg-white rounded-xl shadow-[0_8px_24px_rgba(0,0,0,0.12)] w-full mx-auto my-auto overflow-hidden max-h-[75vh] overflow-y-auto" style="max-width:280px; width: calc(100vw - 32px);">
        {{-- Header kotak kecil tanpa icon/X --}}
        <div class="sticky top-0 bg-white px-4 lg:px-3 pt-4 lg:pt-3 pb-3 lg:pb-2.5 border-b border-[#f0f0f2]">
            <h3 class="text-[13px] lg:text-[12px] font-semibold text-[#1d1d1f] tracking-tight leading-none" id="modalTitle">Sewa Equipment</h3>
            <p class="text-[11px] lg:text-[10px] text-[#86868b] leading-none mt-1" id="modalSubtitle">Atur jadwal sewa</p>
        </div>

        <form id="addToCartForm" method="POST" action="{{ route('customer.checkout.direct-rent') }}" class="px-4 lg:px-3 pb-4 lg:pb-3 pt-3 lg:pt-2.5">
            @csrf
            <input type="hidden" name="product_id" id="modal_product_id">

            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <label class="block text-[10px] font-medium text-[#86868b] tracking-wide uppercase mb-1">Tanggal Mulai</label>
                    <input type="date" class="input-apple !py-1.5 !text-[12px] !rounded-lg w-full" name="tanggal_sewa" id="start_date" required>
                </div>
                <div>
                    <label class="block text-[10px] font-medium text-[#86868b] tracking-wide uppercase mb-1">Jam Mulai</label>
                    <input type="time" class="input-apple !py-1.5 !text-[12px] !rounded-lg w-full" name="jam_mulai" id="jam_mulai" value="08:00" required>
                </div>
                <div>
                    <label class="block text-[10px] font-medium text-[#86868b] tracking-wide uppercase mb-1">Tanggal Kembali</label>
                    <input type="date" class="input-apple !py-1.5 !text-[12px] !rounded-lg datepicker w-full" name="tanggal_kembali" id="end_date" required>
                </div>
                <div>
                    <label class="block text-[10px] font-medium text-[#86868b] tracking-wide uppercase mb-1">Jumlah</label>
                    <div class="flex items-center rounded-lg bg-[#f5f5f7] p-0.5">
                        <button type="button" class="w-6 h-6 rounded-md bg-white shadow-sm flex items-center justify-center text-[#1d1d1f] shrink-0 hover:bg-white active:scale-95 transition text-xs" onclick="let e=document.getElementById('quantity'); if(parseInt(e.value)>1){e.value=parseInt(e.value)-1; e.dispatchEvent(new Event('input')); e.dispatchEvent(new Event('change'));}">−</button>
                        <input type="number" class="flex-1 bg-transparent text-center text-[12px] font-semibold text-[#1d1d1f] outline-none py-0.5 min-w-0" name="jumlah" id="quantity" min="1" value="1" required>
                        <button type="button" class="w-6 h-6 rounded-md bg-white shadow-sm flex items-center justify-center text-[#1d1d1f] shrink-0 hover:bg-white active:scale-95 transition text-xs" onclick="let e=document.getElementById('quantity'); e.value=parseInt(e.value)+1; e.dispatchEvent(new Event('input')); e.dispatchEvent(new Event('change'));">+</button>
                    </div>
                    <p class="text-[9px] text-[#86868b] mt-1 text-center" id="stock_info">Stok tersedia: -</p>
                </div>
            </div>

            {{-- Summary kotak kecil tanpa icon --}}
            <div class="rounded-lg bg-[#f5f5f7] p-2.5 mb-2.5 flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 text-sm min-w-0">
                    <span class="text-[#6e6e73] text-[11px]">Durasi</span>
                    <strong class="text-[#1d1d1f] text-[11px]" id="rental_days">1 hari</strong>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[9px] text-[#86868b] uppercase tracking-wide">per hari</span>
                    <p class="text-[12px] font-semibold text-[#1d1d1f]" id="price_per_day_wrap"><span class="text-[#86868b] font-normal">Rp</span> <span id="price_per_day">0</span></p>
                </div>
            </div>

            <div class="flex items-center justify-between py-2 border-y border-[#f0f0f2] mb-3 gap-2">
                <span class="text-[11px] font-medium text-[#6e6e73]">Subtotal</span>
                <strong class="text-[14px] font-semibold text-[#1d1d1f] tracking-tight text-right" id="subtotal">Rp 0</strong>
            </div>

            <div class="flex gap-2">
                <button type="button" class="flex-1 py-2 rounded-full border border-[#e5e5e7] text-[12px] font-medium text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f] active:scale-[0.98] transition" onclick="addToCartModal.close()">Batal</button>
                <button type="submit" class="flex-1 py-2 rounded-full bg-[#1d1d1f] text-white text-[12px] font-semibold hover:bg-black active:scale-[0.98] transition flex items-center justify-center" id="modalSubmitBtn">Lanjutkan</button>
            </div>
        </form>
    </div>
</dialog>
<style>
@keyframes fadeIn { from { opacity:0; transform: scale(0.98) } to { opacity:1; transform: scale(1) } }
dialog::backdrop { background: rgba(0,0,0,0.3); backdrop-filter: blur(2px); }
dialog[open] { display:flex; }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const IS_GUEST = {{ auth()->check() ? 'false' : 'true' }};
        const LOGIN_URL = '{{ route("login") }}';
        function requireLogin() {
            alert('Silakan login terlebih dahulu untuk menyewa.');
            window.location.href = LOGIN_URL;
        }
        function calculateRentalDays() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
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
        document.getElementById('quantity').addEventListener('input', updatePrice);
        // close on backdrop click
        addToCartModal.addEventListener('click', function(e){ if(e.target===this) this.close(); });

        document.querySelectorAll('.add-to-cart').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (IS_GUEST) { requireLogin(); return; }
                const productId = this.dataset.productId;
                const btnOld = this.innerHTML;
                this.disabled = true;
                this.textContent = '...';
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
                        // price_per_day now expects numeric, fallback to formatted
                        const priceEl = document.getElementById('price_per_day');
                        priceEl.textContent = (response.price_per_day || 0).toLocaleString('id-ID');
                        priceEl.dataset.price = response.price_per_day;
                        document.getElementById('stock_info').textContent = 'Stok tersedia: ' + (response.available_stock || '?');
                        calculateRentalDays();
                        addToCartModal.showModal();
                    } else {
                        alert(response.message || 'Stok tidak tersedia');
                    }
                })
                .catch(()=> alert('Gagal cek ketersediaan'))
                .finally(()=> { btn.disabled=false; btn.textContent='Sewa'; });
            });
        });

        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (IS_GUEST) { requireLogin(); return; }
            const submitBtn = document.getElementById('modalSubmitBtn');
            const orig = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span> Memproses...';
            const formData = new FormData(this);
            fetch('{{ route("customer.checkout.direct-rent") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                body: formData
            })
            .then(r => {
                if (r.status === 401) { requireLogin(); return null; }
                return r.json();
            })
            .then(function(data) {
                if (!data) return;
                addToCartModal.close();
                if (data.success) window.location.href = '{{ route("customer.checkout.index") }}';
                else {
                    alert(data.message || 'Terjadi kesalahan');
                    submitBtn.disabled=false; submitBtn.innerHTML=orig;
                }
            })
            .catch(()=> { alert('Terjadi kesalahan. Coba lagi'); submitBtn.disabled=false; submitBtn.innerHTML=orig; });
        });
    });
</script>
@endpush
