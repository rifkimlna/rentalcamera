

<?php $__env->startSection('title', 'Beri Ulasan - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="card bg-white shadow-md">
                <div class="p-5">
                    <h5 class="font-semibold text-lg mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-lineflex="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Beri Ulasan
                    </h5>

                    <div class="text-sm mb-4">
                        <ul>
                            <li><a href="<?php echo e(route('customer.dashboard')); ?>">Dashboard</a></li>
                            <li><a href="<?php echo e(route('customer.reviews.index')); ?>">Ulasan Saya</a></li>
                            <li>Beri Ulasan Baru</li>
                        </ul>
                    </div>

                    <!-- Transaction Information -->
                    <div class="card border border-[#0071e3] mb-4">
                        <div class="p-5">
                            <div class="flex flex-col md:flex-row justify-between gap-3">
                                <div>
                                    <h6 class="font-semibold mb-1">Transaksi: <?php echo e($transaction->kode_transaksi); ?></h6>
                                    <small class="text-[#6e6e73]">
                                        Selesai: <?php echo e(\Carbon\Carbon::parse($transaction->completed_at)->translatedFormat('d M Y')); ?>

                                    </small>
                                </div>
                                <div class="md:text-right">
                                    <h6 class="text-[#6e6e73] mb-0">Rp <?php echo e(number_format($transaction->grand_total, 0, ',', '.')); ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $selectedProductId = old('produk_id', request('product')); ?>
                    <form action="<?php echo e(route('customer.reviews.store')); ?>" method="POST" id="reviewForm" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="transaksi_id" value="<?php echo e($transaction->id); ?>">

                        <!-- Product Selection (Radio - hanya 1 produk per review) -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Pilih Produk yang Akan Diulas *</span>
                            </label>
                            <div class="space-y-3">
                                <?php
                                    $unreviewedDetails = $transaction->detailTransaksis->filter(function($detail) {
                                        return !$detail->hasReview();
                                    });
                                ?>
                                <?php $__currentLoopData = $unreviewedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card bg-[#f5f5f7] transition-all hover:border-[#0071e3] hover:bg-[#0071e3]/5 <?php echo e($selectedProductId == $detail->produk_id ? 'border-2 border-[#0071e3]' : ''); ?>">
                                        <div class="p-5">
                                            <label class="flex items-start gap-3 cursor-pointer w-full">
                                                <input class="radio radio-sm accent-[#0071e3] mt-1" 
                                                       type="radio" 
                                                       name="produk_id" 
                                                       value="<?php echo e($detail->produk_id); ?>"
                                                       <?php echo e($selectedProductId == $detail->produk_id ? 'checked' : ''); ?>

                                                       required>
                                                <div class="flex items-center gap-3 w-full">
                                                    <div class="shrink-0">
                                                        <?php if($detail->produk && $detail->produk->gambar_utama): ?>
                                                            <img src="<?php echo e(asset('storage/' . $detail->produk->gambar_utama)); ?>" 
                                                                 alt="<?php echo e($detail->produk->nama_produk); ?>" 
                                                                 class="rounded w-20 h-20 object-cover">
                                                        <?php else: ?>
                                                            <div class="bg-[#f5f5f7] rounded flex items-center justify-center w-20 h-20">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                    <path stroke-linecap="round" stroke-lineflex="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="font-semibold mb-1"><?php echo e($detail->produk->nama_produk ?? $detail->nama_produk); ?></h6>
                                                        <small class="text-[#6e6e73]">
                                                            <?php if($detail->produk && $detail->produk->brand): ?>
                                                                Brand: <?php echo e($detail->produk->brand->nama_brand); ?><br>
                                                            <?php endif; ?>
                                                            Disewa: <?php echo e($detail->lama_sewa); ?> hari
                                                        </small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php $__errorArgs = ['produk_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-[#6e6e73]"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="border-t border-[#f0f0f2] my-4"></div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Rating *</span>
                            </label>
                            <div class="flex items-center gap-1 justify-center mb-2">
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="1" <?php echo e(old('rating') == 1 ? 'checked' : ''); ?> />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="2" <?php echo e(old('rating') == 2 ? 'checked' : ''); ?> />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="3" <?php echo e(old('rating') == 3 ? 'checked' : ''); ?> />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="4" <?php echo e(old('rating') == 4 ? 'checked' : ''); ?> />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="5" <?php echo e(old('rating') == 5 ? 'checked' : ''); ?> />
                            </div>
                            <div class="text-center">
                                <small class="text-[#6e6e73]">1: Sangat Buruk, 5: Sangat Baik</small>
                            </div>
                            <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-[#6e6e73] block text-center"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Judul Ulasan</span>
                            </label>
                            <input type="text" class="input-apple w-full" name="judul" 
                                   value="<?php echo e(old('judul')); ?>"
                                   placeholder="Contoh: 'Kamera bagus untuk pemula'" maxlength="200">
                            <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-[#6e6e73]"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Komentar *</span>
                            </label>
                            <textarea class="input-apple resize-none w-full" name="komentar" rows="4" 
                                      placeholder="Bagikan pengalaman Anda menggunakan produk ini..."
                                      required><?php echo e(old('komentar')); ?></textarea>
                            <?php $__errorArgs = ['komentar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-[#6e6e73]"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Photo Upload -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Foto Pendukung</span>
                            </label>
                            <input type="file" class="input-apple w-full" name="foto_ulasan[]" 
                                   accept="image/*" multiple>
                            <?php $__errorArgs = ['foto_ulasan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-[#6e6e73]"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-[#6e6e73]">Unggah foto produk saat digunakan (Maks 2MB per foto)</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col gap-2 mt-4">
                            <button type="submit" class="btn-dark-apple btn-block btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-lineflex="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Kirim Ulasan
                            </button>
                            <a href="<?php echo e(route('customer.reviews.index')); ?>" class="btn-outline-apple">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-lineflex="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\reviews\create.blade.php ENDPATH**/ ?>