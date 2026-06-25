@extends('layouts.admin')

@section('title', 'Tambah Paket - ' . $studio->nama_studio)
@section('page-title', 'Tambah Paket: ' . $studio->nama_studio)

@section('content')
<div class="card bg-base-100 shadow-md max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.studio.paket.store', $studio->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="label"><span class="label-text">Nama Paket *</span></label>
                <input type="text" name="nama_paket" class="input input-bordered w-full" value="{{ old('nama_paket') }}" required>
                @error('nama_paket') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Deskripsi</span></label>
                <textarea name="deskripsi" class="textarea textarea-bordered w-full" rows="3">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="label"><span class="label-text">Harga Paket *</span></label>
                    <input type="number" name="harga" class="input input-bordered w-full" value="{{ old('harga') }}" required>
                    @error('harga') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="label"><span class="label-text">Durasi (jam) *</span></label>
                    <input type="number" name="durasi_jam" class="input input-bordered w-full" value="{{ old('durasi_jam', 2) }}" required min="1">
                    @error('durasi_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Include Alat (pisahkan dengan koma)</span></label>
                <textarea name="include_alat" class="textarea textarea-bordered w-full" rows="3" placeholder="Mic, Kamera, Lighting, ...">{{ old('include_alat') }}</textarea>
                @error('include_alat') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Gambar</span></label>
                <input type="file" name="gambar" class="file-input file-input-bordered w-full" accept="image/*">
                @error('gambar') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Status</span></label>
                <select name="status" class="select select-bordered w-full">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="text-right">
                <a href="{{ route('admin.studio.paket.index', $studio->id) }}" class="btn btn-ghost mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
