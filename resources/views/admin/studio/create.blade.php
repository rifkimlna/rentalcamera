@extends('layouts.admin')

@section('title', 'Tambah Studio')
@section('page-title', 'Tambah Studio')

@section('content')
<div class="card bg-base-100 shadow-md max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.studio.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="label"><span class="label-text">Nama Studio *</span></label>
                <input type="text" name="nama_studio" class="input input-bordered w-full" value="{{ old('nama_studio') }}" required>
                @error('nama_studio') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Deskripsi</span></label>
                <textarea name="deskripsi" class="textarea textarea-bordered w-full" rows="4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Fasilitas (pisahkan dengan koma)</span></label>
                <textarea name="fasilitas" class="textarea textarea-bordered w-full" rows="3" placeholder="AC, Sound System, Green Screen, ...">{{ old('fasilitas') }}</textarea>
                @error('fasilitas') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Harga per Jam *</span></label>
                <input type="number" name="harga_per_jam" class="input input-bordered w-full" value="{{ old('harga_per_jam') }}" required>
                @error('harga_per_jam') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Gambar Utama</span></label>
                <input type="file" name="gambar_utama" class="file-input file-input-bordered w-full" accept="image/*">
                @error('gambar_utama') <span class="text-error text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="label"><span class="label-text">Status</span></label>
                <select name="status" class="select select-bordered w-full">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
