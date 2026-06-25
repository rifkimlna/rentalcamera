<!DOCTYPE html>
<html lang="id" data-theme="minimalist">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sewa Kamera Pro') - Sewa Kamera Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans bg-base-200 min-h-screen flex flex-col">
    <nav class="navbar bg-base-100 border-b border-base-300 sticky top-0 z-50">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('customer.products.index') }}">Kamera</a></li>
                    <li><a href="{{ route('about') }}">Tentang</a></li>
                    <li><a href="{{ route('contact') }}">Kontak</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-lg font-normal tracking-tight" href="{{ route('home') }}">
                Sewa Kamera Pro
            </a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1 text-sm">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active font-medium' : '' }}">Beranda</a></li>
                <li><a href="{{ route('customer.products.index') }}">Kamera</a></li>
                <li><a href="{{ route('about') }}">Tentang</a></li>
                <li><a href="{{ route('contact') }}">Kontak</a></li>
            </ul>
        </div>
        <div class="navbar-end gap-1">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm text-xs">Admin Panel</a>
                @else
                    <a href="{{ route('customer.cart.index') }}" class="btn btn-ghost btn-sm relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        @php $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->count(); @endphp
                        @if($cartCount > 0)
                            <span class="badge badge-sm badge-error absolute -top-1 -right-1">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endif
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-sm text-sm font-normal">
                        {{ auth()->user()->nama }}
                    </div>
                    <ul tabindex="0" class="mt-2 z-[1] p-1 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-44 text-sm">
                        <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('profile') }}">Profil</a></li>
                        <li><a href="{{ route('customer.transactions.index') }}">Transaksi</a></li>
                        <li class="border-t border-base-200 mt-1 pt-1"><a href="{{ route('logout') }}" class="text-error" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm text-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-neutral btn-sm text-sm">Daftar</a>
            @endauth
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-base-300 bg-base-100">
        <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <p class="font-medium text-sm mb-3">Sewa Kamera Pro</p>
                    <p class="text-xs text-base-content/60 leading-relaxed">Penyewaan kamera dan perlengkapan fotografi terpercaya.</p>
                </div>
                <div>
                    <p class="font-medium text-xs text-base-content/60 uppercase tracking-wider mb-3">Layanan</p>
                    <ul class="space-y-2 text-sm">
                        <li><a class="link link-hover text-base-content/70">Sewa Kamera</a></li>
                        <li><a class="link link-hover text-base-content/70">Sewa Lensa</a></li>
                        <li><a class="link link-hover text-base-content/70">Lighting</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium text-xs text-base-content/60 uppercase tracking-wider mb-3">Kontak</p>
                    <ul class="space-y-2 text-sm text-base-content/70">
                        <li>Jakarta, Indonesia</li>
                        <li>+62 812 3456 7890</li>
                        <li>info@sewakamerapro.com</li>
                    </ul>
                </div>
                <div>
                    <p class="font-medium text-xs text-base-content/60 uppercase tracking-wider mb-3">Jam</p>
                    <ul class="space-y-1 text-sm text-base-content/70">
                        <li>Sen-Jum: 08:00-20:00</li>
                        <li>Sab: 09:00-18:00</li>
                        <li>Min: 10:00-16:00</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-base-300 mt-8 pt-6 text-center text-xs text-base-content/40">
                &copy; {{ date('Y') }} Sewa Kamera Pro
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]').forEach(function(el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 300);
            });
        }, 5000);

        document.querySelectorAll('.btn-minus, .btn-plus').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var input = this.closest('.flex, .join, .input-group').querySelector('input');
                if (!input) return;
                var value = parseInt(input.value) || 1;
                if (this.classList.contains('btn-minus') && value > 1) {
                    input.value = value - 1;
                } else if (this.classList.contains('btn-plus') && value < 99) {
                    input.value = value + 1;
                }
                input.dispatchEvent(new Event('change'));
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
