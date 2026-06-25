@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-base-100 border border-base-300 rounded-box p-6">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-1">Reset Password</p>
        <h1 class="text-2xl font-light tracking-tight mb-1">Reset Password</h1>
        <p class="text-sm text-base-content/50 mb-6">Buat password baru untuk akun Anda.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" class="input input-bordered w-full @error('email') input-error @enderror" value="{{ old('email', $email ?? '') }}" readonly required placeholder="nama@example.com">
                @error('email')
                <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-1">Password Baru</label>
                <input type="password" id="password" name="password" class="input input-bordered w-full @error('password') input-error @enderror" required placeholder="Minimal 6 karakter">
                @error('password')
                <p class="text-xs text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="input input-bordered w-full" required placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-neutral w-full">Reset Password</button>
        </form>
    </div>
</div>
@endsection
