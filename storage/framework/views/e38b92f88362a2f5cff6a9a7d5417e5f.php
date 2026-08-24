<?php $__env->startSection('title', 'Laporan Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-lg font-semibold">Laporan Produk</h1>
        <form action="<?php echo e(route('admin.reports.products')); ?>" method="GET" class="flex items-center gap-2">
            <input type="date" class="input-apple input-xs w-36" name="start_date" value="<?php echo e($startDate->format('Y-m-d')); ?>">
            <span class="text-xs text-[#86868b]">s/d</span>
            <input type="date" class="input-apple input-xs w-36" name="end_date" value="<?php echo e($endDate->format('Y-m-d')); ?>">
            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Tampilkan</button>
            <a href="<?php echo e(route('admin.reports.products')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Reset</a>
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'products']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors btn-outline">Export</a>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="pb-2 font-medium">Kode</th>
                        <th class="pb-2 font-medium">Produk</th>
                        <th class="pb-2 font-medium">Kategori</th>
                        <th class="pb-2 font-medium">Brand</th>
                        <th class="pb-2 font-medium text-right">Harga/Hari</th>
                        <th class="pb-2 font-medium text-right">Stok</th>
                        <th class="pb-2 font-medium text-right">Dipesan</th>
                        <th class="pb-2 font-medium text-right">Ulasan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-medium"><?php echo e($product->kode_produk); ?></td>
                        <td class="py-2">
                            <div class="font-medium"><?php echo e($product->nama_produk); ?></div>
                            <div class="text-gray-400"><?php echo e(Str::limit($product->deskripsi_singkat, 40)); ?></div>
                        </td>
                        <td class="py-2"><?php echo e($product->kategori ? $product->kategori->nama_kategori : '-'); ?></td>
                        <td class="py-2"><?php echo e($product->brand ? $product->brand->nama_brand : '-'); ?></td>
                        <td class="py-2 text-right">Rp <?php echo e(number_format($product->harga_per_hari, 0, ',', '.')); ?></td>
                        <td class="py-2 text-right"><?php echo e($product->stok_tersedia); ?> / <?php echo e($product->stok_total); ?></td>
                        <td class="py-2 text-right"><?php echo e($product->jumlah_dipesan); ?></td>
                        <td class="py-2 text-right"><?php echo e($product->jumlah_ulasan); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada produk.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($products->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\reports\products.blade.php ENDPATH**/ ?>