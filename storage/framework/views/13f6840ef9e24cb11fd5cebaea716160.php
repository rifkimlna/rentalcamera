<div class="card-apple overflow-hidden">
    <a href="<?php echo e(route('customer.products.show', $product->slug)); ?>" class="group relative overflow-hidden bg-[#f5f5f7] aspect-square block">
        <?php if($product->gambar_utama): ?>
            <img src="<?php echo e(asset('storage/' . $product->gambar_utama)); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?php echo e($product->nama_produk); ?>">
        <?php else: ?>
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 sm:h-16 w-12 sm:w-16 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

        <div class="absolute top-2 right-2 sm:top-3 sm:right-3 flex flex-col items-end gap-1">
            <?php if($product->is_featured): ?>
                <span class="inline-flex items-center text-[10px] sm:text-xs font-medium text-white/90 bg-white/15 backdrop-blur-md px-2 sm:px-3 py-0.5 sm:py-1 rounded-full">Featured</span>
            <?php endif; ?>
            <?php if($product->is_recommended): ?>
                <span class="inline-flex items-center text-[10px] sm:text-xs font-medium text-white/90 bg-white/15 backdrop-blur-md px-2 sm:px-3 py-0.5 sm:py-1 rounded-full">Rekomendasi</span>
            <?php endif; ?>
        </div>

        <div class="absolute bottom-0 left-0 right-0 p-2.5 sm:p-3 lg:p-4 text-white">
            <p class="text-xs sm:text-sm lg:text-base font-semibold truncate"><?php echo e($product->nama_produk); ?></p>
            <div class="flex items-center gap-0.5 sm:gap-1 mt-0.5 sm:mt-1">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <svg class="h-2.5 w-2.5 sm:h-3 sm:w-3 <?php echo e($i <= round($product->rating) ? 'text-[#ff9500]' : 'text-white/30'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <?php endfor; ?>
                <span class="text-[10px] sm:text-xs text-white/70 ml-0.5 sm:ml-1">(<?php echo e($product->rating); ?>)</span>
            </div>
        </div>
    </a>

    <div class="p-2.5 sm:p-3 lg:p-4 bg-white">
        <div class="flex items-center justify-between gap-1.5 mb-1.5 sm:mb-2">
            <div class="min-w-0">
                <div class="flex items-baseline gap-1">
                    <span class="text-xs sm:text-sm lg:text-base font-bold text-[#1d1d1f]">Rp <?php echo e(number_format($product->harga_per_hari, 0, ',', '.')); ?></span>
                    <span class="text-[10px] sm:text-xs text-[#86868b]">/hari</span>
                </div>
            </div>
            <?php if($product->status != 'available' || $product->stok_tersedia <= 0): ?>
                <span class="badge-error !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2.5 !py-0">Habis</span>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-1 mb-2 sm:mb-3 flex-wrap">
            <?php if($product->kategori): ?>
                <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0"><?php echo e($product->kategori->nama_kategori); ?></span>
            <?php endif; ?>
            <?php if($product->brand): ?>
                <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0"><?php echo e($product->brand->nama_brand); ?></span>
            <?php endif; ?>
        </div>

        <div class="flex gap-1.5 sm:gap-2">
            <a href="<?php echo e(route('customer.products.show', $product->slug)); ?>" class="flex-1 text-center text-[11px] sm:text-xs lg:text-sm font-medium py-1.5 sm:py-2 rounded-lg lg:rounded-xl border border-[#e5e5e7] text-[#1d1d1f] hover:bg-[#f5f5f7] transition-colors">Detail</a>
            <?php if($product->status == 'available' && $product->stok_tersedia > 0): ?>
                <a href="<?php echo e(route('customer.products.show', $product->slug)); ?>" class="flex-1 text-center text-[11px] sm:text-xs lg:text-sm font-medium py-1.5 sm:py-2 rounded-lg lg:rounded-xl bg-[#1d1d1f] text-white hover:bg-[#333] transition-colors">Sewa</a>
            <?php else: ?>
                <button class="flex-1 text-[11px] sm:text-xs lg:text-sm font-medium py-1.5 sm:py-2 rounded-lg lg:rounded-xl bg-[#f5f5f7] text-[#86868b] cursor-not-allowed" disabled>Tidak Tersedia</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\products\_product_card.blade.php ENDPATH**/ ?>