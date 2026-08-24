<?php $__env->startSection('title', 'Edit Layanan'); ?>
<?php $__env->startSection('page-title', 'Edit Layanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2] max-w-2xl">
    <div class="p-5">
        <form method="POST" action="<?php echo e(route('admin.layanan.update', $layanan->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Layanan *</span></label>
                <input type="text" name="nama_layanan" class="input-apple w-full" value="<?php echo e(old('nama_layanan', $layanan->nama_layanan)); ?>" required>
                <?php $__errorArgs = ['nama_layanan'];
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
                <textarea name="deskripsi" class="input-apple resize-none w-full" rows="4"><?php echo e(old('deskripsi', $layanan->deskripsi)); ?></textarea>
                <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kategori</span></label>
                <select name="kategori" class="select-apple w-full">
                    <option value="">Pilih Kategori</option>
                    <option value="Prewedding" <?php echo e(old('kategori', $layanan->kategori) == 'Prewedding' ? 'selected' : ''); ?>>Prewedding</option>
                    <option value="Wedding" <?php echo e(old('kategori', $layanan->kategori) == 'Wedding' ? 'selected' : ''); ?>>Wedding</option>
                    <option value="Live Streaming" <?php echo e(old('kategori', $layanan->kategori) == 'Live Streaming' ? 'selected' : ''); ?>>Live Streaming</option>
                    <option value="Videografi" <?php echo e(old('kategori', $layanan->kategori) == 'Videografi' ? 'selected' : ''); ?>>Videografi</option>
                    <option value="Dokumentasi Event" <?php echo e(old('kategori', $layanan->kategori) == 'Dokumentasi Event' ? 'selected' : ''); ?>>Dokumentasi Event</option>
                    <option value="Fotobooth" <?php echo e(old('kategori', $layanan->kategori) == 'Fotobooth' ? 'selected' : ''); ?>>Fotobooth</option>
                    <option value="Drone" <?php echo e(old('kategori', $layanan->kategori) == 'Drone' ? 'selected' : ''); ?>>Drone</option>
                </select>
                <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga Mulai *</span></label>
                <input type="number" name="harga_mulai" class="input-apple w-full" value="<?php echo e(old('harga_mulai', $layanan->harga_mulai)); ?>" required>
                <?php $__errorArgs = ['harga_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Ikon (emoji/icon class)</span></label>
                <input type="text" name="ikon" class="input-apple w-full" value="<?php echo e(old('ikon', $layanan->ikon)); ?>" placeholder="📸">
                <?php $__errorArgs = ['ikon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar Utama</span></label>
                <?php if($layanan->gambar_utama): ?>
                    <div class="mb-2">
                        <img src="<?php echo e(asset('storage/' . $layanan->gambar_utama)); ?>" alt="<?php echo e($layanan->nama_layanan); ?>" class="w-24 h-24 object-cover rounded">
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar_utama" class="input-apple w-full" accept="image/*">
                <?php $__errorArgs = ['gambar_utama'];
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
                    <option value="active" <?php echo e(old('status', $layanan->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(old('status', $layanan->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>

            <div class="text-right">
                <a href="<?php echo e(route('admin.layanan.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors mr-2">Batal</a>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\layanan\edit.blade.php ENDPATH**/ ?>