@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-base-100 border border-base-300 rounded-box p-6">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-1">Lupa Password</p>
        <h1 class="text-2xl font-light tracking-tight mb-1">Lupa Password</h1>
        <p class="text-sm text-base-content/50 mb-6">Masukkan email Anda dan kami akan mengirimkan tautan reset password.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" class="input input-bordered w-full @error('email') input-error @enderror" value="{{ old('email') }}" required placeholder="nama@example.com">
                @error('email')
                <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-neutral w-full mb-4">Kirim Link Reset</button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm link link-hover">Kembali ke halaman login</a>
            </div>
        </form>
    </div>
</div>
@endsection
