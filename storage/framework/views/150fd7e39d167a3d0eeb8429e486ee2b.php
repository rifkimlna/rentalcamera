<?php $__env->startSection('title', 'Edit Voucher - Stekpro Multimedia & Broadcast'); ?>
<?php $__env->startSection('page-title', 'Edit Voucher'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Edit Voucher</h1>
        <a href="<?php echo e(route('admin.vouchers.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#fef2f2] border border-[#fecaca] text-[#d70015] text-sm mb-4">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="<?php echo e(route('admin.vouchers.update', $voucher->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_voucher" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kode Voucher *</span></label>
                        <div class="flex w-full">
                            <input type="text" class="input-apple w-full <?php $__errorArgs = ['kode_voucher'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="kode_voucher" name="kode_voucher" value="<?php echo e(old('kode_voucher', $voucher->kode_voucher)); ?>" required>
                            <button type="button" class="btn-dark-apple  btn-outline" onclick="generateCode()">Generate</button>
                        </div>
                        <?php $__errorArgs = ['kode_voucher'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="nama_voucher" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Voucher *</span></label>
                        <input type="text" class="input-apple w-full <?php $__errorArgs = ['nama_voucher'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="nama_voucher" name="nama_voucher" value="<?php echo e(old('nama_voucher', $voucher->nama_voucher)); ?>" required>
                        <?php $__errorArgs = ['nama_voucher'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="type" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tipe *</span></label>
                        <select class="select-apple w-full <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="type" name="type" required onchange="toggleTypeFields()">
                            <option value="percentage" <?php echo e(old('type', $voucher->type) == 'percentage' ? 'selected' : ''); ?>>Persentase</option>
                            <option value="fixed" <?php echo e(old('type', $voucher->type) == 'fixed' ? 'selected' : ''); ?>>Nominal</option>

                        </select>
                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="value" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nilai *</span></label>
                        <input type="number" step="0.01" min="0" class="input-apple w-full <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="value" name="value" value="<?php echo e(old('value', $voucher->value)); ?>" required>
                        <span class="text-xs text-[#6e6e73]" id="value_hint">Dalam persen (%)</span>
                        <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="min_purchase" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Min. Pembelian</span></label>
                        <input type="number" step="0.01" min="0" class="input-apple w-full <?php $__errorArgs = ['min_purchase'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="min_purchase" name="min_purchase" value="<?php echo e(old('min_purchase', $voucher->min_purchase)); ?>">
                        <?php $__errorArgs = ['min_purchase'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div id="max_discount_wrapper">
                        <label for="max_discount" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Maks. Diskon</span></label>
                        <input type="number" step="0.01" min="0" class="input-apple w-full <?php $__errorArgs = ['max_discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="max_discount" name="max_discount" value="<?php echo e(old('max_discount', $voucher->max_discount)); ?>"
                               placeholder="Kosongkan jika tanpa batas">
                        <span class="text-xs text-[#6e6e73]">Hanya untuk tipe Persentase</span>
                        <?php $__errorArgs = ['max_discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="kuota" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kuota</span></label>
                        <input type="number" min="1" class="input-apple w-full <?php $__errorArgs = ['kuota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="kuota" name="kuota" value="<?php echo e(old('kuota', $voucher->kuota)); ?>"
                               placeholder="Kosongkan jika tanpa batas">
                        <?php $__errorArgs = ['kuota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Mulai *</span></label>
                            <input type="datetime-local" class="input-apple w-full <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="start_date" name="start_date"
                                   value="<?php echo e(old('start_date', \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d\TH:i'))); ?>" required>
                            <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Berakhir *</span></label>
                            <input type="datetime-local" class="input-apple w-full <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="end_date" name="end_date"
                                   value="<?php echo e(old('end_date', \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d\TH:i'))); ?>" required>
                            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#86868b] mb-1.5 cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="is_active" value="1" class="w-11 h-6 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all" <?php echo e(old('is_active', $voucher->is_active) ? 'checked' : ''); ?>>
                            <span class="">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="border-t border-[#f0f0f2]">Batasan (Opsional)</div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="user_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Khusus Pengguna</span></label>
                        <select class="select-apple w-full" id="user_id" name="user_id">
                            <option value="">Semua Pengguna</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id', $voucher->user_id) == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->nama); ?> (<?php echo e($user->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="kategori_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Khusus Kategori</span></label>
                        <select class="select-apple w-full" id="kategori_id" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('kategori_id', $voucher->kategori_id) == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->nama_kategori); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="produk_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Khusus Produk</span></label>
                        <select class="select-apple w-full" id="produk_id" name="produk_id">
                            <option value="">Semua Produk</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>" <?php echo e(old('produk_id', $voucher->produk_id) == $product->id ? 'selected' : ''); ?>>
                                    <?php echo e($product->nama_produk); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <a href="<?php echo e(route('admin.vouchers.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">Batal</a>
                    <button type="submit" class="btn-dark-apple">Perbarui Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('kode_voucher').value = code;
}

function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const valueHint = document.getElementById('value_hint');
    const maxDiscountWrapper = document.getElementById('max_discount_wrapper');

    if (type === 'percentage') {
        valueHint.textContent = 'Dalam persen (%)';
        maxDiscountWrapper.style.display = 'block';
    } else if (type === 'fixed') {
        valueHint.textContent = 'Dalam Rupiah (Rp)';
        maxDiscountWrapper.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\vouchers\edit.blade.php ENDPATH**/ ?>