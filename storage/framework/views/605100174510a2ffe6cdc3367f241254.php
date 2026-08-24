<?php $__env->startSection('title', 'Kelola Layanan'); ?>
<?php $__env->startSection('page-title', 'Kelola Layanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" class="input-apple input-sm" placeholder="Cari layanan..." value="<?php echo e(request('search')); ?>">
                    <select name="status" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                </form>
            </div>
            <a href="<?php echo e(route('admin.layanan.create')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Layanan</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Harga Mulai</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $layanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <?php if($layanan->gambar_utama): ?>
                                    <img src="<?php echo e(asset('storage/' . $layanan->gambar_utama)); ?>" class="w-10 h-10 object-cover rounded" alt="<?php echo e($layanan->nama_layanan); ?>">
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-[#f5f5f7] rounded flex items-center justify-center text-xs">No img</div>
                                <?php endif; ?>
                                <div>
                                    <span class="font-medium"><?php echo e($layanan->nama_layanan); ?></span>
                                    <?php if($layanan->kategori): ?>
                                        <span class="text-xs text-[#86868b] block"><?php echo e($layanan->kategori); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($layanan->harga_mulai_formatted); ?></td>
                        <td><?php echo e($layanan->pakets_count); ?></td>
                        <td>
                            <span class="badge <?php echo e($layanan->status == 'active' ? 'badge-success' : 'badge-apple'); ?>"><?php echo e($layanan->status); ?></span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="<?php echo e(route('admin.layanan.show', $layanan->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                <a href="<?php echo e(route('admin.layanan.edit', $layanan->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <a href="<?php echo e(route('admin.layanan.paket.index', $layanan->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Paket</a>
                                <form method="POST" action="<?php echo e(route('admin.layanan.destroy', $layanan->id)); ?>" onsubmit="return confirm('Hapus layanan ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada layanan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4"><?php echo e($layanans->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/admin/layanan/index.blade.php ENDPATH**/ ?>