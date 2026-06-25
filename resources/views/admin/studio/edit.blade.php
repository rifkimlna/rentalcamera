@extends('layouts.admin')

@section('title', 'Edit Studio')
@section('page-title', 'Edit Studio')

@section('content')
<div class="card bg-base-100 shadow-md max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.studio.update', $studio->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="label"><span class="label-text">Nama Studio *</span></label>
                <input type="text" name="nama_studio" class="input input-bordered w-full" value="{{ old('nama_studio', $studio->nama_studio) }}" required>
                @error('nama_studio') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Deskripsi</span></label>
                <textarea name="deskripsi" class="textarea textarea-bordered w-full" rows="4">{{ old('deskripsi', $studio->deskripsi) }}</textarea>
                @error('deskripsi') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Fasilitas (pisahkan dengan koma)</span></label>
                <textarea name="fasilitas" class="textarea textarea-bordered w-full" rows="3" placeholder="AC, Sound System, Green Screen, ...">{{ old('fasilitas', $studio->fasilitas) }}</textarea>
                @error('fasilitas') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Harga per Jam *</span></label>
                <input type="number" name="harga_per_jam" class="input input-bordered w-full" value="{{ old('harga_per_jam', $studio->harga_per_jam) }}" required>
                @error('harga_per_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Gambar Utama</span></label>
                @if($studio->gambar_utama)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $studio->gambar_utama) }}" class="w-24 h-24 object-cover rounded">
                    </div>
                @endif
                <input type="file" name="gambar_utama" class="file-input file-input-bordered w-full" accept="image/*">
                @error('gambar_utama') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Status</span></label>
                <select name="status" class="select select-bordered w-full">
                    <option value="active" {{ old('status', $studio->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $studio->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="text-right">
                <a href="{{ route('admin.studio.index') }}" class="btn btn-ghost mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
