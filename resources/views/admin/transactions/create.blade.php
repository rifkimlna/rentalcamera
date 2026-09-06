@extends('layouts.admin')

@section('title', 'Buat Transaksi Manual - Stekpro Multimedia & Broadcast')
@section('page-title', 'Buat Transaksi Manual')

@section('content')
<x-flash-messages />
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Buat Transaksi Manual</h1>
        <div>
            <a href="{{ route('admin.transactions.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="{{ route('admin.transactions.store.manual') }}" method="POST" id="transactionForm">
                @csrf

                <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Customer</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="user_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Pilih Customer *</span></label>
                                <select class="select-apple w-full @error('user_id') select-error @enderror" id="user_id" name="user_id" required>
                                    <option value="">Pilih Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" data-phone="{{ $customer->telepon }}" data-email="{{ $customer->email }}" data-address="{{ $customer->alamat }}">{{ $customer->nama }} - {{ $customer->email }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Telepon</span></label>
                                        <input type="text" class="input-apple w-full" id="customer_phone" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Email</span></label>
                                        <input type="text" class="input-apple w-full" id="customer_email" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-3">
                <button type="submit" class="btn-dark-apple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Buat Transaksi
                </button>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <h5 class="text-lg font-semibold text-[#1d1d1f] mb-4">Ringkasan</h5>
                <div class="space-y-2 text-sm" id="orderSummary">
                    <div class="flex justify-between">
                        <span>Total Hari:</span>
                        <span id="totalDays">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span>Grand Total:</span>
                        <span id="grandTotal">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="product-row-template">
    <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 mb-3">
        <div class="md:col-span-5">
            <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Produk *</span></label>
            <select class="select-apple w-full product-select" name="products[IDX][id]" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->harga_per_hari }}" data-stock="{{ $product->stok_tersedia }}">{{ $product->nama_produk }} - Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}/hari (Stok: {{ $product->stok_tersedia }})</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Jumlah *</span></label>
            <input type="number" class="input-apple w-full product-quantity" name="products[IDX][quantity]" min="1" value="1" required>
            <small class="text-[#6e6e73] stock-info"></small>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Subtotal</span></label>
            <input type="text" class="input-apple w-full product-subtotal" readonly value="Rp 0">
        </div>
        <div class="md:col-span-1 flex items-end">
            <button type="button" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors remove-product w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>
