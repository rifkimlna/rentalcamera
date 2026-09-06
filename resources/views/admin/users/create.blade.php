@extends('layouts.admin')

@section('title', 'Tambah User Baru')
@section('page-title', 'Tambah User Baru')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Tambah User Baru</h1>
        <a href="{{ route('admin.users.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Lengkap *</span></label>
                        <input type="text" name="nama" id="nama" class="input-apple w-full @error('nama') input-error @enderror" value="{{ old('nama') }}" required>
                        @error('nama')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Email *</span></label>
                        <input type="email" name="email" id="email" class="input-apple w-full @error('email') input-error @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Password *</span></label>
                        <input type="password" name="password" id="password" class="input-apple w-full @error('password') input-error @enderror" required>
                        @error('password')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Konfirmasi Password *</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-apple w-full @error('password_confirmation') input-error @enderror" required>
                        @error('password_confirmation')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="telepon" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Telepon *</span></label>
                        <input type="text" name="telepon" id="telepon" class="input-apple w-full @error('telepon') input-error @enderror" value="{{ old('telepon') }}" required>
                        @error('telepon')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="role" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Role *</span></label>
                            <select name="role" id="role" class="select-apple w-full @error('role') select-error @enderror" required>
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="status" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status *</span></label>
                            <select name="status" id="status" class="select-apple w-full @error('status') select-error @enderror" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')<span class="text-[#d70015] text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn-dark-apple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
