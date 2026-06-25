@extends('layouts.customer')

@section('title', 'Keranjang - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <!-- Page header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Keranjang Saya</h1>
            <p class="text-base-content/60 mb-0">Review produk yang akan Anda sewa</p>
        </div>
        <div>
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Lanjutkan Belanja
            </a>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Cart items -->
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th class="w-14"></th>
                                        <th>Produk</th>
                                        <th class="text-center">Durasi</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-right">Harga</th>
                                        <th class="text-right">Total</th>
                                        <th class="w-14"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->produk && $item->produk->gambar_utama)
                                                    <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" 
                                                         class="rounded w-12 h-12 object-cover">
                                                @else
                                                    <div class="bg-base-200 rounded flex items-center justify-center w-12 h-12">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</h6>
                                                    <small class="text-base-content/60">{{ optional($item->produk)->brand?->nama_brand ?? '' }}</small>
                                                    <div class="text-xs text-base-content/60">
                                                        {{ optional(optional($item->produk)->kategori)->nama_kategori ?? '' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div>
                                                    <small>{{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d M') }}</small>
                                                    <div class="text-xs text-base-content/60">s/d</div>
                                                    <small>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M') }}</small>
                                                    <div class="text-xs text-primary">{{ $item->lama_sewa }} hari</div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="join" style="width: 100px;">
                                                        <input type="number" class="input input-bordered input-sm join-item w-full text-center" name="jumlah" 
                                                               value="{{ $item->jumlah }}" min="1" max="{{ optional($item->produk)->stok_tersedia ?? 0 }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-ghost btn-xs mt-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-right">
                                                <div>Rp {{ number_format($item->produk->harga_per_hari, 0, ',', '.') }}</div>
                                                <small class="text-base-content/60">per hari</small>
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $hargaPerHari = optional($item->produk)->harga_per_hari ?? 0;
                                                    $itemSubtotal = $hargaPerHari * $item->lama_sewa * $item->jumlah;
                                                @endphp
                                                <div class="text-primary">
                                                    <strong>Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="{{ route('customer.cart.destroy', $item->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost text-error btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
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
                                <button type="submit" class="btn btn-outline btn-error btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Kosongkan Keranjang
                                </button>
                            </form>

                            <div class="text-base-content/60">
                                {{ $cartItems->count() }} produk di keranjang
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order summary -->
            <div>
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ringkasan Pesanan</h5>
                        <div class="space-y-3">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Subtotal Sewa</td>
                                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Pengiriman</td>
                                        <td class="text-right">-</td>
                                    </tr>
                                    <tr class="bg-base-200">
                                        <td><strong>Total</strong></td>
                                        <td class="text-right">
                                            <strong class="text-primary">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary btn-block btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Lanjut ke Checkout
                            </a>

                            <div class="text-center mt-3">
                                <small class="text-base-content/60">
                                    Dengan melanjutkan, Anda menyetujui 
                                    <a href="#" class="link link-primary">Syarat & Ketentuan</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @else
        <!-- Empty cart -->
        <div class="card bg-base-100 shadow-md">
            <div class="card-body text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                <h4 class="mt-4 mb-2">Keranjang Kosong</h4>
                <p class="text-base-content/60 mb-4">Tambahkan produk ke keranjang untuk memulai penyewaan</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Sewa Kamera Sekarang
                </a>
            </div>
        </div>
    @endif
</div>

@endsection
