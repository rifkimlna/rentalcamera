@extends('layouts.customer')

@section('title', 'Keranjang - Stekpro Multimedia & Broadcast')
@section('page-title', 'Keranjang Saya')

@section('content')
<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Keranjang Saya</h1>
            <p class="text-[#6e6e73] mb-0">Review produk yang akan Anda sewa</p>
        </div>
        <div>
            <a href="{{ route('customer.products.index') }}" class="btn-outline-apple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Lanjutkan Belanja
            </a>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <div class="card-apple-static">
                    <div class="p-5">
                        {{-- Mobile view --}}
                        <div class="md:hidden space-y-3">
                            @foreach($cartItems as $item)
                                @php
                                    $hargaPerHari = optional($item->produk)->harga_per_hari ?? 0;
                                    $itemSubtotal = $hargaPerHari * $item->lama_sewa * $item->jumlah;
                                @endphp
                                <div class="bg-white border border-[#f0f0f2] rounded-xl p-3">
                                    <div class="flex items-start gap-3">
                                        @if($item->produk && $item->produk->gambar_utama)
                                            <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" alt="{{ $item->produk->nama_produk }}" class="w-14 h-14 rounded-xl object-cover shrink-0" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.classList.remove('hidden')">
                                            <div class="bg-[#f5f5f7] rounded-xl flex items-center justify-center w-14 h-14 hidden shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </div>
                                        @else
                                            <div class="bg-[#f5f5f7] rounded-xl flex items-center justify-center w-14 h-14 shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h6 class="text-sm font-semibold truncate">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</h6>
                                            <p class="text-xs text-[#6e6e73]">{{ optional(optional($item->produk)->kategori)->nama_kategori ?? '' }}</p>
                                            <p class="text-xs text-[#6e6e73]">
                                                {{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d M') }} - {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M') }} ({{ $item->lama_sewa }} hari)
                                            </p>
                                            <p class="text-sm font-bold text-[#0071e3] mt-1">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</p>
                                        </div>
                                        <form action="{{ route('customer.cart.destroy', $item->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[#d70015] hover:bg-[#d70015]/10 rounded-xl p-2 transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-[#f0f0f2]">
                                        <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <span class="text-xs text-[#6e6e73]">Jumlah:</span>
                                            <input type="number" class="input-apple w-16 text-center text-xs" name="jumlah" value="{{ $item->jumlah }}" min="1" max="{{ optional($item->produk)->stok_tersedia ?? 0 }}">
                                            <button type="submit" class="text-[#6e6e73] hover:text-[#1d1d1f] rounded-xl p-2 hover:bg-[#f5f5f7] transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                        </form>
                                        <span class="text-xs text-[#6e6e73]">{{ $item->jumlah }}x Rp {{ number_format($hargaPerHari, 0, ',', '.') }}/hari</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop view --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2 w-14"></th>
                                        <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Produk</th>
                                        <th class="text-center text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Durasi</th>
                                        <th class="text-center text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Jumlah</th>
                                        <th class="text-right text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Harga</th>
                                        <th class="text-right text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Total</th>
                                        <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2 w-14"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr class="border-t border-[#f0f0f2]">
                                            <td class="py-3 px-2">
                                                @if($item->produk && $item->produk->gambar_utama)
                                                    <div class="relative">
                                                        <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" alt="{{ $item->produk->nama_produk }}" class="rounded-xl w-12 h-12 object-cover" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.classList.remove('hidden')">
                                                        <div class="bg-[#f5f5f7] rounded-xl flex items-center justify-center w-12 h-12 hidden"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg></div>
                                                    </div>
                                                @else
                                                    <div class="bg-[#f5f5f7] rounded-xl flex items-center justify-center w-12 h-12"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg></div>
                                                @endif
                                            </td>
                                            <td class="py-3 px-2">
                                                <h6 class="mb-1">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</h6>
                                                <small class="text-[#6e6e73]">{{ optional($item->produk)->brand?->nama_brand ?? '' }}</small>
                                            </td>
                                            <td class="text-center py-3 px-2">
                                                <small>{{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d M') }}</small>
                                                <div class="text-xs text-[#6e6e73]">s/d</div>
                                                <small>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M') }}</small>
                                                <div class="text-xs text-[#0071e3]">{{ $item->lama_sewa }} hari</div>
                                            </td>
                                            <td class="text-center py-3 px-2">
                                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="inline">
                                                    @csrf @method('PUT')
                                                    <div class="flex items-center justify-center gap-1">
                                                        <input type="number" class="input-apple w-16 text-center text-xs" name="jumlah" value="{{ $item->jumlah }}" min="1" max="{{ optional($item->produk)->stok_tersedia ?? 0 }}">
                                                        <button type="submit" class="text-[#6e6e73] hover:text-[#1d1d1f] rounded-xl p-1 hover:bg-[#f5f5f7] transition-all"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-right py-3 px-2">
                                                <div>Rp {{ number_format(optional($item->produk)->harga_per_hari ?? 0, 0, ',', '.') }}</div>
                                                <small class="text-[#6e6e73]">per hari</small>
                                            </td>
                                            <td class="text-right py-3 px-2">
                                                @php $itemSubtotal = (optional($item->produk)->harga_per_hari ?? 0) * $item->lama_sewa * $item->jumlah; @endphp
                                                <div class="text-[#0071e3]"><strong>Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</strong></div>
                                            </td>
                                            <td class="py-3 px-2">
                                                <form action="{{ route('customer.cart.destroy', $item->id) }}" method="POST" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-[#6e6e73] hover:text-[#d70015] rounded-xl p-2 hover:bg-[#d70015]/10 transition-all"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <form action="{{ route('customer.cart.clear') }}" method="POST" class="delete-form">
                                @csrf
                                <button type="submit" class="btn-outline-apple border-[#d70015] text-[#d70015] hover:bg-[#d70015]/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Kosongkan
                                </button>
                            </form>
                            <div class="text-[#6e6e73] text-sm">{{ $cartItems->count() }} produk di keranjang</div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card-apple-static lg:sticky lg:top-20">
                    <div class="p-5">
                        <h5 class="font-semibold text-lg mb-4">Ringkasan Pesanan</h5>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal Sewa</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-[#f0f0f2] my-1"></div>
                            <div class="flex justify-between font-bold">
                                <span>Total</span>
                                <span class="text-[#0071e3]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('customer.checkout.index') }}" class="btn-dark-apple w-full mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Lanjut ke Checkout
                            </a>
                            <div class="text-center mt-2">
                                <small class="text-[#6e6e73]">Dengan melanjutkan, Anda menyetujui <a href="#" class="text-[#0071e3] hover:underline">Syarat & Ketentuan</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card-apple-static">
            <div class="p-5 text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" /></svg>
                <h4 class="mt-4 mb-2">Keranjang Kosong</h4>
                <p class="text-[#6e6e73] mb-4">Tambahkan produk ke keranjang untuk memulai penyewaan</p>
                <a href="{{ route('customer.products.index') }}" class="btn-dark-apple">Sewa Kamera Sekarang</a>
            </div>
        </div>
    @endif
</div>
@endsection


