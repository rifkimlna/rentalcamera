@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-10 bg-white">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8 sm:p-10">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-12 w-auto mx-auto mb-3 object-contain">
                <h1 class="text-xl sm:text-2xl font-bold text-[#1d1d1f]">Lupa Password</h1>
                <p class="text-[#6e6e73] text-sm mt-1.5">Verifikasi identitas dengan email dan nomor HP terdaftar, lalu buat password baru.</p>
            </div>

            @if(session()->has('pwd_reset_user_id'))
                <form method="POST" action="{{ route('password.phone.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="password" class="block text-xs font-medium text-[#86868b] mb-1.5">Password Baru</label>
                        <input type="password" id="password" name="password" class="input-apple w-full @error('password') text-[#d70015] border-[#d70015] @enderror" required placeholder="Minimal 6 karakter">
                        @error('password')
                        <p class="text-xs text-[#d70015] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-xs font-medium text-[#86868b] mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input-apple w-full" required placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn-dark-apple w-full mb-4">Simpan Password Baru</button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-[#0071e3] hover:underline">Kembali ke halaman login</a>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('password.phone.verify') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email_hp" class="block text-xs font-medium text-[#86868b] mb-1.5">Email Terdaftar</label>
                        <input type="email" id="email_hp" name="email" class="input-apple w-full" value="{{ old('email') }}" required placeholder="nama@example.com">
                    </div>

                    <div class="mb-4">
                        <label for="telepon" class="block text-xs font-medium text-[#86868b] mb-1.5">Nomor HP Terdaftar</label>
                        <input type="tel" id="telepon" name="telepon" class="input-apple w-full @error('telepon') text-[#d70015] border-[#d70015] @enderror" value="{{ old('telepon') }}" required placeholder="08xxxxxxxxxx">
                        @error('telepon')
                        <p class="text-xs text-[#d70015] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-outline-apple w-full mb-4">Verifikasi & Buat Password Baru</button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-[#0071e3] hover:underline">Kembali ke halaman login</a>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
