@extends('layouts.admin')

@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')

@section('content')
<div class="bg-white rounded-2xl border border-[#f0f0f2] max-w-2xl">
    <div class="p-5">
        <form method="POST" action="{{ route('admin.layanan.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Layanan *</span></label>
                <input type="text" name="nama_layanan" class="input-apple w-full" value="{{ old('nama_layanan') }}" required>
                @error('nama_layanan') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" class="input-apple resize-none w-full" rows="4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kategori</span></label>
                <select name="kategori" class="select-apple w-full">
                    <option value="">Pilih Kategori</option>
                    <option value="Prewedding" {{ old('kategori') == 'Prewedding' ? 'selected' : '' }}>Prewedding</option>
                    <option value="Wedding" {{ old('kategori') == 'Wedding' ? 'selected' : '' }}>Wedding</option>
                    <option value="Live Streaming" {{ old('kategori') == 'Live Streaming' ? 'selected' : '' }}>Live Streaming</option>
                    <option value="Videografi" {{ old('kategori') == 'Videografi' ? 'selected' : '' }}>Videografi</option>
                    <option value="Dokumentasi Event" {{ old('kategori') == 'Dokumentasi Event' ? 'selected' : '' }}>Dokumentasi Event</option>
                    <option value="Fotobooth" {{ old('kategori') == 'Fotobooth' ? 'selected' : '' }}>Fotobooth</option>
                    <option value="Drone" {{ old('kategori') == 'Drone' ? 'selected' : '' }}>Drone</option>
                </select>
                @error('kategori') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga Mulai *</span></label>
                <input type="number" name="harga_mulai" class="input-apple w-full" value="{{ old('harga_mulai') }}" required>
                @error('harga_mulai') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Ikon (emoji/icon class)</span></label>
                <input type="text" name="ikon" class="input-apple w-full" value="{{ old('ikon') }}" placeholder="📸">
                @error('ikon') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar Utama</span></label>
                <input type="file" name="gambar_utama" class="input-apple w-full" accept="image/*">
                @error('gambar_utama') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" class="select-apple w-full">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="text-right">
                <a href="{{ route('admin.layanan.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors mr-2">Batal</a>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
