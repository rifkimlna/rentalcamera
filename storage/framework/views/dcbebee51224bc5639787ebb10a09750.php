<?php $__env->startSection('title', 'Equipment'); ?>
<?php $__env->startSection('page-title', 'Equipment'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex flex-wrap gap-2">
                    <input type="text" name="search" class="input-apple input-sm" placeholder="Cari equipment..." value="<?php echo e(request('search')); ?>">
                    <select name="kategori_id" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('kategori_id') == $category->id ? 'selected' : ''); ?>><?php echo e($category->nama_kategori); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select name="status" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" <?php echo e(request('status') == 'available' ? 'selected' : ''); ?>>Tersedia</option>
                        <option value="unavailable" <?php echo e(request('status') == 'unavailable' ? 'selected' : ''); ?>>Tidak Tersedia</option>
                    </select>
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                    <?php if(request('search') || request('kategori_id') || request('status')): ?>
                        <a href="<?php echo e(route('admin.products.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Equipment</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Kategori</th>
                        <th>Harga/Hari</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <?php if($product->gambar_utama): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->gambar_utama)); ?>" class="w-10 h-10 object-cover rounded" alt="<?php echo e($product->nama_produk); ?>">
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-[#f5f5f7] rounded flex items-center justify-center text-xs">No img</div>
                                <?php endif; ?>
                                <div>
                                    <span class="font-medium"><?php echo e($product->nama_produk); ?></span>
                                    <span class="text-xs text-[#86868b] block"><?php echo e($product->kode_produk); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($product->kategori->nama_kategori ?? '-'); ?></td>
                        <td>Rp <?php echo e(number_format($product->harga_per_hari, 0, ',', '.')); ?></td>
                        <td>
                            <div class="flex flex-col text-xs">
                                <span class="<?php echo e($product->stok_tersedia > 0 ? 'text-[#34c759]' : 'text-[#d70015]'); ?>"><?php echo e($product->stok_tersedia); ?> tersedia</span>
                                <span class="text-[#86868b]"><?php echo e($product->stok_total); ?> total</span>
                            </div>
                        </td>
                        <td>
                            <?php
                                $statusColors = [
                                    'available' => 'badge-success',
                                    'unavailable' => 'badge-apple',
                                ];
                                $statusTexts = [
                                    'available' => 'Tersedia',
                                    'unavailable' => 'Tidak Tersedia',
                                ];
                            ?>
                            <span class="badge <?php echo e($statusColors[$product->status] ?? 'badge-apple'); ?>"><?php echo e($statusTexts[$product->status] ?? $product->status); ?></span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="<?php echo e(route('admin.products.show', $product->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#ff9500]">Edit</a>
                                <form method="POST" action="<?php echo e(route('admin.products.destroy', $product->id)); ?>" onsubmit="return confirm('Hapus equipment ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-[#6e6e73] py-4">Belum ada equipment</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4"><?php echo e($products->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\products\index.blade.php ENDPATH**/ ?>