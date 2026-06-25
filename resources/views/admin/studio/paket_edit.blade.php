@extends('layouts.admin')

@section('title', 'Edit Paket - ' . $paket->nama_paket)
@section('page-title', 'Edit Paket: ' . $paket->nama_paket)

@section('content')
<div class="card bg-base-100 shadow-md max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.studio.paket.update', [$studio->id, $paket->id]) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="label"><span class="label-text">Nama Paket *</span></label>
                <input type="text" name="nama_paket" class="input input-bordered w-full" value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                @error('nama_paket') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Deskripsi</span></label>
                <textarea name="deskripsi" class="textarea textarea-bordered w-full" rows="3">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                @error('deskripsi') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="label"><span class="label-text">Harga Paket *</span></label>
                    <input type="number" name="harga" class="input input-bordered w-full" value="{{ old('harga', $paket->harga) }}" required>
                    @error('harga') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="label"><span class="label-text">Durasi (jam) *</span></label>
                    <input type="number" name="durasi_jam" class="input input-bordered w-full" value="{{ old('durasi_jam', $paket->durasi_jam) }}" required min="1">
                    @error('durasi_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Include Alat (pisahkan dengan koma)</span></label>
                <textarea name="include_alat" class="textarea textarea-bordered w-full" rows="3" placeholder="Mic, Kamera, Lighting, ...">{{ old('include_alat', $paket->include_alat) }}</textarea>
                @error('include_alat') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Gambar</span></label>
                @if($paket->gambar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $paket->gambar) }}" class="w-24 h-24 object-cover rounded">
                    </div>
                @endif
                <input type="file" name="gambar" class="file-input file-input-bordered w-full" accept="image/*">
                @error('gambar') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Status</span></label>
                <select name="status" class="select select-bordered w-full">
                    <option value="active" {{ old('status', $paket->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $paket->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
