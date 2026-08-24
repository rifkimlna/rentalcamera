@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 min-h-screen bg-[#f5f5f7]">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8 sm:p-10">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-12 w-auto mx-auto mb-3 object-contain">
                <h1 class="text-xl sm:text-2xl font-bold text-[#1d1d1f]">Verifikasi Email Anda</h1>
                <p class="text-[#6e6e73] text-sm mt-1.5">Kami telah mengirimkan link verifikasi ke email Anda. Silakan klik link tersebut untuk memverifikasi akun Anda.</p>
            </div>

            @if (session('status'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0fdf4] border border-[#bbf7d0] text-[#16a34a] text-sm mb-4">
                {{ session('status') }}
            </div>
            @endif

            @if (session('resent'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0fdf4] border border-[#bbf7d0] text-[#16a34a] text-sm mb-4">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-dark-apple w-full mb-4">Kirim Ulang Link Verifikasi</button>
            </form>

            <div class="text-center">
                <a href="{{ route('logout') }}" class="text-sm text-[#0071e3] hover:underline"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
