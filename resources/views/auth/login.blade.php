@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 min-h-screen bg-[#f5f5f7]">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8 sm:p-10">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-12 w-auto mx-auto mb-3 object-contain">
                <h1 class="text-xl sm:text-2xl font-bold text-[#1d1d1f]">Stekpro Multimedia & Broadcast</h1>
                <p class="text-[#6e6e73] text-sm mt-1.5">Masuk ke akun Anda</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <input type="email"
                           class="input-apple w-full @error('email') text-[#d70015] border-[#d70015] @enderror"
                           id="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com">
                    @error('email')
                        <span class="text-[#d70015] text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="relative">
                        <input type="password"
                               class="input-apple w-full pr-10 @error('password') text-[#d70015] border-[#d70015] @enderror"
                               id="password" name="password" required placeholder="Masukkan password">
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 text-[#86868b] hover:text-[#1d1d1f] transition-colors p-1 rounded-lg"
                                type="button" id="togglePassword">
                            <svg class="h-4 w-4 eye-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg class="h-4 w-4 eye-icon-closed hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-[#d70015] text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <label class="flex items-center gap-2 mb-4 cursor-pointer">
                    <input type="checkbox" class="w-4 h-4 rounded border-[#d1d5db] text-[#0071e3] focus:ring-[#0071e3]" id="remember" name="remember">
                    <span class="text-sm text-[#6e6e73]">Ingat saya</span>
                </label>

                <button type="submit" class="btn-dark-apple w-full">
                    Masuk
                </button>

                <div class="border-t border-[#f0f0f2] text-xs text-[#86868b] my-5 text-center">atau</div>

                <a href="{{ route('auth.google') }}" class="btn-outline-apple w-full flex items-center justify-center gap-2 mb-4">
                    <svg class="h-5 w-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>

                <div class="text-center text-sm">
                    <span class="text-[#6e6e73]">Belum punya akun? </span>
                    <a href="{{ route('register') }}" class="text-[#1d1d1f] font-medium hover:underline">Daftar</a>
                </div>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">
                &larr; Kembali ke beranda
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const openIcon = this.querySelector('.eye-icon-open');
        const closedIcon = this.querySelector('.eye-icon-closed');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            openIcon.classList.add('hidden');
            closedIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            openIcon.classList.remove('hidden');
            closedIcon.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
