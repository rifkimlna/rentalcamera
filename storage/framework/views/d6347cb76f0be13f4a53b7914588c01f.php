<?php $__env->startSection('title', 'Tambah Portfolio'); ?>
<?php $__env->startSection('page-title', 'Tambah Portfolio'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('admin.portfolios.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Judul <span class="text-[#d70015]">*</span></span></label>
                    <input type="text" name="judul" class="input-apple w-full <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('judul')); ?>" required>
                    <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                    <textarea name="deskripsi" class="input-apple resize-none w-full <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> textarea-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('deskripsi')); ?></textarea>
                    <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tipe <span class="text-[#d70015]">*</span></span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="foto" class="w-4 h-4 text-[#0071e3] bg-white border-[#e5e5e7]" <?php echo e(old('tipe') === 'video' ? '' : 'checked'); ?> onchange="toggleTipe()">
                            <span class="text-sm">Foto</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="video" class="w-4 h-4 text-[#0071e3] bg-white border-[#e5e5e7]" <?php echo e(old('tipe') === 'video' ? 'checked' : ''); ?> onchange="toggleTipe()">
                            <span class="text-sm">Video</span>
                        </label>
                    </div>
                    <?php $__errorArgs = ['tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4" id="url_input">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">URL <span class="text-xs text-[#86868b]">(Instagram atau YouTube)</span></span></label>
                    <input type="url" name="url" class="input-apple w-full <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('url')); ?>" placeholder="https://www.instagram.com/p/... atau https://youtube.com/watch?v=...">
                    <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-[#86868b] mt-1">Untuk foto dari Instagram atau video dari YouTube. Biarkan kosong jika upload gambar langsung.</p>
                </div>

                <div class="mb-4" id="gambar_input">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Upload Gambar</span></label>
                    <input type="file" name="gambar" class="input-apple w-full <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-apple-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-[#86868b] mt-1">Format: JPEG, PNG, WebP. Maks 2MB.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Urutan</span></label>
                    <input type="number" name="sort_order" class="input-apple w-full <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('sort_order', 0)); ?>" min="0">
                    <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" class="w-9 h-5 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <span class="text-sm">Aktif</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Simpan</button>
                    <a href="<?php echo e(route('admin.portfolios.index')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleTipe() {
        const tipe = document.querySelector('input[name="tipe"]:checked').value;
        document.getElementById('url_input').style.display = 'block';
    }

    function detectTipeFromUrl() {
        const url = document.querySelector('input[name="url"]').value.trim();

        if (/youtube\.com|youtu\.be/.test(url) || /instagram\.com\/reel/.test(url)) {
            document.querySelector('input[name="tipe"][value="video"]').checked = true;
        } else if (/instagram\.com\/p/.test(url)) {
            document.querySelector('input[name="tipe"][value="foto"]').checked = true;
        }
    }

    document.querySelector('input[name="url"]').addEventListener('input', detectTipeFromUrl);
    toggleTipe();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\portfolios\create.blade.php ENDPATH**/ ?>