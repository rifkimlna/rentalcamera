<?php $__env->startSection('title', $studio->nama_studio); ?>
<?php $__env->startSection('page-title', $studio->nama_studio); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <?php if($studio->gambar_utama): ?>
                    <img src="<?php echo e(asset('storage/' . $studio->gambar_utama)); ?>" alt="<?php echo e($studio->nama_studio); ?>" class="w-full rounded mb-3">
                <?php endif; ?>
                <h5 class="font-medium"><?php echo e($studio->nama_studio); ?></h5>
                <p class="text-sm text-[#6e6e73]"><?php echo e($studio->harga_per_jam_formatted); ?> / jam</p>
                <span class="badge <?php echo e($studio->status == 'active' ? 'badge-success' : 'badge-apple'); ?>"><?php echo e($studio->status); ?></span>
                <p class="text-sm mt-2">Total Booking: <?php echo e($studio->bookings_count); ?></p>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <h5 class="font-medium mb-2">Deskripsi</h5>
                <p class="text-sm"><?php echo e($studio->deskripsi ?? 'Tidak ada deskripsi.'); ?></p>

                <h5 class="font-medium mt-4 mb-2">Fasilitas</h5>
                <?php if($studio->fasilitas): ?>
                    <?php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : explode(',', $studio->fasilitas); ?>
                    <div class="flex flex-wrap gap-1">
                        <?php $__currentLoopData = $fasilitasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge-apple border border-[#e5e5e7]"><?php echo e(trim($f)); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-[#6e6e73]">Tidak ada fasilitas.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] mt-4">
            <div class="p-5">
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-medium">Paket Studio</h5>
                    <a href="<?php echo e(route('admin.studio.paket.create', $studio->id)); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Paket</a>
                </div>
                <?php $__empty_1 = true; $__currentLoopData = $studio->paketActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-[#e5e5e7] rounded p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="font-medium text-sm"><?php echo e($paket->nama_paket); ?></h6>
                                <p class="text-xs text-[#6e6e73]"><?php echo e($paket->durasi_jam); ?> jam - <?php echo e($paket->harga_formatted); ?></p>
                            </div>
                            <div class="flex gap-1">
                                <a href="<?php echo e(route('admin.studio.paket.edit', [$studio->id, $paket->id])); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <form method="POST" action="<?php echo e(route('admin.studio.paket.destroy', [$studio->id, $paket->id])); ?>" onsubmit="return confirm('Hapus paket?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-[#6e6e73]">Belum ada paket.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\studio\show.blade.php ENDPATH**/ ?>