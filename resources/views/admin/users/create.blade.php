@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Tambah User Baru</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="label"><span class="label-text">Nama Lengkap *</span></label>
                        <input type="text" name="nama" id="nama" class="input input-bordered w-full @error('nama') input-error @enderror" value="{{ old('nama') }}" required>
                        @error('nama')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="email" class="label"><span class="label-text">Email *</span></label>
                        <input type="email" name="email" id="email" class="input input-bordered w-full @error('email') input-error @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="password" class="label"><span class="label-text">Password *</span></label>
                        <input type="password" name="password" id="password" class="input input-bordered w-full @error('password') input-error @enderror" required>
                        @error('password')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="label"><span class="label-text">Konfirmasi Password *</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input input-bordered w-full @error('password_confirmation') input-error @enderror" required>
                        @error('password_confirmation')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="telepon" class="label"><span class="label-text">Telepon *</span></label>
                        <input type="text" name="telepon" id="telepon" class="input input-bordered w-full @error('telepon') input-error @enderror" value="{{ old('telepon') }}" required>
                        @error('telepon')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="role" class="label"><span class="label-text">Role *</span></label>
                            <select name="role" id="role" class="select select-bordered w-full @error('role') select-error @enderror" required>
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="status" class="label"><span class="label-text">Status *</span></label>
                            <select name="status" id="status" class="select select-bordered w-full @error('status') select-error @enderror" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
