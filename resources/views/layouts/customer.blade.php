<!DOCTYPE html>
<html lang="id" data-theme="minimalist">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sewa Kamera Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    @stack('styles')
</head>
<body class="font-sans bg-base-200 min-h-screen">
    <div class="drawer lg:drawer-open">
        <input id="customer-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <div class="navbar bg-base-100 border-b border-base-300 sticky top-0 z-30">
                <div class="flex-none lg:hidden">
                    <label for="customer-drawer" class="btn btn-square btn-ghost btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                    </label>
                </div>
                <div class="flex-1">
                    <h2 class="text-sm font-medium">@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="flex-none">
                    <a href="{{ route('customer.cart.index') }}" class="btn btn-ghost btn-sm relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->count(); @endphp
                        @if($cartCount > 0)
                            <span class="badge badge-sm badge-error absolute -top-1 -right-1">{{ $cartCount }}</span>
                        @endif
                    </a>
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
                @if(session('warning'))
                    <div role="alert" class="alert alert-warning mb-4 text-sm py-2">
                        <span>{{ session('warning') }}</span>
                        <button onclick="this.parentElement.remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @if(session('info'))
                    <div role="alert" class="alert alert-info mb-4 text-sm py-2">
                        <span>{{ session('info') }}</span>
                        <button onclick="this.parentElement.remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>

        <div class="drawer-side z-40">
            <label for="customer-drawer" class="drawer-overlay"></label>
            <aside class="bg-base-100 border-r border-base-300 min-h-screen w-56 overflow-y-auto">
                <div class="px-4 py-4 border-b border-base-300">
                    <p class="text-sm font-medium">{{ auth()->user()->nama }}</p>
                    <p class="text-xs text-base-content/50">{{ auth()->user()->email }}</p>
                </div>

                <ul class="menu p-2 text-sm mt-2">
                    <li><a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('customer.products.index') }}" class="{{ request()->routeIs('customer.products.*') ? 'active' : '' }}">Sewa Kamera</a></li>
                    <li><a href="{{ route('customer.cart.index') }}" class="{{ request()->routeIs('customer.cart.index') ? 'active' : '' }}">Keranjang @if($cartCount > 0)<span class="badge badge-error badge-xs">{{ $cartCount }}</span>@endif</a></li>
                    <li><a href="{{ route('customer.transactions.index') }}" class="{{ request()->routeIs('customer.transactions.*') ? 'active' : '' }}">Transaksi</a></li>
                    <li><a href="{{ route('customer.studio.index') }}" class="{{ request()->routeIs('customer.studio.*') && !request()->routeIs('customer.studio.my-bookings') ? 'active' : '' }}">Sewa Studio</a></li>
                    <li><a href="{{ route('customer.studio.my-bookings') }}" class="{{ request()->routeIs('customer.studio.my-bookings') ? 'active' : '' }}">Booking Studio</a></li>
                    <li><a href="{{ route('customer.reviews.available') }}" class="{{ request()->routeIs('customer.reviews.*') ? 'active' : '' }}">Ulasan</a></li>
                    <li><a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profil</a></li>
                </ul>
                <div class="border-t border-base-300 p-2 mt-2">
                    <ul class="menu text-sm">
                        <li><a href="{{ route('home') }}">Kembali ke Beranda</a></li>
                        <li><a href="{{ route('logout') }}" class="text-error" onclick="event.preventDefault();document.getElementById('logout-form-sidebar').submit();">Logout</a></li>
                    </ul>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
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

        document.querySelectorAll('.datepicker').forEach(function(el) { el.setAttribute('type', 'date'); });

        document.querySelectorAll('.rental-date').forEach(function(el) {
            el.addEventListener('change', function() {
                var start = document.getElementById('tanggal_sewa');
                var end = document.getElementById('tanggal_kembali');
                if (!start || !end) return;
                var s = new Date(start.value);
                var e = new Date(end.value);
                if (s && e && s <= e) {
                    var diff = Math.ceil(Math.abs(e - s) / (1000 * 60 * 60 * 24)) + 1;
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
