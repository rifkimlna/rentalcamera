<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Stekpro Multimedia & Broadcast</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Gooey blobs loader (gaya loaders-gooey-blobs, CSS murni) */
        .gooey-loader{display:inline-flex;align-items:center;gap:4px;filter:url(#gooey-loader-filter);vertical-align:middle}
        .gooey-loader>i{width:var(--gooey-dot,8px);height:var(--gooey-dot,8px);border-radius:9999px;background:var(--gooey-color,currentColor);animation:gooey-blobs-x 1.5s ease-in-out infinite}
        .gooey-loader>i:nth-child(2){animation-delay:.2s}
        .gooey-loader>i:nth-child(3){animation-delay:.4s}
        @keyframes gooey-blobs-x{0%,100%{transform:translateX(0) scale(1)}25%{transform:translateX(var(--gooey-shift,5px)) scale(1.2)}50%{transform:translateX(0) scale(1)}75%{transform:translateX(calc(var(--gooey-shift,5px) * -1)) scale(1.2)}}
        @media (prefers-reduced-motion:reduce){.gooey-loader>i{animation:none}}
    </style>
    <meta name="user-id" content="{{ auth()->id() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#f5f5f7] min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Filter goo untuk gooey-loader (definisi global, disembunyikan) --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs><filter id="gooey-loader-filter"><feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur"/><feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="gooey"/><feBlend in="SourceGraphic" in2="gooey"/></filter></defs></svg>

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/30 z-40 lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="flex min-h-screen">

        {{-- SIDEBAR (ukuran & struktur disamakan dengan admin: w-64) --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed top-0 left-0 bottom-0 w-64 bg-white border-r border-[#e5e5e7] z-50 transition-transform duration-200 ease-out overflow-y-auto flex flex-col lg:sticky lg:top-0 lg:h-screen">

            {{-- Sidebar Header --}}
            <div class="px-5 py-5 border-b border-[#f0f0f2]">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-8 w-auto object-contain">
                    <div>
                        <p class="text-sm font-semibold text-[#1d1d1f]">Stekpro</p>
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider">Customer Panel</p>
                    </div>
                </a>
            </div>

            {{-- Sidebar Nav --}}
            <nav class="flex-1 px-3 py-4">
                <ul class="space-y-0.5">
                    <li>
                            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('customer.dashboard') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('customer.transactions.*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                Transaksi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('customer.products.*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                Equipment
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.studio.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('customer.studio.*') && !request()->routeIs('customer.studio.my-bookings') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Studio
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.layanan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('customer.layanan.*') && !request()->routeIs('customer.layanan.my-bookings') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                Layanan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.reviews.available') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('customer.reviews.*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Ulasan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('profile') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil
                            </a>
                        </li>
                    </ul>

                <div class="border-t border-[#f0f0f2] my-4"></div>

                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f] transition-colors">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Kembali ke Beranda
                            </a>
                        </li>
                        @auth
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#d70015] hover:bg-red-50 w-full transition-colors">
                                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#1d1d1f] font-medium hover:bg-[#f5f5f7] transition-colors">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Masuk / Daftar
                            </a>
                        </li>
                        @endauth
                </ul>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-[#f0f0f2]">
                <div class="flex items-center h-14 px-4 sm:px-6 lg:px-8">
                    {{-- Mobile menu button --}}
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 mr-2">
                        <svg class="w-5 h-5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    {{-- Mobile logo --}}
                    <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2 mr-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-7 w-auto object-contain">
                    </a>

                    {{-- Page title --}}
                    <div class="flex-1 min-w-0">
                        <span class="text-sm font-semibold text-[#1d1d1f]">@yield('page-title', 'Dashboard')</span>
                    </div>

                    {{-- Right actions --}}
                    <div class="flex items-center gap-2">
                        @auth
                            <div class="hidden lg:flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                                </div>
                                <span class="text-sm text-[#6e6e73]">{{ auth()->user()->nama }}</span>
                            </div>
                        @else
                            <div class="hidden lg:flex items-center gap-2">
                                <a href="{{ route('login') }}" class="text-sm font-medium text-[#1d1d1f] hover:text-[#6e6e73] transition-colors px-3 py-2">Masuk</a>
                                <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-[#1d1d1f] hover:bg-[#333] rounded-xl px-4 py-2 transition-colors">Daftar</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto">

                {{-- Flash Messages --}}
                <x-flash-messages />

                @yield('content')
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts (banner error validasi tetap tampil)
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]:not([data-persistent])').forEach(function(el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 300);
            });
        }, 5000);

        // Date inputs
        document.querySelectorAll('.datepicker').forEach(function(el) { el.setAttribute('type', 'date'); });

        // Rental date calculation
        document.querySelectorAll('.rental-date').forEach(function(el) {
            el.addEventListener('change', function() {
                var start = document.getElementById('tanggal_sewa');
                var end = document.getElementById('tanggal_kembali');
                if (!start || !end) return;
                var s = new Date(start.value);
                var e = new Date(end.value);
                if (s && e && e > s) {
                    var diff = Math.ceil(Math.abs(e - s) / (1000 * 60 * 60 * 24));
                    var lama = document.getElementById('lama_sewa');
                    var display = document.getElementById('lama_sewa_display');
                    if (lama) lama.value = diff;
                    if (display) display.textContent = diff + ' hari';
                }
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
