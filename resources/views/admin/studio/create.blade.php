@extends('layouts.admin')

@section('title', 'Tambah Studio')
@section('page-title', 'Tambah Studio')

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2] max-w-2xl">
    <div class="p-5">
        <form method="POST" action="{{ route('admin.studio.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Studio *</span></label>
                <input type="text" name="nama_studio" class="input-apple w-full" value="{{ old('nama_studio') }}" required>
                @error('nama_studio') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" class="input-apple resize-none w-full" rows="4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Fasilitas (pisahkan dengan koma)</span></label>
                <textarea name="fasilitas" class="input-apple resize-none w-full" rows="3" placeholder="AC, Sound System, Green Screen, ...">{{ old('fasilitas') }}</textarea>
                @error('fasilitas') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga per Jam *</span></label>
                <input type="number" name="harga_per_jam" class="input-apple w-full" value="{{ old('harga_per_jam') }}" required>
                @error('harga_per_jam') <span class="text-[#d70015] text-sm">{{ $message }}</span> @enderror
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
                <a href="{{ route('admin.studio.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors mr-2">Batal</a>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
