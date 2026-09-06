@extends('layouts.admin')

@section('title', 'Tambah Voucher - Stekpro Multimedia & Broadcast')
@section('page-title', 'Tambah Voucher')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Tambah Voucher Baru</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0faf1] border border-[#d1f5d5] text-[#248a3d] text-sm mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#fef2f2] border border-[#fecaca] text-[#d70015] text-sm mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_voucher" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kode Voucher</span></label>
                        <div class="flex w-full">
                            <input type="text" class="input-apple w-full @error('kode_voucher') input-error @enderror"
                                   id="kode_voucher" name="kode_voucher" value="{{ old('kode_voucher') }}"
                                   placeholder="Kosongkan untuk generate otomatis">
                            <button type="button" class="btn-dark-apple  btn-outline" onclick="generateCode()">Generate</button>
                        </div>
                        <span class="text-xs text-[#6e6e73] mt-1">Kosongkan untuk generate otomatis</span>
                        @error('kode_voucher')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_voucher" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Voucher *</span></label>
                        <input type="text" class="input-apple w-full @error('nama_voucher') input-error @enderror"
                               id="nama_voucher" name="nama_voucher" value="{{ old('nama_voucher') }}" required>
                        @error('nama_voucher')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tipe *</span></label>
                        <select class="select-apple w-full @error('type') select-error @enderror"
                                id="type" name="type" required onchange="toggleTypeFields()">
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Nominal</option>

                        </select>
                        @error('type')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="value" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nilai *</span></label>
                        <input type="number" step="0.01" min="0" class="input-apple w-full @error('value') input-error @enderror"
                               id="value" name="value" value="{{ old('value') }}" required>
                        <span class="text-xs text-[#6e6e73]" id="value_hint">Dalam persen (%)</span>
                        @error('value')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="min_purchase" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Min. Pembelian</span></label>
                        <input type="number" step="0.01" min="0" class="input-apple w-full @error('min_purchase') input-error @enderror"
                               id="min_purchase" name="min_purchase" value="{{ old('min_purchase', 0) }}">
                        @error('min_purchase')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="max_discount_wrapper">
                        <label for="max_discount" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Maks. Diskon</span></label>
                        <input type="number" step="0.01" min="0" class="input-apple w-full @error('max_discount') input-error @enderror"
                               id="max_discount" name="max_discount" value="{{ old('max_discount') }}"
                               placeholder="Kosongkan jika tanpa batas">
                        <span class="text-xs text-[#6e6e73]">Hanya untuk tipe Persentase</span>
                        @error('max_discount')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="kuota" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kuota</span></label>
                        <input type="number" min="1" class="input-apple w-full @error('kuota') input-error @enderror"
                               id="kuota" name="kuota" value="{{ old('kuota') }}"
                               placeholder="Kosongkan jika tanpa batas">
                        @error('kuota')
                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Mulai *</span></label>
                            <input type="datetime-local" class="input-apple w-full @error('start_date') input-error @enderror"
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <span class="text-[#d70015] text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Berakhir *</span></label>
                            <input type="datetime-local" class="input-apple w-full @error('end_date') input-error @enderror"
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <span class="text-[#d70015] text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#86868b] mb-1.5 cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="is_active" value="1" class="w-11 h-6 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="border-t border-[#f0f0f2]">Batasan (Opsional)</div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="user_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Khusus Pengguna</span></label>
                        <select class="select-apple w-full" id="user_id" name="user_id">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nama }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="kategori_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Khusus Kategori</span></label>
                        <select class="select-apple w-full" id="kategori_id" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="produk_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Khusus Produk</span></label>
                        <select class="select-apple w-full" id="produk_id" name="produk_id">
                            <option value="">Semua Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('produk_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('admin.vouchers.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">Batal</a>
                    <button type="submit" class="btn-dark-apple">Simpan Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('kode_voucher').value = code;
}

function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const valueHint = document.getElementById('value_hint');
    const maxDiscountWrapper = document.getElementById('max_discount_wrapper');

    if (type === 'percentage') {
        valueHint.textContent = 'Dalam persen (%)';
        maxDiscountWrapper.style.display = 'block';
    } else if (type === 'fixed') {
        valueHint.textContent = 'Dalam Rupiah (Rp)';
        maxDiscountWrapper.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
@endpush
@endsection
