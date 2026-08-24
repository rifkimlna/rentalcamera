<?php $__env->startSection('title', 'Tambah Paket - ' . $layanan->nama_layanan); ?>
<?php $__env->startSection('page-title', 'Tambah Paket: ' . $layanan->nama_layanan); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2] max-w-2xl">
    <div class="p-5">
        <form method="POST" action="<?php echo e(route('admin.layanan.paket.store', $layanan->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Paket *</span></label>
                <input type="text" name="nama_paket" class="input-apple w-full" value="<?php echo e(old('nama_paket')); ?>" required>
                <?php $__errorArgs = ['nama_paket'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" class="input-apple resize-none w-full" rows="3"><?php echo e(old('deskripsi')); ?></textarea>
                <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="mb-3">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga Paket *</span></label>
                    <input type="number" name="harga" class="input-apple w-full" value="<?php echo e(old('harga')); ?>" required>
                    <?php $__errorArgs = ['harga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Durasi (jam) *</span></label>
                    <input type="number" name="durasi_jam" class="input-apple w-full" value="<?php echo e(old('durasi_jam', 2)); ?>" required min="1">
                    <?php $__errorArgs = ['durasi_jam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Include (pisahkan dengan koma)</span></label>
                <textarea name="include" class="input-apple resize-none w-full" rows="3" placeholder="Kamera, Videografer, Editor, ..."><?php echo e(old('include')); ?></textarea>
                <?php $__errorArgs = ['include'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar</span></label>
                <input type="file" name="gambar" class="input-apple w-full" accept="image/*">
                <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" class="select-apple w-full">
                    <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>

            <div class="text-right">
                <a href="<?php echo e(route('admin.layanan.paket.index', $layanan->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors mr-2">Batal</a>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\layanan\paket_create.blade.php ENDPATH**/ ?>