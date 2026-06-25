@extends('layouts.customer')

@section('title', 'Checkout - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <div class="text-sm breadcrumbs mb-4">
        <ul>
            <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('customer.cart.index') }}">Keranjang</a></li>
            <li>Checkout</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
            <button class="btn btn-sm btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
            <button class="btn btn-sm btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    @if(count($keranjangItems) == 0)
        <div class="card bg-base-100 shadow-md">
            <div class="card-body text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                <h4 class="mt-4 mb-2">Keranjang Anda kosong</h4>
                <p class="text-base-content/60 mb-4">Silakan tambahkan produk ke keranjang terlebih dahulu</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lihat Produk
                </a>
            </div>
        </div>
    @else
        <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkoutForm" 
              data-shipping-cost="{{ $shippingFee }}" data-subtotal="{{ $subtotal }}"
              data-validate-url="{{ route('customer.checkout.validate-voucher') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Left Column: Order Details -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Customer Information Card -->
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Customer
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Nama Lengkap *</span>
                                    </label>
                                    <input type="text" class="input input-bordered w-full" 
                                           name="nama_customer" 
                                           value="{{ auth()->user()->nama }}" 
                                           required>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Email *</span>
                                    </label>
                                    <input type="email" class="input input-bordered w-full" 
                                           name="email_customer" 
                                           value="{{ auth()->user()->email }}" 
                                           required>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">No. Telepon *</span>
                                    </label>
                                    <input type="tel" class="input input-bordered w-full" 
                                           name="telepon_customer" 
                                           value="{{ auth()->user()->telepon }}" 
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Alamat Pengiriman
                            </h5>
                            <div class="space-y-3">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Alamat Lengkap *</span>
                                    </label>
                                    <textarea class="textarea textarea-bordered w-full" 
                                              name="alamat_pengiriman" 
                                              rows="3" required>{{ auth()->user()->alamat }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="label">
                                            <span class="label-text">Kota *</span>
                                        </label>
                                        <input type="text" class="input input-bordered w-full" 
                                               name="kota_pengiriman" 
                                               value="{{ auth()->user()->kota }}" 
                                               required>
                                    </div>
                                    <div>
                                        <label class="label">
                                            <span class="label-text">Provinsi *</span>
                                        </label>
                                        <input type="text" class="input input-bordered w-full" 
                                               name="provinsi_pengiriman" 
                                               value="{{ auth()->user()->provinsi }}" 
                                               required>
                                    </div>
                                    <div>
                                        <label class="label">
                                            <span class="label-text">Kode Pos *</span>
                                        </label>
                                        <input type="text" class="input input-bordered w-full" 
                                               name="kode_pos_pengiriman" 
                                               value="{{ auth()->user()->kode_pos }}" 
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Card -->
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Detail Pesanan
                            </h5>
                            <div class="overflow-x-auto">
                                <table class="table table-zebra">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga/Hari</th>
                                            <th class="text-center">Jumlah</th>
                                            <th>Durasi</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalSubtotal = 0;
                                            $totalDays = 0;
                                        @endphp

                                        @foreach($keranjangItems as $item)
                                            @php
                                                $days = $item->lama_sewa;
                                                $harga = optional($item->produk)->harga_per_hari ?? 0;
                                                $jumlah = $item->jumlah;
                                                $subtotal = $harga * $days * $jumlah;
                                                $totalSubtotal += $subtotal;
                                                $totalDays += $days;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="flex items-center gap-3">
                                                        @if($item->produk && $item->produk->gambar_utama)
                                                            <img src="{{ asset('storage/' . $item->produk->gambar_utama) }}" 
                                                                 alt="{{ optional($item->produk)->nama_produk }}" 
                                                                 class="rounded w-16 h-16 object-cover">
                                                        @else
                                                            <div class="bg-base-200 rounded flex items-center justify-center w-16 h-16">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-1 font-semibold">{{ optional($item->produk)->nama_produk ?? 'Produk tidak tersedia' }}</h6>
                                                            <small class="text-base-content/60">
                                                                Brand: {{ optional(optional($item->produk)->brand)->nama_brand ?? '' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $jumlah }}x</td>
                                                <td>
                                                    <div class="flex items-start gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <div>
                                                            {{ $days }} hari
                                                            <br>
                                                            <small class="text-base-content/60">
                                                                 {{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d M Y') }} - 
                                                                 {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right font-bold">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Method Card -->
                    <div class="card bg-base-100 shadow-md">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1m8 1V6l-2-1m2 11h6m-6 0a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 00-1-1h-2l-3 3z" />
                                </svg>
                                Metode Pengiriman
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-base-300 has-checked:border-primary has-checked:bg-primary/5">
                                        <input class="radio radio-primary mt-1" type="radio" 
                                               name="metode_pengambilan" 
                                               id="pickup" 
                                               value="pickup" 
                                               checked>
                                        <div>
                                            <h6 class="font-semibold mb-1">Ambil Sendiri</h6>
                                            <p class="text-base-content/60 mb-0 text-sm">
                                                Ambil langsung di toko kami
                                            </p>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-base-300 has-checked:border-primary has-checked:bg-primary/5">
                                        <input class="radio radio-primary mt-1" type="radio" 
                                               name="metode_pengambilan" 
                                               id="delivery" 
                                               value="delivery">
                                        <div>
                                            <h6 class="font-semibold mb-1">Dikirim</h6>
                                            <p class="text-base-content/60 mb-0 text-sm">
                                                Biaya pengiriman: Rp {{ number_format($shippingFee, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metode Pengembalian -->
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Metode Pengembalian
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-base-300 has-checked:border-primary has-checked:bg-primary/5">
                                    <input class="radio radio-primary mt-1" type="radio" 
                                           name="metode_pengembalian" 
                                           id="return_pickup" 
                                           value="return" 
                                           checked>
                                    <div>
                                        <h6 class="font-semibold mb-1">Kembalikan ke Toko</h6>
                                        <p class="text-base-content/60 mb-0 text-sm">Antar langsung ke toko kami</p>
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-base-300 has-checked:border-primary has-checked:bg-primary/5">
                                    <input class="radio radio-primary mt-1" type="radio" 
                                           name="metode_pengembalian" 
                                           id="return_pickup_service" 
                                           value="pickup">
                                    <div>
                                        <h6 class="font-semibold mb-1">Dijemput</h6>
                                        <p class="text-base-content/60 mb-0 text-sm">Kami jemput ke lokasi Anda</p>
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-start gap-3 cursor-pointer p-4 rounded-lg border border-base-300 has-checked:border-primary has-checked:bg-primary/5">
                                    <input class="radio radio-primary mt-1" type="radio" 
                                           name="metode_pengembalian" 
                                           id="return_both" 
                                           value="both">
                                    <div>
                                        <h6 class="font-semibold mb-1">Fleksibel</h6>
                                        <p class="text-base-content/60 mb-0 text-sm">Bisa antar atau dijemput</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div>
                    <div class="card bg-base-100 shadow-md sticky top-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ringkasan Pesanan
                            </h5>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between">
                                    <span>Subtotal ({{ $totalDays }} hari):</span>
                                    <span class="font-bold">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Biaya Pengiriman:</span>
                                    <span id="shippingCostDisplay">Rp 0</span>
                                </div>

                                <!-- Voucher Section -->
                                <div class="pt-3">
                                    <label class="label">
                                        <span class="label-text">Kode Voucher</span>
                                    </label>
                                    <div class="join w-full">
                                        <input type="text" class="input input-bordered join-item flex-1" 
                                               id="voucher_code" name="voucher_code"
                                               placeholder="Masukkan kode">
                                        <button type="button" class="btn btn-outline btn-primary join-item" 
                                                id="applyVoucherBtn">
                                            Terapkan
                                        </button>
                                    </div>
                                    <div id="voucherMessage" class="mt-2"></div>
                                    <input type="hidden" name="voucher_id" id="voucher_id">
                                    <input type="hidden" name="diskon" id="diskon" value="0">
                                </div>

                                <hr>

                                <div class="flex justify-between items-center">
                                    <h5>Total Pembayaran</h5>
                                    <h4 class="text-primary" id="totalAmount">
                                        Rp {{ number_format($totalSubtotal, 0, ',', '.') }}
                                    </h4>
                                    <input type="hidden" name="grand_total" id="grand_total" value="{{ $totalSubtotal }}">
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="label">
                                    <span class="label-text font-semibold">Metode Pembayaran</span>
                                </label>
                                <div class="space-y-2">
                                    @foreach($paymentMethods as $method)
                                        <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-base-300 has-checked:border-primary has-checked:bg-primary/5">
                                            <input class="radio radio-primary mt-1 payment-method" 
                                                   type="radio" 
                                                   name="payment_method_id" 
                                                   id="method{{ $method->id }}" 
                                                   value="{{ $method->id }}"
                                                   data-type="{{ $method->type }}"
                                                   data-bank-code="{{ $method->bank_code }}"
                                                   data-fee-percentage="{{ $method->fee_percentage }}"
                                                   data-fee-flat="{{ $method->fee_flat }}"
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3 w-full">
                                                <div class="shrink-0">
                                                    @if($method->type == 'bank_transfer')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    @elseif($method->type == 'ewallet')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    @elseif($method->type == 'qris')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $method->name }}</strong>
                                                    @if($method->fee_percentage > 0 || $method->fee_flat > 0)
                                                        <small class="text-base-content/60 block">
                                                            Biaya admin: 
                                                            @if($method->fee_percentage > 0)
                                                                {{ $method->fee_percentage }}%
                                                            @endif
                                                            @if($method->fee_flat > 0)
                                                                + Rp {{ number_format($method->fee_flat, 0, ',', '.') }}
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <label class="flex items-start gap-2 mb-3">
                                <input class="checkbox checkbox-primary mt-1" type="checkbox" 
                                       id="agree_terms" name="agree_terms" value="1" required>
                                <span class="text-sm">
                                    Saya menyetujui 
                                    <a href="#" class="link link-primary" onclick="termsModal.showModal(); return false;">
                                        Syarat & Ketentuan
                                    </a>
                                </span>
                            </label>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-block btn-lg" 
                                    id="payButton">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Lanjutkan Pembayaran
                            </button>

                            <p class="text-center text-base-content/60 text-xs mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Pembayaran aman dan terenkripsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

<!-- Terms & Conditions Modal -->
<dialog id="termsModal" class="modal">
    <div class="modal-box max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Syarat & Ketentuan Penyewaan</h3>
        <div class="space-y-4">
            <div>
                <h6 class="font-semibold">1. Persyaratan Umum</h6>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li>Penyewa minimal berusia 17 tahun dan memiliki KTP/Kartu Pelajar yang valid</li>
                    <li>Penyewa bertanggung jawab penuh atas barang sewaan selama masa sewa</li>
                    <li>Pembayaran harus lunas sebelum barang diambil/dikirim</li>
                </ul>
            </div>

            <div>
                <h6 class="font-semibold">2. Ketentuan Penyewaan</h6>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li>Barang harus dikembalikan tepat waktu sesuai tanggal yang disepakati</li>
                    <li>Keterlambatan pengembalian akan dikenakan denda 50% dari harga sewa per hari</li>
                    <li>Barang harus dikembalikan dalam kondisi baik sesuai dengan foto saat pengambilan</li>
                </ul>
            </div>

            <div>
                <h6 class="font-semibold">3. Kebijakan Pembatalan</h6>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li>Pembatalan 7 hari sebelum tanggal sewa: refund 100%</li>
                    <li>Pembatalan 3-6 hari sebelum tanggal sewa: refund 50%</li>
                    <li>Pembatalan kurang dari 3 hari: tidak ada refund</li>
                </ul>
            </div>

            <div>
                <h6 class="font-semibold">4. Ketentuan Kerusakan & Kehilangan</h6>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <li>Kerusakan ringan akan dikenakan biaya perbaikan sesuai harga pasar</li>
                    <li>Kehilangan atau kerusakan total akan dikenakan biaya sesuai harga barang baru</li>
                    <li>Segala kerusakan harus dilaporkan maksimal 24 jam setelah pengambilan barang</li>
                </ul>
            </div>
        </div>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Tutup</button>
                <button class="btn btn-primary">Saya Mengerti</button>
            </form>
        </div>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    const shippingCost = parseFloat(checkoutForm.dataset.shippingCost) || 0;
    const currentSubtotal = parseFloat(checkoutForm.dataset.subtotal) || 0;
    let discount = 0;
    let voucherId = null;

    // Toggle shipping cost
    const shippingMethods = document.querySelectorAll('input[name="metode_pengambilan"]');
    shippingMethods.forEach(function(method) {
        method.addEventListener('change', function() {
            updateShippingCost(this.value);
            calculateTotal();
        });
    });

    function updateShippingCost(method) {
        const cost = method === 'delivery' ? shippingCost : 0;
        const displayElement = document.getElementById('shippingCostDisplay');
        if (displayElement) {
            displayElement.textContent = 'Rp ' + formatRupiah(cost);
        }
        return cost;
    }

    // Apply voucher
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const voucherCodeInput = document.getElementById('voucher_code');
            if (!voucherCodeInput) return;

            const voucherCode = voucherCodeInput.value.trim();

            if (!voucherCode) {
                showVoucherMessage('Masukkan kode voucher terlebih dahulu', 'danger');
                return;
            }

            const params = new URLSearchParams();
            params.append('_token', document.querySelector('input[name="_token"]').value);
            params.append('voucher_code', voucherCode);
            params.append('subtotal', currentSubtotal);

            fetch(checkoutForm.dataset.validateUrl, {
                method: 'POST',
                body: params,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    showVoucherMessage(response.message, 'success');
                    voucherId = response.voucher.id;
                    discount = response.voucher.diskon;

                    const voucherIdInput = document.getElementById('voucher_id');
                    const diskonInput = document.getElementById('diskon');

                    if (voucherIdInput) voucherIdInput.value = voucherId;
                    if (diskonInput) diskonInput.value = discount;

                    calculateTotal();
                } else {
                    showVoucherMessage(response.message, 'danger');
                    resetVoucher();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showVoucherMessage('Terjadi kesalahan saat validasi voucher', 'danger');
                resetVoucher();
            });
        });
    }

    function showVoucherMessage(message, type) {
        const alertClass = type === 'success' ? 'alert alert-success' : 'alert alert-error';
        const voucherMessageDiv = document.getElementById('voucherMessage');
        if (voucherMessageDiv) {
            voucherMessageDiv.innerHTML = 
                '<div class="' + alertClass + ' flex items-center gap-2 p-3 text-sm">' +
                '<span>' + message + '</span>' +
                '<button class="btn btn-xs btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button>' +
                '</div>';
        }
    }

    function resetVoucher() {
        voucherId = null;
        discount = 0;

        const voucherIdInput = document.getElementById('voucher_id');
        const diskonInput = document.getElementById('diskon');
        const voucherCodeInput = document.getElementById('voucher_code');

        if (voucherIdInput) voucherIdInput.value = '';
        if (diskonInput) diskonInput.value = 0;
        if (voucherCodeInput) voucherCodeInput.value = '';

        calculateTotal();
    }

    function calculateTotal() {
        const selectedShippingMethod = document.querySelector('input[name="metode_pengambilan"]:checked');
        if (!selectedShippingMethod) return;

        const shippingMethod = selectedShippingMethod.value;
        const shipping = updateShippingCost(shippingMethod);

        let total = currentSubtotal + shipping - discount;

        // Apply payment method fee
        const selectedMethod = document.querySelector('input[name="payment_method_id"]:checked');
        if (selectedMethod) {
            const feePercentage = parseFloat(selectedMethod.dataset.feePercentage || 0);
            const feeFlat = parseFloat(selectedMethod.dataset.feeFlat || 0);

            if (feePercentage > 0) {
                total += (total * feePercentage / 100);
            }

            if (feeFlat > 0) {
                total += feeFlat;
            }
        }

        const totalAmountElement = document.getElementById('totalAmount');
        const grandTotalInput = document.getElementById('grand_total');

        if (totalAmountElement) {
            totalAmountElement.textContent = 'Rp ' + formatRupiah(total);
        }

        if (grandTotalInput) {
            grandTotalInput.value = total;
        }
    }

    function formatRupiah(angka) {
        const num = typeof angka === 'number' ? angka : parseFloat(angka) || 0;
        return num.toLocaleString('id-ID');
    }

    // Recalculate when payment method changes
    const paymentMethods = document.querySelectorAll('input[name="payment_method_id"]');
    paymentMethods.forEach(function(method) {
        method.addEventListener('change', function() {
            calculateTotal();
        });
    });

    // Form submission
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const agreeTerms = document.getElementById('agree_terms');
            const payButton = document.getElementById('payButton');

            if (agreeTerms && !agreeTerms.checked) {
                e.preventDefault();

                if (typeof Swal === 'undefined') {
                    if (!confirm('Harap setujui Syarat & Ketentuan terlebih dahulu')) {
                        return false;
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Persetujuan Diperlukan',
                        text: 'Harap setujui Syarat & Ketentuan terlebih dahulu',
                        confirmButtonColor: '#0d6efd'
                    });
                }
                return false;
            }

            // Disable button to prevent double click
            if (payButton) {
                payButton.disabled = true;
                payButton.innerHTML = '<span class="loading loading-spinner loading-sm me-2"></span>Memproses...';
            }
        });
    }

    // Initial calculation
    calculateTotal();
});
</script>
@endpush
