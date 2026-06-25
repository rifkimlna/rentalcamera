<!DOCTYPE html>
<html lang="id" data-theme="minimalist">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <title>@yield('title', 'Admin - Sewa Kamera Pro')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans bg-base-200 min-h-screen">
    <div class="drawer lg:drawer-open">
        <input id="sidebar-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <div class="navbar bg-base-100 border-b border-base-300 sticky top-0 z-30">
                <div class="flex-none lg:hidden">
                    <label for="sidebar-drawer" class="btn btn-square btn-ghost btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                    </label>
                </div>
                <div class="flex-1">
                    <span class="text-sm font-medium hidden lg:block">@yield('page-title', 'Dashboard')</span>
                </div>
                <div class="flex-none flex items-center gap-1">
                    <a href="{{ route('notifications') }}" class="btn btn-ghost btn-sm relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php $notifCount = Auth::user()->notifications()->where('is_read', false)->count(); @endphp
                        @if($notifCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 badge badge-xs badge-error p-0.5 min-w-[14px] h-[14px] text-[9px]">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm text-sm font-normal">
                            {{ Auth::user()->nama }}
                        </div>
                        <ul tabindex="0" class="mt-2 z-[1] p-1 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-40 text-sm">
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="{{ route('notifications') }}">Notifikasi</a></li>
                            <li class="border-t border-base-200 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="text-error">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-4 lg:p-6">
                @if(session('success'))
                    <div role="alert" class="alert alert-success mb-4 text-sm py-2">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div role="alert" class="alert alert-error mb-4 text-sm py-2">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @if($errors->any())
                    <div role="alert" class="alert alert-error mb-4 text-sm py-2">
                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>

        <div class="drawer-side z-40">
            <label for="sidebar-drawer" class="drawer-overlay"></label>
            <aside class="bg-base-100 border-r border-base-300 min-h-screen w-56 overflow-y-auto">
                <div class="px-4 py-4 border-b border-base-300">
                    <p class="text-sm font-medium">Sewa Kamera Pro</p>
                    <p class="text-xs text-base-content/50">Admin Panel</p>
                </div>
                <ul class="menu p-2 text-sm">
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">Produk</a></li>
                    <li><a href="{{ route('admin.transactions.index') }}" class="{{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">Transaksi</a></li>
                    <li>
                        <a class="{{ request()->routeIs('admin.studio*') ? 'active' : '' }}">Studio</a>
                        <ul>
                            <li><a href="{{ route('admin.studio.index') }}" class="{{ request()->routeIs('admin.studio.index*') || request()->routeIs('admin.studio.create*') || request()->routeIs('admin.studio.edit*') || request()->routeIs('admin.studio.show*') || request()->routeIs('admin.studio.paket*') ? 'active' : '' }}">Daftar Studio</a></li>
                            <li><a href="{{ route('admin.studio.bookings') }}" class="{{ request()->routeIs('admin.studio.bookings*') || request()->routeIs('admin.studio.booking.print*') ? 'active' : '' }}">Booking Studio</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">Pengguna</a></li>
                    <li><a href="{{ route('admin.maintenance.index') }}" class="{{ request()->routeIs('admin.maintenance*') ? 'active' : '' }}">Maintenance</a></li>
                    <li><a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports*') ? 'active' : '' }}">Laporan</a></li>
                    <li><a href="{{ route('admin.vouchers.index') }}" class="{{ request()->routeIs('admin.vouchers*') ? 'active' : '' }}">Voucher</a></li>
                    <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">Ulasan</a></li>
                    <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">Pengaturan</a></li>
                    <li><a href="{{ route('admin.activity-logs') }}" class="{{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">Log</a></li>
                </ul>
                <div class="border-t border-base-300 p-2 mt-2">
                    <ul class="menu text-sm">
                        <li><a href="{{ route('home') }}" target="_blank">Lihat Website</a></li>
                        <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-error">Logout</button></form></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]').forEach(function(el) {
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
