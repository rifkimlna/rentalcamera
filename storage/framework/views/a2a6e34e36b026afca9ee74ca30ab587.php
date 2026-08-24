<?php $__env->startSection('title', 'Riwayat Sewa - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('page-title', 'Riwayat Sewa'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <?php if($totalSpending > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="card-apple-static">
            <div class="p-4 text-center">
                <p class="text-xs text-[#6e6e73]">Total Transaksi</p>
                <p class="text-xl font-medium"><?php echo e($rentalHistory->total()); ?></p>
            </div>
        </div>
        <div class="card-apple-static">
            <div class="p-4 text-center">
                <p class="text-xs text-[#6e6e73]">Total Pengeluaran</p>
                <p class="text-xl font-medium">Rp <?php echo e(number_format($totalSpending, 0, ',', '.')); ?></p>
            </div>
        </div>
        <div class="card-apple-static">
            <div class="p-4 text-center">
                <p class="text-xs text-[#6e6e73]">Produk Terpopuler</p>
                <p class="text-xl font-medium"><?php echo e($mostRented->first()->nama_produk ?? '-'); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php $__empty_1 = true; $__currentLoopData = $rentalHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card-apple-static">
        <div class="p-4">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div>
                    <p class="text-sm font-medium"><?php echo e($rental->kode_transaksi); ?></p>
                    <p class="text-xs text-[#86868b]"><?php echo e($rental->completed_at ? $rental->completed_at->format('d M Y') : $rental->created_at->format('d M Y')); ?></p>
                </div>
                <span class="badge-apple !text-[10px] !px-2 !py-0.5 border border-[#e5e5e7]">Selesai</span>
            </div>

            <div class="space-y-2">
                <?php $__currentLoopData = $rental->detailTransaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between py-2 border-b border-[#f0f0f2] last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#f5f5f7] rounded-xl flex items-center justify-center text-xs text-[#86868b]">
                            <?php if($detail->produk && $detail->produk->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $detail->produk->gambar)); ?>" alt="<?php echo e($detail->nama_produk); ?>" class="w-full h-full object-cover rounded-xl">
                            <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-sm"><?php echo e($detail->nama_produk); ?></p>
                            <p class="text-xs text-[#86868b]"><?php echo e($detail->jumlah); ?> unit x <?php echo e($detail->lama_sewa ?? $rental->lama_sewa); ?> hari</p>
                        </div>
                    </div>
                    <p class="text-sm font-medium">Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-[#e5e5e7]">
                <div class="text-xs text-[#6e6e73]">
                    <span><?php echo e($rental->tanggal_pengambilan ? $rental->tanggal_pengambilan->format('d M Y') : '-'); ?></span>
                    <span class="mx-1">&rarr;</span>
                    <span><?php echo e($rental->tanggal_pengembalian ? $rental->tanggal_pengembalian->format('d M Y') : '-'); ?></span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-[#86868b]">Total</p>
                    <p class="text-sm font-semibold">Rp <?php echo e(number_format($rental->grand_total, 0, ',', '.')); ?></p>
                </div>
            </div>

            <?php if($rental->ulasan): ?>
            <div class="mt-3 pt-3 border-t border-[#f0f0f2]">
                <div class="flex items-center gap-1">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 <?php echo e($i <= $rental->ulasan->rating ? 'text-[#1d1d1f]' : 'text-[#86868b]'); ?>" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card-apple-static">
        <div class="p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm text-[#6e6e73]">Belum ada riwayat sewa</p>
            <a href="<?php echo e(route('customer.products.index')); ?>" class="btn-outline-apple mt-3">Sewa Sekarang</a>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex justify-center">
        <?php echo e($rentalHistory->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\dashboard\rental_history.blade.php ENDPATH**/ ?>