</template>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Penyewaan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_sewa" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Sewa *</span></label>
                                <input type="date" class="input-apple w-full @error('tanggal_sewa') input-error @enderror" id="tanggal_sewa" name="tanggal_sewa" value="{{ old('tanggal_sewa', date('Y-m-d')) }}" required>
                                @error('tanggal_sewa')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="tanggal_kembali" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Kembali *</span></label>
                                <input type="date" class="input-apple w-full @error('tanggal_kembali') input-error @enderror" id="tanggal_kembali" name="tanggal_kembali" required>
                                @error('tanggal_kembali')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="metode_pengambilan" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Metode Pengambilan *</span></label>
                                <select class="select-apple w-full @error('metode_pengambilan') select-error @enderror" id="metode_pengambilan" name="metode_pengambilan" required>
                                    <option value="pickup" {{ old('metode_pengambilan') == 'pickup' ? 'selected' : '' }}>Ambil di Toko</option>
                                </select>
                                @error('metode_pengambilan')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="metode_pengembalian" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Metode Pengembalian *</span></label>
                                <select class="select-apple w-full @error('metode_pengembalian') select-error @enderror" id="metode_pengembalian" name="metode_pengembalian" required>
                                    <option value="return" {{ old('metode_pengembalian') == 'return' ? 'selected' : '' }}>Kembali ke Toko</option>
                                </select>
                                @error('metode_pengembalian')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="catatan" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Catatan (Opsional)</span></label>
                            <textarea class="input-apple resize-none w-full @error('catatan') textarea-error @enderror" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                            @error('catatan')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                    <div class="p-5">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-[#1d1d1f]">Pilih Produk</h2>
                            <button type="button" class="btn-dark-apple !text-sm !px-3 !py-1.5" id="addProductBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Produk
                            </button>
                        </div>
                        <div id="products-container">
                            <div class="product-row grid grid-cols-1 md:grid-cols-12 gap-4 mb-3">
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Produk *</span></label>
                                    <select class="select-apple w-full product-select" name="products[0][id]" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->harga_per_hari }}" data-stock="{{ $product->stok_tersedia }}">{{ $product->nama_produk }} - Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}/hari (Stok: {{ $product->stok_tersedia }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Jumlah *</span></label>
                                    <input type="number" class="input-apple w-full product-quantity" name="products[0][quantity]" min="1" value="1" required>
                                    <small class="text-[#6e6e73] stock-info"></small>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Subtotal</span></label>
                                    <input type="text" class="input-apple w-full product-subtotal" readonly value="Rp 0">
                                </div>
                                <div class="md:col-span-1 flex items-end">
                                    <button type="button" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors remove-product w-full" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <div class="w-full md:w-1/2">
                                <table class="w-full text-sm">
                                    <tbody>
                                        <tr><td class="font-semibold">Subtotal:</td><td class="text-end" id="total-subtotal">Rp 0</td></tr>
                                        <tr><td class="font-semibold">Biaya Asuransi:</td><td class="text-end" id="insurance-cost">Rp 0</td></tr>
                                        <tr class="bg-[#f5f5f7]"><td class="font-bold">Grand Total:</td><td class="text-end font-bold" id="grand-total">Rp 0</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Pembayaran (Mode Developer)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="payment_method_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Metode Pembayaran *</span></label>
                                <select class="select-apple w-full @error('payment_method_id') select-error @enderror" id="payment_method_id" name="payment_method_id" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    @foreach($paymentMethods as $method)
                                        @if($method->is_active)
                                            <option value="{{ $method->id }}" data-type="{{ $method->type }}" data-fee-percentage="{{ $method->fee_percentage }}" data-fee-flat="{{ $method->fee_flat }}">{{ $method->name }} @if($method->fee_percentage > 0 || $method->fee_flat > 0)(Biaya: @if($method->fee_percentage > 0){{ $method->fee_percentage }}%@endif @if($method->fee_flat > 0)@if($method->fee_percentage > 0)+ @endif Rp {{ number_format($method->fee_flat, 0, ',', '.') }}@endif)@endif</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('payment_method_id')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="payment_status" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status Pembayaran *</span></label>
                                <select class="select-apple w-full @error('payment_status') select-error @enderror" id="payment_status" name="payment_status" required>
                                    <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="settlement" {{ old('payment_status') == 'settlement' ? 'selected' : '' }}>Lunas (Paid)</option>
                                    <option value="capture" {{ old('payment_status') == 'capture' ? 'selected' : '' }}>Terkonfirmasi</option>
                                    <option value="cancel" {{ old('payment_status') == 'cancel' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="expire" {{ old('payment_status') == 'expire' ? 'selected' : '' }}>Kadaluarsa</option>
                                </select>
                                @error('payment_status')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div id="payment-details" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="bank" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Bank (Opsional)</span></label>
                                    <input type="text" class="input-apple w-full @error('bank') input-error @enderror" id="bank" name="bank" value="{{ old('bank') }}">
                                    @error('bank')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="va_number" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nomor VA (Opsional)</span></label>
                                    <input type="text" class="input-apple w-full @error('va_number') input-error @enderror" id="va_number" name="va_number" value="{{ old('va_number') }}">
                                    @error('va_number')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="payment_code" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kode Pembayaran (Opsional)</span></label>
                                    <input type="text" class="input-apple w-full @error('payment_code') input-error @enderror" id="payment_code" name="payment_code" value="{{ old('payment_code') }}">
                                    @error('payment_code')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="paid_at" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Pembayaran (Jika Lunas)</span></label>
                                    <input type="datetime-local" class="input-apple w-full @error('paid_at') input-error @enderror" id="paid_at" name="paid_at" value="{{ old('paid_at') }}">
                                    @error('paid_at')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="payment_notes" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Catatan Pembayaran (Opsional)</span></label>
                                <textarea class="input-apple resize-none w-full @error('payment_notes') textarea-error @enderror" id="payment_notes" name="payment_notes" rows="2">{{ old('payment_notes') }}</textarea>
                                @error('payment_notes')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div role="alert" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0faf1] border border-[#d1f5d5] text-[#0071e3] text-sm mt-4" id="payment-fee-info" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="fee-text"></span>
                            <br>
                            <strong>Total setelah biaya: <span id="final-total">Rp 0</span></strong>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('admin.transactions.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-dark-apple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Buat Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productCount = 1;
    const productsContainer = document.getElementById('products-container');
    const addProductBtn = document.getElementById('addProductBtn');
    const paymentDetails = document.getElementById('payment-details');
    const paymentFeeInfo = document.getElementById('payment-fee-info');
    const feeText = document.getElementById('fee-text');
    const finalTotal = document.getElementById('final-total');

    document.getElementById('user_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value) {
            document.getElementById('customer_phone').value = selectedOption.dataset.phone || '';
            document.getElementById('customer_email').value = selectedOption.dataset.email || '';
        }
    });

    function calculateRentalDays() {
        const startDate = new Date(document.getElementById('tanggal_sewa').value);
        const endDate = new Date(document.getElementById('tanggal_kembali').value);
        if (startDate && endDate && endDate > startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        }
        return 0;
    }

    function calculateProductSubtotal(productRow) {
        const productSelect = productRow.querySelector('.product-select');
        const quantityInput = productRow.querySelector('.product-quantity');
        const subtotalInput = productRow.querySelector('.product-subtotal');
        if (productSelect.value && quantityInput.value) {
            const price = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price) || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            const rentalDays = calculateRentalDays();
            const subtotal = price * quantity * rentalDays;
            subtotalInput.value = 'Rp ' + subtotal.toLocaleString('id-ID');
            const stock = parseInt(productSelect.options[productSelect.selectedIndex].dataset.stock) || 0;
            const stockInfo = productRow.querySelector('.stock-info');
            if (stockInfo) {
                stockInfo.textContent = 'Stok tersedia: ' + stock;
                if (quantity > stock) {
                    stockInfo.classList.add('text-[#d70015]');
                    stockInfo.textContent += ' (Jumlah melebihi stok!)';
                } else {
                    stockInfo.classList.remove('text-[#d70015]');
                }
            }
            return subtotal;
        }
        return 0;
    }

    function calculateTotals() {
        let totalSubtotal = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const productSelect = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.product-quantity');
            if (productSelect.value && quantityInput.value) {
                const price = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const rentalDays = calculateRentalDays();
                totalSubtotal += price * quantity * rentalDays;
            }
        });
        const insuranceCost = totalSubtotal * 0.005;
        const grandTotal = totalSubtotal + insuranceCost;
        document.getElementById('total-subtotal').textContent = 'Rp ' + totalSubtotal.toLocaleString('id-ID');
        document.getElementById('insurance-cost').textContent = 'Rp ' + Math.round(insuranceCost).toLocaleString('id-ID');
        document.getElementById('grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        calculatePaymentFees(grandTotal);
        return grandTotal;
    }

    function calculatePaymentFees(grandTotal) {
        const paymentMethodSelect = document.getElementById('payment_method_id');
        if (paymentMethodSelect.value) {
            const selectedOption = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
            const feePercentage = parseFloat(selectedOption.dataset.feepercentage) || 0;
            const feeFlat = parseFloat(selectedOption.dataset.feeflat) || 0;
            if (feePercentage > 0 || feeFlat > 0) {
                const feeAmount = (grandTotal * feePercentage / 100) + feeFlat;
                const finalAmount = grandTotal + feeAmount;
                feeText.innerHTML = 'Biaya transaksi: ' + (feePercentage > 0 ? feePercentage + '%' : '') + (feePercentage > 0 && feeFlat > 0 ? ' + ' : '') + (feeFlat > 0 ? 'Rp ' + feeFlat.toLocaleString('id-ID') : '') + ' = Rp ' + feeAmount.toLocaleString('id-ID');
                finalTotal.textContent = 'Rp ' + finalAmount.toLocaleString('id-ID');
                paymentFeeInfo.style.display = 'block';
            } else {
                paymentFeeInfo.style.display = 'none';
            }
        } else {
            paymentFeeInfo.style.display = 'none';
        }
    }

    document.getElementById('payment_method_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const paymentType = selectedOption.dataset.type;
            paymentDetails.style.display = ['bank_transfer', 'ewallet', 'qris'].includes(paymentType) ? 'block' : 'none';
            const grandTotal = parseFloat(document.getElementById('grand-total').textContent.replace('Rp', '').replace(/\./g, '').replace(',', '').trim()) || 0;
            calculatePaymentFees(grandTotal);
        } else {
            paymentDetails.style.display = 'none';
            paymentFeeInfo.style.display = 'none';
        }
    });

    document.getElementById('payment_status').addEventListener('change', function() {
        const paidAtField = document.getElementById('paid_at');
        if ((this.value === 'settlement' || this.value === 'capture') && !paidAtField.value) {
            const now = new Date();
            paidAtField.value = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        }
    });

    addProductBtn.addEventListener('click', function() {
        const template = document.getElementById('product-row-template');
        const row = template.content.cloneNode(true).firstElementChild;
        row.querySelectorAll('[name*="IDX"]').forEach(function(el) {
            el.name = el.name.replace('IDX', productCount);
        });
        productsContainer.appendChild(row);
        productCount++;
        if (document.querySelectorAll('.product-row').length > 1) {
            document.querySelector('.product-row .remove-product').disabled = false;
        }
    });

    productsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-product')) {
            const row = e.target.closest('.product-row');
            if (document.querySelectorAll('.product-row').length > 1) {
                row.remove();
                calculateTotals();
            }
        }
    });

    productsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('product-quantity')) {
            calculateProductSubtotal(e.target.closest('.product-row'));
            calculateTotals();
        }
    });

    document.getElementById('tanggal_sewa').addEventListener('change', calculateTotals);
    document.getElementById('tanggal_kembali').addEventListener('change', calculateTotals);


    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        let isValid = true, errorMessage = '';
        const productSelects = document.querySelectorAll('.product-select');
        let hasProduct = false;
        productSelects.forEach(select => { if (select.value) hasProduct = true; });
        if (!hasProduct) { isValid = false; errorMessage += '- Pilih minimal 1 produk\n'; }
        const rentalDays = calculateRentalDays();
        if (rentalDays < 1) { isValid = false; errorMessage += '- Tanggal kembali harus setelah tanggal sewa\n'; }
        document.querySelectorAll('.product-row').forEach(row => {
            const ps = row.querySelector('.product-select'), qi = row.querySelector('.product-quantity');
            if (ps.value && qi.value) {
                const stock = parseInt(ps.options[ps.selectedIndex].dataset.stock) || 0;
                const quantity = parseInt(qi.value) || 0;
                if (quantity > stock) {
                    isValid = false;
                    errorMessage += '- Stok tidak cukup untuk ' + ps.options[ps.selectedIndex].text.split(' - ')[0] + ' (tersedia: ' + stock + ', pesan: ' + quantity + ')\n';
                }
            }
        });
        if (!isValid) { e.preventDefault(); alert('Perbaiki kesalahan berikut:\n\n' + errorMessage); }
    });

    calculateTotals();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_sewa').min = today;
    document.getElementById('tanggal_kembali').min = today;
    document.getElementById('tanggal_sewa').addEventListener('change', function() {
        if (this.value) {
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            document.getElementById('tanggal_kembali').min = nextDay.toISOString().split('T')[0];
            const crd = document.getElementById('tanggal_kembali').value;
            if (!crd || new Date(crd) < nextDay) {
                document.getElementById('tanggal_kembali').value = nextDay.toISOString().split('T')[0];
            }
        }
    });
});
</script>
@endpush
