<?php $__env->startSection('title', 'Kelola Studio'); ?>
<?php $__env->startSection('page-title', 'Kelola Studio'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" class="input-apple input-sm" placeholder="Cari studio..." value="<?php echo e(request('search')); ?>">
                    <select name="status" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                </form>
            </div>
            <a href="<?php echo e(route('admin.studio.create')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Studio</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Studio</th>
                        <th>Harga/Jam</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $studios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <?php if($studio->gambar_utama): ?>
                                    <img src="<?php echo e(asset('storage/' . $studio->gambar_utama)); ?>" class="w-10 h-10 object-cover rounded" alt="<?php echo e($studio->nama_studio); ?>">
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-[#f5f5f7] rounded flex items-center justify-center text-xs">No img</div>
                                <?php endif; ?>
                                <div>
                                    <span class="font-medium"><?php echo e($studio->nama_studio); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($studio->harga_per_jam_formatted); ?></td>
                        <td><?php echo e($studio->pakets_count); ?></td>
                        <td>
                            <span class="badge <?php echo e($studio->status == 'active' ? 'badge-success' : 'badge-apple'); ?>"><?php echo e($studio->status); ?></span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="<?php echo e(route('admin.studio.show', $studio->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                <a href="<?php echo e(route('admin.studio.edit', $studio->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <a href="<?php echo e(route('admin.studio.paket.index', $studio->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Paket</a>
                                <form method="POST" action="<?php echo e(route('admin.studio.destroy', $studio->id)); ?>" onsubmit="return confirm('Hapus studio ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada studio</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4"><?php echo e($studios->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\studio\index.blade.php ENDPATH**/ ?>