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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#f5f5f7] min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/30 z-40 lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="flex min-h-screen">

        {{-- SIDEBAR — selaras: rounded-[24px] tidak, tapi border soft + ikon stroke 1.5 konsisten --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed top-0 left-0 bottom-0 w-[264px] bg-white border-r border-[#e5e5e7] z-50 transition-transform duration-200 ease-out overflow-y-auto flex flex-col lg:sticky lg:top-0 lg:h-screen">

            {{-- Sidebar Header --}}
            <div class="px-5 py-5 border-b border-[#f0f0f2] flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Stekpro Logo" class="h-8 w-auto object-contain shrink-0">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[#1d1d1f] leading-none">Stekpro</p>
                        <p class="text-[10px] text-[#86868b] uppercase tracking-[0.12em] mt-0.5">Admin Panel</p>
                    </div>
                </a>
                <button @click="sidebarOpen=false" class="lg:hidden p-2 -mr-2 text-[#86868b] hover:text-[#1d1d1f]">
                    <x-admin.icon name="x" :size="18" />
                </button>
            </div>

            {{-- Sidebar Nav --}}
            <nav class="flex-1 px-3 py-4">
                <p class="px-3 mb-2 text-[10px] font-semibold tracking-[0.14em] uppercase text-[#86868b]">Menu Utama</p>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="dashboard" :size="18" />
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.transactions*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="transactions" :size="18" />
                            Transaksi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.products*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="equipment" :size="18" />
                            Equipment
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.studio.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.studio*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="studio" :size="18" />
                            Studio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.layanan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.layanan*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="layanan" :size="18" />
                            Layanan
                        </a>
                    </li>
                </ul>

                <div class="border-t border-[#f0f0f2] my-4"></div>
                <p class="px-3 mb-2 text-[10px] font-semibold tracking-[0.14em] uppercase text-[#86868b]">Kelola</p>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.users*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="users" :size="18" />
                            Pengguna
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.reports*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="reports" :size="18" />
                            Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.vouchers.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.vouchers*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="voucher" :size="18" />
                            Voucher
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.reviews*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="reviews" :size="18" />
                            Ulasan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.portfolios.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.portfolios*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="portfolio" :size="18" />
                            Portfolio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl transition-colors {{ request()->routeIs('admin.activity-logs*') ? 'bg-[#1d1d1f] text-white font-medium shadow-sm' : 'text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f]' }}">
                            <x-admin.icon name="activity" :size="18" />
                            Log Aktivitas
                        </a>
                    </li>
                </ul>

                <div class="border-t border-[#f0f0f2] my-4"></div>

                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f] transition-colors">
                            <x-admin.icon name="external" :size="18" />
                            Lihat Website
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-xl text-[#d70015] hover:bg-red-50 w-full transition-colors">
                                <x-admin.icon name="logout" :size="18" />
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
            <div class="px-4 py-3 border-t border-[#f0f0f2] hidden lg:block">
                <p class="text-[10px] leading-relaxed text-[#86868b]">© {{ date('Y') }} Stekpro Multimedia</p>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- Top Bar — selaras: blur + border #f0f0f2 + auto-layout --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-[#f0f0f2]">
                <div class="flex items-center h-14 px-4 sm:px-6 gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-[#1d1d1f] hover:bg-[#f5f5f7] rounded-xl transition">
                        <x-admin.icon name="menu" :size="20" />
                    </button>
                    <div class="flex-1 min-w-0">
                        <span class="text-sm font-semibold tracking-tight text-[#1d1d1f] truncate block">@yield('page-title', 'Dashboard')</span>
                        <span class="text-[11px] text-[#86868b] hidden sm:block truncate">@yield('page-subtitle', '')</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                        <a href="{{ route('notifications') }}" class="relative p-2.5 rounded-xl hover:bg-[#f5f5f7] text-[#6e6e73] hover:text-[#1d1d1f] transition">
                            <x-admin.icon name="bell" :size="18" />
                            @php $notifCount = Auth::user()->notifications()->where('is_read', false)->count(); @endphp
                            @if($notifCount > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[#d70015] ring-2 ring-white"></span>
                            @endif
                        </a>
                        <div class="hidden sm:flex items-center gap-2.5 pl-2.5 border-l border-[#f0f0f2]">
                            <x-avatar :user="Auth::user()" :size="32" />
                            <div class="hidden lg:block text-left">
                                <div class="text-xs font-medium text-[#1d1d1f] leading-none">{{ Str::limit(Auth::user()->nama, 16) }}</div>
                                <div class="text-[11px] text-[#86868b] leading-none mt-0.5 capitalize">{{ Auth::user()->role }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content — selaras max-width + auto-layout padding --}}
            <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-[1280px] mx-auto">

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
