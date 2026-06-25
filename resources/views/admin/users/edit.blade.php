@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Edit Pengguna</h1>
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="label"><span class="label-text">Nama Lengkap *</span></label>
                        <input type="text" name="nama" id="nama" class="input input-bordered w-full @error('nama') input-error @enderror" value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="email" class="label"><span class="label-text">Email *</span></label>
                        <input type="email" name="email" id="email" class="input input-bordered w-full @error('email') input-error @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="telepon" class="label"><span class="label-text">Telepon</span></label>
                        <input type="text" name="telepon" id="telepon" class="input input-bordered w-full @error('telepon') input-error @enderror" value="{{ old('telepon', $user->telepon) }}">
                        @error('telepon')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="password" class="label"><span class="label-text">Password (Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" id="password" class="input input-bordered w-full @error('password') input-error @enderror">
                        @error('password')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="role" class="label"><span class="label-text">Role *</span></label>
                        <select name="role" id="role" class="select select-bordered w-full @error('role') select-error @enderror" required>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="status" class="label"><span class="label-text">Status *</span></label>
                        <select name="status" id="status" class="select select-bordered w-full @error('status') select-error @enderror" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $user->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="label"><span class="label-text">Tanggal Lahir</span></label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="input input-bordered w-full @error('tanggal_lahir') input-error @enderror" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                        @error('tanggal_lahir')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="label"><span class="label-text">Jenis Kelamin</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="select select-bordered w-full @error('jenis_kelamin') select-error @enderror">
                            <option value="">Pilih...</option>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="alamat" class="label"><span class="label-text">Alamat</span></label>
                    <textarea name="alamat" id="alamat" rows="3" class="textarea textarea-bordered w-full @error('alamat') textarea-error @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="kota" class="label"><span class="label-text">Kota</span></label>
                        <input type="text" name="kota" id="kota" class="input input-bordered w-full @error('kota') input-error @enderror" value="{{ old('kota', $user->kota) }}">
                        @error('kota')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="provinsi" class="label"><span class="label-text">Provinsi</span></label>
                        <input type="text" name="provinsi" id="provinsi" class="input input-bordered w-full @error('provinsi') input-error @enderror" value="{{ old('provinsi', $user->provinsi) }}">
                        @error('provinsi')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="kode_pos" class="label"><span class="label-text">Kode Pos</span></label>
                        <input type="text" name="kode_pos" id="kode_pos" class="input input-bordered w-full @error('kode_pos') input-error @enderror" value="{{ old('kode_pos', $user->kode_pos) }}">
                        @error('kode_pos')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="mt-6 flex gap-4">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
