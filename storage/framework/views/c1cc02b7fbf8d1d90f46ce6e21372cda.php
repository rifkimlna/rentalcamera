<?php $__env->startSection('title', 'Sewa Equipment Kamera - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('meta-description', 'Sewa kamera DSLR, mirrorless, lensa, lighting, drone, dan peralatan fotografi lainnya dengan harga harian terbaik. Kondisi terawat, siap antar.'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-8 lg:py-12">

    
    <div class="text-center max-w-2xl mx-auto mb-8 lg:mb-12">
        <p class="text-sm font-semibold text-[#0071e3] mb-2 tracking-wide">KATALOG EQUIPMENT</p>
        <h1 class="text-3xl lg:text-5xl font-bold text-[#1d1d1f] tracking-tight">Sewa Peralatan
            <span class="block bg-gradient-to-r from-[#0071e3] to-[#42a1ec] bg-clip-text text-transparent">Fotografi Terbaik</span>
        </h1>
        <p class="text-[#6e6e73] mt-4 text-base lg:text-lg leading-relaxed">Kamera, lensa, lighting, hingga drone — semua terawat dan siap menemani momen kreatif Anda.</p>
    </div>

    
    <div class="card-apple-static p-3 sm:p-4 lg:p-5 mb-6 sm:mb-8">
        <form method="GET" action="<?php echo e(route('products')); ?>">
            
            <div class="hidden lg:grid lg:grid-cols-6 gap-4">
                <div class="col-span-2">
                    <label for="search" class="block text-xs font-medium text-[#86868b] mb-1.5">Cari Produk</label>
                    <input type="text" id="search" class="input-apple" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari produk...">
                </div>
                <div>
                    <label for="category" class="block text-xs font-medium text-[#86868b] mb-1.5">Kategori</label>
                    <select id="category" class="select-apple" name="category">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->slug); ?>" <?php echo e(request('category') == $category->slug ? 'selected' : ''); ?>><?php echo e($category->nama_kategori); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="brand" class="block text-xs font-medium text-[#86868b] mb-1.5">Brand</label>
                    <select id="brand" class="select-apple" name="brand">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->slug); ?>" <?php echo e(request('brand') == $brand->slug ? 'selected' : ''); ?>><?php echo e($brand->nama_brand); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="sort" class="block text-xs font-medium text-[#86868b] mb-1.5">Urutkan</label>
                    <select id="sort" class="select-apple" name="sort">
                        <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Terbaru</option>
                        <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Harga Terendah</option>
                        <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Harga Tertinggi</option>
                        <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>Terpopuler</option>
                        <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Rating Tertinggi</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-dark-apple !px-4 !py-2.5 flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                        Cari
                    </button>
                    <a href="<?php echo e(route('products')); ?>" class="btn-outline-apple !px-3 !py-2.5" aria-label="Reset filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </div>

            
            <div class="lg:hidden space-y-3">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <label for="search-mobile" class="sr-only">Cari produk</label>
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#86868b]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                        </span>
                        <input type="text" id="search-mobile" class="input-apple !pl-9" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari produk...">
                    </div>
                    <button type="submit" class="btn-dark-apple !px-5 !py-3">Cari</button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <select class="select-apple !text-xs !py-2" name="category" aria-label="Kategori">
                        <option value="">Kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->slug); ?>" <?php echo e(request('category') == $category->slug ? 'selected' : ''); ?>><?php echo e($category->nama_kategori); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select class="select-apple !text-xs !py-2" name="brand" aria-label="Brand">
                        <option value="">Brand</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand->slug); ?>" <?php echo e(request('brand') == $brand->slug ? 'selected' : ''); ?>><?php echo e($brand->nama_brand); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select class="select-apple !text-xs !py-2" name="sort" aria-label="Urutkan">
                        <option value="newest">Terbaru</option>
                        <option value="price_low">Harga Rendah</option>
                        <option value="price_high">Harga Tinggi</option>
                        <option value="popular">Populer</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    
    <p class="text-sm text-[#86868b] mb-4"><?php echo e($products->total()); ?> equipment ditemukan</p>

    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $attributes = $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $component = $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full card-apple-static p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#d1d1d6] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                <p class="text-[#6e6e73] mb-1 font-medium">Produk tidak ditemukan</p>
                <p class="text-sm text-[#86868b] mb-4">Coba ubah kata kunci atau reset filter pencarian.</p>
                <a href="<?php echo e(route('products')); ?>" class="btn-outline-apple !px-5 !py-2 !text-sm">Reset Pencarian</a>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($products->hasPages()): ?>
        <div class="flex justify-center mt-10">
            <?php echo e($products->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>

    
    <div class="mt-14 card-apple-static p-6 lg:p-8">
        <h2 class="text-lg font-semibold text-[#1d1d1f] mb-5">Jelajahi per Kategori</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('products', ['category' => $category->slug])); ?>" class="group flex flex-col items-center p-4 rounded-2xl hover:bg-[#f5f5f7] transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-[#f5f5f7] flex items-center justify-center mb-3 group-hover:bg-[#e5e5e7] transition-colors">
                        <?php
                            $icons = [
                                'kamera-dslr' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                                'kamera-mirrorless' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                                'lensa-kamera' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm-7 4a7 7 0 1114 0 7 7 0 01-14 0z"/><circle cx="12" cy="12" r="3"/></svg>',
                                'lighting-equipment' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
                                'audio-equipment' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m-4 0h8m-8-8a4 4 0 014-4m0 0a4 4 0 014 4"/></svg>',
                                'tripod-stabilizer' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'drone' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'aksesoris' => '<svg class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
                            ];
                            $icon = $icons[$category->slug] ?? $icons['kamera-dslr'];
                        ?>
                        <?php echo $icon; ?>

                    </div>
                    <span class="text-sm font-medium text-[#1d1d1f] text-center"><?php echo e($category->nama_kategori); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <?php if(auth()->guard()->guest()): ?>
        <div class="mt-14 rounded-3xl bg-gradient-to-br from-[#1d1d1f] to-[#3a3a3c] p-8 lg:p-12 text-center text-white">
            <h2 class="text-2xl lg:text-3xl font-bold tracking-tight mb-3">Siap menyewa equipment ini?</h2>
            <p class="text-white/70 max-w-xl mx-auto mb-6">Daftar gratis dalam 1 menit untuk mulai memesan, mengelola keranjang, dan melacak transaksi Anda.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?php echo e(route('register')); ?>" class="btn-primary-apple !px-8 !py-3">Daftar Sekarang</a>
                <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center justify-center px-8 py-3 rounded-full border border-white/25 text-sm font-medium text-white hover:bg-white/10 transition-colors">Masuk</a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/products/index.blade.php ENDPATH**/ ?>