<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <title>@yield('title', 'Admin - Stekpro Multimedia & Broadcast')</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#f5f5f7] min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/30 z-40 lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed top-0 left-0 bottom-0 w-64 bg-white border-r border-[#e5e5e7] z-50 transition-transform duration-200 ease-out overflow-y-auto flex flex-col lg:sticky lg:top-0 lg:h-screen">

            {{-- Sidebar Header --}}
            <div class="px-5 py-5 border-b border-[#f0f0f2]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-8 w-auto object-contain">
                    <div>
                        <p class="text-sm font-semibold text-[#1d1d1f]">Stekpro</p>
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider">Admin Panel</p>
                    </div>
                </a>
            </div>

            {{-- Sidebar Nav --}}
            <nav class="flex-1 px-3 py-4">
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.transactions*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.products*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Equipment
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.studio.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.studio*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Studio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.layanan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.layanan*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                            Layanan
                        </a>
                    </li>
                </ul>

                <div class="border-t border-[#f0f0f2] my-4"></div>

                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.users*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7v1H4v-1z"/></svg>
                            Pengguna
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.reports*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.vouchers.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.vouchers*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Voucher
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.reviews*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            Ulasan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.portfolios.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.portfolios*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Portfolio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.activity-logs*') ? 'bg-[#f5f5f7] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Log Aktivitas
                        </a>
                    </li>
                </ul>

                <div class="border-t border-[#f0f0f2] my-4"></div>

                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f] transition-colors">
                            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Lihat Website
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#d70015] hover:bg-red-50 w-full transition-colors">
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-[#f0f0f2]">
                <div class="flex items-center h-12 px-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 mr-2">
                        <svg class="w-5 h-5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-[#1d1d1f]">@yield('page-title', 'Dashboard')</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('notifications') }}" class="relative p-2 -mr-1">
                            <svg class="w-4.5 h-4.5 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @php $notifCount = Auth::user()->notifications()->where('is_read', false)->count(); @endphp
                            @if($notifCount > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[#d70015]"></span>
                            @endif
                        </a>
                        <div class="hidden lg:flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-[10px] font-semibold">
                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            </div>
                            <span class="text-xs text-[#6e6e73]">{{ Auth::user()->nama }}</span>
                        </div>
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
