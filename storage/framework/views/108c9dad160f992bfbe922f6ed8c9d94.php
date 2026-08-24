<?php $__env->startSection('title', 'Paket Layanan - ' . $layanan->nama_layanan); ?>
<?php $__env->startSection('page-title', 'Paket: ' . $layanan->nama_layanan); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <a href="<?php echo e(route('admin.layanan.show', $layanan->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Kembali ke Detail</a>
            <a href="<?php echo e(route('admin.layanan.paket.create', $layanan->id)); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Paket</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <?php if($paket->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $paket->gambar)); ?>" alt="Paket <?php echo e($paket->nama_paket); ?>" class="w-10 h-10 object-cover rounded">
                                <?php endif; ?>
                                <span class="font-medium"><?php echo e($paket->nama_paket); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($paket->durasi_jam); ?> jam</td>
                        <td><?php echo e($paket->harga_formatted); ?></td>
                        <td>
                            <span class="badge <?php echo e($paket->status == 'active' ? 'badge-success' : 'badge-apple'); ?>"><?php echo e($paket->status); ?></span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="<?php echo e(route('admin.layanan.paket.edit', [$layanan->id, $paket->id])); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <form method="POST" action="<?php echo e(route('admin.layanan.paket.destroy', [$layanan->id, $paket->id])); ?>" onsubmit="return confirm('Hapus paket?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada paket</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4"><?php echo e($pakets->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\layanan\paket_index.blade.php ENDPATH**/ ?>