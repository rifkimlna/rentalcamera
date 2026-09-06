@extends('layouts.admin')

@section('title', 'Tambah Paket - ' . $layanan->nama_layanan)
@section('page-title', 'Tambah Paket: ' . $layanan->nama_layanan)

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2] max-w-2xl">
    <div class="p-5">
        <form method="POST" action="{{ route('admin.layanan.paket.store', $layanan->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Paket *</span></label>
                <input type="text" name="nama_paket" class="input-apple w-full" value="{{ old('nama_paket') }}" required>
                @error('nama_paket') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" class="input-apple resize-none w-full" rows="3">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga Paket *</span></label>
                    <input type="number" name="harga" class="input-apple w-full" value="{{ old('harga') }}" required>
                    @error('harga') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Durasi (jam) *</span></label>
                    <input type="number" name="durasi_jam" class="input-apple w-full" value="{{ old('durasi_jam', 2) }}" required min="1">
                    @error('durasi_jam') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Include (pisahkan dengan koma)</span></label>
                <textarea name="include" class="input-apple resize-none w-full" rows="3" placeholder="Kamera, Videografer, Editor, ...">{{ old('include') }}</textarea>
                @error('include') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar</span></label>
                <input type="file" name="gambar" class="input-apple w-full" accept="image/*">
                @error('gambar') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" class="select-apple w-full">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="text-right">
                <a href="{{ route('admin.layanan.paket.index', $layanan->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors mr-2">Batal</a>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
