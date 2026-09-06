@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<x-flash-messages />
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Edit Pengguna</h1>
        <a href="{{ route('admin.users.show', $user->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Lengkap *</span></label>
                        <input type="text" name="nama" id="nama" class="input-apple w-full @error('nama') input-error @enderror" value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Email *</span></label>
                        <input type="email" name="email" id="email" class="input-apple w-full @error('email') input-error @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="telepon" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Telepon</span></label>
                        <input type="text" name="telepon" id="telepon" class="input-apple w-full @error('telepon') input-error @enderror" value="{{ old('telepon', $user->telepon) }}">
                        @error('telepon')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Password (Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" id="password" class="input-apple w-full @error('password') input-error @enderror">
                        @error('password')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="role" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Role *</span></label>
                        <select name="role" id="role" class="select-apple w-full @error('role') select-error @enderror" required>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status *</span></label>
                        <select name="status" id="status" class="select-apple w-full @error('status') select-error @enderror" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $user->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Lahir</span></label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="input-apple w-full @error('tanggal_lahir') input-error @enderror" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                        @error('tanggal_lahir')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Jenis Kelamin</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="select-apple w-full @error('jenis_kelamin') select-error @enderror">
                            <option value="">Pilih...</option>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="alamat" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Alamat</span></label>
                    <textarea name="alamat" id="alamat" rows="3" class="input-apple resize-none w-full @error('alamat') textarea-error @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="kota" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kota</span></label>
                        <input type="text" name="kota" id="kota" class="input-apple w-full @error('kota') input-error @enderror" value="{{ old('kota', $user->kota) }}">
                        @error('kota')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="provinsi" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Provinsi</span></label>
                        <input type="text" name="provinsi" id="provinsi" class="input-apple w-full @error('provinsi') input-error @enderror" value="{{ old('provinsi', $user->provinsi) }}">
                        @error('provinsi')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="kode_pos" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kode Pos</span></label>
                        <input type="text" name="kode_pos" id="kode_pos" class="input-apple w-full @error('kode_pos') input-error @enderror" value="{{ old('kode_pos', $user->kode_pos) }}">
                        @error('kode_pos')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="mt-6 flex gap-4">
                    <button type="submit" class="btn-dark-apple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
