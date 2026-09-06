<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stekpro Multimedia & Broadcast')</title>
    <meta name="description" content="@yield('meta-description', 'Stekpro Multimedia & Broadcast - Sewa equipment, studio, dan layanan kreatif profesional di Sukabumi. Gear terawat, studio lengkap, tim profesional.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Stekpro Multimedia & Broadcast">
    <meta property="og:title" content="@yield('title', 'Stekpro Multimedia & Broadcast')">
    <meta property="og:description" content="@yield('meta-description', 'Sewa equipment, studio, dan layanan kreatif profesional.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title', 'Stekpro Multimedia & Broadcast')">
    <meta name="twitter:description" content="@yield('meta-description', 'Sewa equipment, studio, dan layanan kreatif profesional.')">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Gooey blobs loader (gaya loaders-gooey-blobs, CSS murni) */
        .gooey-loader{display:inline-flex;align-items:center;gap:4px;filter:url(#gooey-loader-filter);vertical-align:middle}
        .gooey-loader>i{width:var(--gooey-dot,8px);height:var(--gooey-dot,8px);border-radius:9999px;background:var(--gooey-color,currentColor);animation:gooey-blobs-x 1.5s ease-in-out infinite}
        .gooey-loader>i:nth-child(2){animation-delay:.2s}
        .gooey-loader>i:nth-child(3){animation-delay:.4s}
        @keyframes gooey-blobs-x{0%,100%{transform:translateX(0) scale(1)}25%{transform:translateX(var(--gooey-shift,5px)) scale(1.2)}50%{transform:translateX(0) scale(1)}75%{transform:translateX(calc(var(--gooey-shift,5px) * -1)) scale(1.2)}}
        @media (prefers-reduced-motion:reduce){.gooey-loader>i{animation:none}}
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-neutral-900 min-h-screen flex flex-col" x-data="{ mobileOpen: false }">

    {{-- Filter goo untuk gooey-loader (definisi global, disembunyikan) --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs><filter id="gooey-loader-filter"><feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur"/><feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="gooey"/><feBlend in="SourceGraphic" in2="gooey"/></filter></defs></svg>

    {{-- ================= NAVBAR : sticky glass ================= --}}
    <header class="fixed top-0 left-0 right-0 z-50 glass border-b border-white/40" x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 12 })" :class="{ 'shadow-[0_8px_30px_-12px_rgba(15,23,42,0.15)]': scrolled }">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-[72px] gap-3">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Stekpro" class="h-9 w-auto object-contain p-1 group-hover:scale-105 transition-transform">
                    @else
                        <span class="font-extrabold text-lg text-black">S</span>
                    @endif
                    <span class="leading-tight">
                        <span class="block text-[15px] font-extrabold tracking-tight text-slate-900">Stekpro</span>
                        <span class="block text-[10px] font-medium tracking-widest uppercase text-slate-400">Creative Rental</span>
                    </span>
                </a>

                {{-- Desktop Navigation : menu awal (Beranda, Sewa, Harga, Tentang, Kontak) --}}
                <div class="hidden lg:flex items-center">
                    <div class="flex items-center gap-1 p-1.5 rounded-full bg-white/70 border border-slate-200/70 backdrop-blur-md shadow-sm">
                        <a href="{{ route('home') }}" class="px-4 py-2 text-sm rounded-full transition-all duration-200 {{ request()->routeIs('home') ? 'bg-[#111111] text-white font-semibold shadow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">Beranda</a>
                        {{-- Sewa dropdown: tamu boleh lihat Equipment / Studio / Layanan, sewa wajib login --}}
                        <div class="relative" x-data="{ sewaOpen: false }" @click.outside="sewaOpen = false" @keydown.escape.window="sewaOpen = false">
                            <button type="button" @click="sewaOpen = !sewaOpen" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm rounded-full transition-all duration-200 {{ request()->routeIs(['customer.*']) ? 'bg-[#111111] text-white font-semibold shadow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                                Sewa
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': sewaOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="sewaOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute left-1/2 -translate-x-1/2 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-2 z-50 overflow-hidden">
                                <a href="{{ route('customer.dashboard') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-slate-700 hover:bg-neutral-100 hover:text-[#111111] transition-colors {{ request()->routeIs('customer.dashboard') ? 'font-semibold text-[#111111] bg-neutral-100' : '' }}">Semua Katalog</a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="{{ route('customer.products.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-slate-700 hover:bg-neutral-100 hover:text-[#111111] transition-colors {{ request()->routeIs('customer.products.*') ? 'font-semibold text-[#111111] bg-neutral-100' : '' }}">Equipment</a>
                                <a href="{{ route('customer.studio.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-slate-700 hover:bg-neutral-100 hover:text-[#111111] transition-colors {{ request()->routeIs('customer.studio.*') ? 'font-semibold text-[#111111] bg-neutral-100' : '' }}">Studio</a>
                                <a href="{{ route('customer.layanan.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-slate-700 hover:bg-neutral-100 hover:text-[#111111] transition-colors {{ request()->routeIs('customer.layanan.*') ? 'font-semibold text-[#111111] bg-neutral-100' : '' }}">Layanan</a>
                                @guest
                                <div class="border-t border-slate-100 mt-1 pt-2 px-4 pb-1">
                                    <p class="text-[11px] text-slate-400">Lihat-lihat bebas, login untuk sewa.</p>
                                </div>
                                @endguest
                            </div>
                        </div>
                        <a href="{{ route('pricing') }}" class="px-4 py-2 text-sm rounded-full transition-all duration-200 {{ request()->routeIs('pricing') ? 'bg-[#111111] text-white font-semibold shadow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">Harga</a>
                        <a href="{{ route('about') }}" class="px-4 py-2 text-sm rounded-full transition-all duration-200 {{ request()->routeIs('about') ? 'bg-[#111111] text-white font-semibold shadow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">Tentang</a>
                        <a href="{{ route('contact') }}" class="px-4 py-2 text-sm rounded-full transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-[#111111] text-white font-semibold shadow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">Kontak</a>
                    </div>
                </div>

                {{-- Desktop Auth + CTA --}}
                <div class="hidden lg:flex items-center gap-2.5">
                    @auth
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-full bg-white border border-slate-200 hover:border-slate-300 hover:shadow-sm transition-all">
                                <div class="w-8 h-8 rounded-full bg-[#111111] text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-800 hidden xl:block max-w-[110px] truncate">{{ auth()->user()->nama }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-60 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-200/80 py-2 z-50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-slate-100 mb-1 bg-slate-50/60">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->nama }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-neutral-100 hover:text-[#111111] transition-colors">Dashboard</a>
                                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-neutral-100 hover:text-[#111111] transition-colors">Profil</a>
                                <div class="border-t border-slate-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 w-full transition-colors">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-[#111111] transition-colors px-3 py-2">Masuk</a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#111111] hover:bg-[#000000] text-white text-sm font-semibold shadow-[0_8px_20px_-6px_rgba(0,0,0,0.55)] hover:shadow-[0_12px_24px_-6px_rgba(0,0,0,0.6)] hover:-translate-y-px active:translate-y-0 transition-all duration-200">
                            Daftar
                        </a>
                    @endauth
                </div>

                {{-- Mobile buttons --}}
                <div class="lg:hidden flex items-center gap-1">
                    <button @click="mobileOpen = !mobileOpen" class="p-2.5 rounded-xl hover:bg-slate-100 transition-colors" aria-label="Menu">
                        <svg x-show="!mobileOpen" class="w-6 h-6 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Mobile Menu : menu awal --}}
        <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden glass-strong border-t border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('home') ? 'bg-neutral-100 text-[#111111]' : 'text-slate-600 hover:bg-slate-100' }}">Beranda <span>→</span></a>
                {{-- Sewa group: tamu bisa lihat Equipment / Studio / Layanan --}}
                <div class="rounded-xl overflow-hidden {{ request()->routeIs(['customer.*']) ? 'bg-neutral-100' : '' }}">
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('customer.dashboard') ? 'text-[#111111]' : 'text-slate-600 hover:bg-slate-100' }}">Sewa</a>
                    <div class="pl-4 pr-2 pb-2 space-y-1">
                        <a href="{{ route('customer.products.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('customer.products.*') ? 'bg-white text-[#111111] font-semibold shadow-sm' : 'text-slate-500 hover:bg-white' }}">Equipment</a>
                        <a href="{{ route('customer.studio.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('customer.studio.*') ? 'bg-white text-[#111111] font-semibold shadow-sm' : 'text-slate-500 hover:bg-white' }}">Studio</a>
                        <a href="{{ route('customer.layanan.index') }}" class="flex items-center justify-between px-4 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('customer.layanan.*') ? 'bg-white text-[#111111] font-semibold shadow-sm' : 'text-slate-500 hover:bg-white' }}">Layanan</a>
                    </div>
                </div>
                <a href="{{ route('pricing') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('pricing') ? 'bg-neutral-100 text-[#111111]' : 'text-slate-600 hover:bg-slate-100' }}">Harga <span>→</span></a>
                <a href="{{ route('about') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('about') ? 'bg-neutral-100 text-[#111111]' : 'text-slate-600 hover:bg-slate-100' }}">Tentang <span>→</span></a>
                <a href="{{ route('contact') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('contact') ? 'bg-neutral-100 text-[#111111]' : 'text-slate-600 hover:bg-slate-100' }}">Kontak <span>→</span></a>
                <div class="border-t border-slate-200/70 my-2"></div>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-100 rounded-xl">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-xl">Keluar</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('login') }}" class="px-4 py-3 text-sm font-semibold text-slate-800 bg-white border border-slate-200 rounded-xl text-center">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-3 text-sm font-semibold text-white bg-[#111111] rounded-xl text-center">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <div class="h-16 lg:h-[72px]"></div>

    <main class="flex-1 w-full flex flex-col">
        @hasSection('flash')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 w-full">
                <x-flash-messages />
            </div>
        @else
            @if((session()->has('success') || session()->has('error') || $errors->any()) && !request()->routeIs('login'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 w-full">
                    <x-flash-messages />
                </div>
            @endif
        @endif
        @yield('content')
    </main>

    {{-- ================= FOOTER : clean white / light grey ================= --}}
    <footer class="bg-white mt-0">
        {{-- CTA strip --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
            <div class="relative overflow-hidden rounded-3xl bg-black px-6 py-10 sm:p-12 text-center shadow-[0_24px_60px_-20px_rgba(0,0,0,0.5)]">
                <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -right-16 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
                <div class="relative">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/15 border border-white/25 text-white text-xs font-semibold backdrop-blur-md mb-4">Siap produksi hari ini?</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-3">Wujudkan karya terbaikmu<br class="hidden sm:block"> bersama Stekpro.</h2>
                    <p class="text-sm sm:text-base text-neutral-300 mb-7 max-w-xl mx-auto">Gear terawat, studio lengkap, dan tim profesional — booking dalam hitungan menit, langsung siap shooting.</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('customer.products.index') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-white text-[#111111] text-sm font-bold hover:bg-neutral-100 hover:-translate-y-0.5 transition-all shadow-lg">Booking Sekarang</a>
                        <a href="https://wa.me/6281234567890?text=Halo%20Stekpro,%20saya%20mau%20konsultasi%20sewa" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full border border-white/40 text-white text-sm font-semibold hover:bg-white/10 backdrop-blur-md transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
                {{-- Brand --}}
                <div class="col-span-2 lg:col-span-4">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="w-9 h-9 rounded-xl bg-[#111111] text-white flex items-center justify-center font-extrabold text-lg">S</span>
                        <span class="leading-tight">
                            <span class="block text-[15px] font-extrabold tracking-tight text-slate-900">Stekpro</span>
                            <span class="block text-[10px] font-medium tracking-widest uppercase text-slate-400">Multimedia & Broadcast</span>
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-xs mb-5">Platform sewa equipment, studio, dan layanan kreatif profesional. Gear terawat, harga transparan, booking super cepat.</p>
                    <div class="flex items-center gap-2">
                        <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-[#111111] hover:text-white text-slate-500 flex items-center justify-center transition-all hover:-translate-y-0.5">
                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="2.5" y="2.5" width="19" height="19" rx="5.5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.6" cy="6.4" r="1.3" fill="currentColor" stroke="none"/></svg>
                        </a>
                        <a href="#" aria-label="YouTube" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-[#111111] hover:text-white text-slate-500 flex items-center justify-center transition-all hover:-translate-y-0.5">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M23 12s0-3.846-.493-5.637a2.9 2.9 0 00-2.04-2.04C18.676 3.83 12 3.83 12 3.83s-6.676 0-8.467.493a2.9 2.9 0 00-2.04 2.04C1 8.154 1 12 1 12s0 3.846.493 5.637a2.9 2.9 0 002.04 2.04c1.791.493 8.467.493 8.467.493s6.676 0 8.467-.493a2.9 2.9 0 002.04-2.04C23 15.846 23 12 23 12zM9.75 15.5v-7L15.5 12l-5.75 3.5z"/></svg>
                        </a>
                        <a href="#" aria-label="TikTok" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-[#111111] hover:text-white text-slate-500 flex items-center justify-center transition-all hover:-translate-y-0.5">
                            <svg class="w-[17px] h-[17px]" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.9 2.9 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64c.3 0 .58.05.85.13V9.4a6.33 6.33 0 00-5.38 10.4 6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-[#22C55E] hover:text-white text-slate-500 flex items-center justify-center transition-all hover:-translate-y-0.5">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Katalog --}}
                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Katalog</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('customer.products.index') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Equipment</a></li>
                        <li><a href="{{ route('customer.studio.index') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Studio</a></li>
                        <li><a href="{{ route('customer.layanan.index') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Layanan Kreatif</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Harga</a></li>
                    </ul>
                </div>

                {{-- Bantuan --}}
                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Bantuan</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ url('/#cara-sewa') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Cara Sewa</a></li>
                        <li><a href="{{ route('faq') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">FAQ</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-slate-500 hover:text-[#111111] transition-colors">Kontak</a></li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div class="col-span-2 lg:col-span-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl bg-green-50 border border-green-200/70 text-green-700 font-semibold hover:bg-green-100 hover:-translate-y-px transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                +62 812-3456-7890
                            </a>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Senin – Sabtu, 09.00 – 20.00 WIB<br><span class="text-slate-400">Minggu & libur nasional: 10.00 – 17.00</span></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Sukabumi, Jawa Barat, Indonesia
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200/70 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Stekpro Multimedia & Broadcast. All rights reserved.</p>
                <div class="flex items-center gap-5">
                    <a href="#" class="text-xs text-slate-400 hover:text-slate-700 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-xs text-slate-400 hover:text-slate-700 transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- WhatsApp floating --}}
    <a href="https://wa.me/6281234567890?text=Halo%20Stekpro,%20saya%20mau%20tanya%20sewa" target="_blank" rel="noopener" aria-label="Chat WhatsApp"
       class="whatsapp-btn fixed bottom-5 right-5 z-50 w-14 h-14 rounded-full bg-[#22C55E] text-white flex items-center justify-center shadow-[0_12px_30px_-6px_rgba(34,197,94,0.6)] hover:bg-[#16A34A] hover:scale-105 active:scale-95 transition-all">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const revealElements = document.querySelectorAll('.reveal');
        if (revealElements.length > 0) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
            revealElements.forEach(el => observer.observe(el));
        }
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]:not([data-persistent])').forEach(function(el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 300);
            });
        }, 5000);
    });
    </script>
    @stack('scripts')
</body>
</html>
