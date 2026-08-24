@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 min-h-screen bg-[#f5f5f7]">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8 sm:p-10">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-12 w-auto mx-auto mb-3 object-contain">
                <h1 class="text-xl sm:text-2xl font-bold text-[#1d1d1f]">Lupa Password</h1>
                <p class="text-[#6e6e73] text-sm mt-1.5">Masukkan email Anda dan kami akan mengirimkan tautan reset password.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-xs font-medium text-[#86868b] mb-1.5">Email</label>
                    <input type="email" id="email" name="email" class="input-apple w-full @error('email') text-[#d70015] border-[#d70015] @enderror" value="{{ old('email') }}" required placeholder="nama@example.com">
                    @error('email')
                    <p class="text-xs text-[#d70015] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-dark-apple w-full mb-4">Kirim Link Reset</button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#0071e3] hover:underline">Kembali ke halaman login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
