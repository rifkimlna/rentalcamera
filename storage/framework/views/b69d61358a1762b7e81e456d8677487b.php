<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Stekpro Multimedia & Broadcast'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta-description', 'Stekpro Multimedia & Broadcast - Penyewaan kamera, studio foto, dan perlengkapan multimedia di Sukabumi. Harga terjangkau, alat terawat.'); ?>">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Stekpro Multimedia & Broadcast">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', 'Stekpro Multimedia & Broadcast'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta-description', 'Penyewaan kamera, studio foto, dan perlengkapan multimedia di Sukabumi.'); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:image" content="<?php echo e(asset('images/logo.png')); ?>">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('title', 'Stekpro Multimedia & Broadcast'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta-description', 'Penyewaan kamera, studio foto, dan perlengkapan multimedia di Sukabumi.'); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-white min-h-screen flex flex-col" x-data="{ mobileOpen: false }">

    
    <header class="fixed top-0 left-0 right-0 z-50 glass border-b border-black/5" x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })" :class="{ 'shadow-sm': scrolled }">
        <nav class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
            <div class="flex items-center justify-between h-14 lg:h-16">
                
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2.5 shrink-0">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Stekpro Logo" class="h-9 w-auto object-contain">
                    <span class="text-base font-semibold tracking-tight text-[#1d1d1f] hidden sm:block">Stekpro</span>
                </a>

                
                <div class="hidden lg:flex items-center">
                    <div class="flex items-center gap-1 p-1 bg-black/[0.03] rounded-full">
                        <a href="<?php echo e(route('home')); ?>" class="px-4 py-2 text-sm rounded-full transition-all duration-200 <?php echo e(request()->routeIs('home') ? 'bg-white text-[#1d1d1f] shadow-sm font-medium' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'); ?>">Beranda</a>
                        <a href="<?php echo e(auth()->check() && auth()->user()->isCustomer() ? route('customer.products.index') : route('products')); ?>" class="px-4 py-2 text-sm rounded-full transition-all duration-200 <?php echo e(request()->routeIs(['customer.products.*', 'product.*', 'products']) ? 'bg-white text-[#1d1d1f] shadow-sm font-medium' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'); ?>">Equipment</a>
                        <a href="<?php echo e(route('pricing')); ?>" class="px-4 py-2 text-sm rounded-full transition-all duration-200 <?php echo e(request()->routeIs('pricing') ? 'bg-white text-[#1d1d1f] shadow-sm font-medium' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'); ?>">Harga</a>
                        <a href="<?php echo e(route('about')); ?>" class="px-4 py-2 text-sm rounded-full transition-all duration-200 <?php echo e(request()->routeIs('about') ? 'bg-white text-[#1d1d1f] shadow-sm font-medium' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'); ?>">Tentang</a>
                        <a href="<?php echo e(route('contact')); ?>" class="px-4 py-2 text-sm rounded-full transition-all duration-200 <?php echo e(request()->routeIs('contact') ? 'bg-white text-[#1d1d1f] shadow-sm font-medium' : 'text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white/60'); ?>">Kontak</a>
                    </div>
                </div>

                
                <div class="hidden lg:flex items-center gap-3">
                    <?php if(auth()->guard()->check()): ?>
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-black/[0.04] transition-colors">
                                <div class="w-8 h-8 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-xs font-semibold">
                                    <?php echo e(strtoupper(substr(auth()->user()->nama, 0, 1))); ?>

                                </div>
                                <span class="text-sm font-medium text-[#1d1d1f] hidden xl:block"><?php echo e(auth()->user()->nama); ?></span>
                                <svg class="w-3.5 h-3.5 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-lg border border-[#f0f0f2] py-2 z-50">
                                <div class="px-4 py-2 border-b border-[#f0f0f2] mb-1">
                                    <p class="text-sm font-medium text-[#1d1d1f] truncate"><?php echo e(auth()->user()->nama); ?></p>
                                    <p class="text-xs text-[#86868b] truncate"><?php echo e(auth()->user()->email); ?></p>
                                </div>
                                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-[#1d1d1f] hover:bg-[#f5f5f7] transition-colors">
                                    <svg class="w-4 h-4 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                                <a href="<?php echo e(route('profile')); ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-[#1d1d1f] hover:bg-[#f5f5f7] transition-colors">
                                    <svg class="w-4 h-4 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil
                                </a>
                                <div class="border-t border-[#f0f0f2] mt-1 pt-1">
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="flex items-center gap-3 px-4 py-2 text-sm text-[#d70015] hover:bg-[#fef2f2] w-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium text-[#1d1d1f] hover:text-[#6e6e73] transition-colors px-3 py-2">Masuk</a>
                        <a href="<?php echo e(route('register')); ?>" class="btn-primary-apple !px-5 !py-2 !text-sm">Daftar</a>
                    <?php endif; ?>
                </div>

                
                <div class="lg:hidden flex items-center gap-2">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('customer.cart.index')); ?>" class="relative p-2 -mr-1">
                            <svg class="w-5 h-5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </a>
                    <?php endif; ?>
                    <button @click="mobileOpen = !mobileOpen" class="p-2 -mr-1">
                        <svg x-show="!mobileOpen" class="w-5 h-5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" x-cloak class="w-5 h-5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </nav>

        
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden glass border-t border-black/5">
            <div class="max-w-7xl mx-auto px-5 py-4 space-y-1">
                <a href="<?php echo e(route('home')); ?>" class="block px-4 py-2.5 text-sm rounded-xl transition-colors <?php echo e(request()->routeIs('home') ? 'bg-black/[0.04] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-black/[0.03]'); ?>">Beranda</a>
                <a href="<?php echo e(auth()->check() && auth()->user()->isCustomer() ? route('customer.products.index') : route('products')); ?>" class="block px-4 py-2.5 text-sm rounded-xl transition-colors <?php echo e(request()->routeIs(['customer.products.*', 'product.*', 'products']) ? 'bg-black/[0.04] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-black/[0.03]'); ?>">Equipment</a>
                <a href="<?php echo e(route('pricing')); ?>" class="block px-4 py-2.5 text-sm rounded-xl transition-colors <?php echo e(request()->routeIs('pricing') ? 'bg-black/[0.04] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-black/[0.03]'); ?>">Harga</a>
                <a href="<?php echo e(route('about')); ?>" class="block px-4 py-2.5 text-sm rounded-xl transition-colors <?php echo e(request()->routeIs('about') ? 'bg-black/[0.04] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-black/[0.03]'); ?>">Tentang</a>
                <a href="<?php echo e(route('contact')); ?>" class="block px-4 py-2.5 text-sm rounded-xl transition-colors <?php echo e(request()->routeIs('contact') ? 'bg-black/[0.04] font-medium text-[#1d1d1f]' : 'text-[#6e6e73] hover:bg-black/[0.03]'); ?>">Kontak</a>
                <div class="border-t border-[#f0f0f2] my-2"></div>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="block px-4 py-2.5 text-sm font-medium text-[#1d1d1f] hover:bg-black/[0.03] rounded-xl">Dashboard</a>
                    <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2.5 text-sm text-[#6e6e73] hover:bg-black/[0.03] rounded-xl">Profil</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-[#d70015] hover:bg-red-50 rounded-xl">Keluar</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="block px-4 py-2.5 text-sm font-medium text-[#1d1d1f] hover:bg-black/[0.03] rounded-xl">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="block px-4 py-2.5 text-sm font-medium text-white bg-[#1d1d1f] hover:bg-[#333] rounded-xl text-center mt-1">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    
    <div class="h-14 lg:h-16"></div>

    
    <main class="flex-1 w-full flex flex-col">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 pt-6 w-full">
            <?php if (isset($component)) { $__componentOriginal5b09c79149dfb771c232996af5f9dae4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b09c79149dfb771c232996af5f9dae4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.flash-messages','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b09c79149dfb771c232996af5f9dae4)): ?>
<?php $attributes = $__attributesOriginal5b09c79149dfb771c232996af5f9dae4; ?>
<?php unset($__attributesOriginal5b09c79149dfb771c232996af5f9dae4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b09c79149dfb771c232996af5f9dae4)): ?>
<?php $component = $__componentOriginal5b09c79149dfb771c232996af5f9dae4; ?>
<?php unset($__componentOriginal5b09c79149dfb771c232996af5f9dae4); ?>
<?php endif; ?>
        </div>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="bg-[#f5f5f7] border-t border-[#e5e5e7]">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-12 lg:py-16">
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                
                <div class="col-span-2 sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Stekpro Logo" class="h-7 w-auto object-contain">
                        <span class="text-sm font-semibold text-[#1d1d1f]">Stekpro</span>
                    </div>
                    <p class="text-sm text-[#6e6e73] leading-relaxed max-w-xs">Penyewaan kamera, studio, dan perlengkapan fotografi untuk kebutuhan kreatif Anda.</p>
                </div>

                
                <div>
                    <h4 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Layanan</h4>
                    <ul class="space-y-2.5">
                        <li><a href="<?php echo e(route('customer.products.index')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">Equipment</a></li>
                        <li><a href="<?php echo e(route('customer.studio.index')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">Studio</a></li>
                        <li><a href="<?php echo e(route('customer.layanan.index')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">Layanan</a></li>
                    </ul>
                </div>

                
                <div>
                    <h4 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Informasi</h4>
                    <ul class="space-y-2.5">
                        <li><a href="<?php echo e(route('about')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">Tentang Kami</a></li>
                        <li><a href="<?php echo e(route('pricing')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">Harga</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] transition-colors">Kontak</a></li>
                    </ul>
                </div>

                
                <div>
                    <h4 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Kontak</h4>
                    <ul class="space-y-2.5 text-sm text-[#6e6e73]">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Sukabumi, Indonesia
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +62 812 3456 7890
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            info@sewakamerapro.com
                        </li>
                    </ul>
                </div>
            </div>

            
            <div class="border-t border-[#e5e5e7] mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-[#86868b]">&copy; <?php echo e(date('Y')); ?> Stekpro Multimedia & Broadcast. All rights reserved.</p>
                <div class="flex items-center gap-5">
                    <a href="#" class="text-xs text-[#86868b] hover:text-[#1d1d1f] transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-xs text-[#86868b] hover:text-[#1d1d1f] transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    
    <?php $waPhone = '6281234567890'; ?>
    <a href="https://wa.me/<?php echo e($waPhone); ?>" target="_blank" rel="noopener" class="whatsapp-btn fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg hover:bg-[#20BD5A] transition-colors">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll reveal
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

        // Auto-dismiss alerts (banner error validasi tetap tampil)
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]:not([data-persistent])').forEach(function(el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 300);
            });
        }, 5000);
    });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\PROJECT KP\resources\views\layouts\app.blade.php ENDPATH**/ ?>