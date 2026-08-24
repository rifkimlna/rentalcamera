<?php $__env->startSection('title', 'Detail Ulasan - Stekpro Multimedia & Broadcast'); ?>
<?php $__env->startSection('page-title', 'Detail Ulasan'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Detail Ulasan</h1>
        <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn-dark-apple-outline-apple">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0faf1] border border-[#d1f5d5] text-[#248a3d] text-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#fef2f2] border border-[#fecaca] text-[#d70015] text-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <?php if($review->produk && $review->produk->gambar_utama): ?>
                                <img src="<?php echo e(asset('storage/' . $review->produk->gambar_utama)); ?>" 
                                     alt="<?php echo e($review->produk->nama_produk); ?>" 
                                     class="rounded w-16 h-16 object-cover">
                            <?php else: ?>
                                <div class="bg-[#f5f5f7] rounded flex items-center justify-center w-16 h-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h5 class="font-bold"><?php echo e($review->produk->nama_produk ?? 'Produk dihapus'); ?></h5>
                                <small class="text-[#6e6e73]">
                                    Transaksi: <?php echo e($review->transaksi->kode_transaksi ?? '-'); ?>

                                </small>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if($review->status == 'pending'): ?>
                                <span class="badge-warning text-sm">Menunggu</span>
                            <?php elseif($review->status == 'approved'): ?>
                                <span class="badge-success text-sm">Disetujui</span>
                            <?php elseif($review->status == 'rejected'): ?>
                                <span class="badge-error text-sm">Ditolak</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-0.5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" class="text-[#ff9500]" disabled <?php echo e($i <= $review->rating ? 'checked' : ''); ?> />
                            <?php endfor; ?>
                        </div>
                        <span class="text-xl font-bold text-[#ff9500]"><?php echo e($review->rating); ?>/5</span>
                    </div>

                    <?php if($review->judul): ?>
                        <h4 class="font-bold text-lg mb-2"><?php echo e($review->judul); ?></h4>
                    <?php endif; ?>

                    <div class="mb-4">
                        <p class="text-base"><?php echo e($review->komentar); ?></p>
                    </div>

                    <?php if($review->foto_ulasan && is_array($review->foto_ulasan) && count($review->foto_ulasan) > 0): ?>
                        <div class="mb-4">
                            <h6 class="text-[#6e6e73] mb-3 text-sm font-semibold">Foto Pendukung:</h6>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <?php $__currentLoopData = $review->foto_ulasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <a href="<?php echo e(asset('storage/' . $photo)); ?>" data-lightbox="admin-review-photos">
                                            <img src="<?php echo e(asset('storage/' . $photo)); ?>" 
                                                 alt="Foto Review" 
                                                 class="rounded w-full h-32 object-cover hover:scale-[1.02] transition-transform">
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="text-[#6e6e73] text-sm">
                        <small>
                            Ditulis oleh <strong><?php echo e($review->user->nama ?? 'Anonim'); ?></strong> 
                            pada <?php echo e(\Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y H:i')); ?>

                        </small>
                    </div>
                </div>
            </div>

            <?php if($review->balasan): ?>
                <div class="bg-white rounded-2xl border border-[#34c759]/30 mb-4">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-semibold mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                Balasan Admin
                            </h5>
                            <?php if($review->balasan_at): ?>
                                <small class="text-[#6e6e73]">
                                    <?php echo e(\Carbon\Carbon::parse($review->balasan_at)->translatedFormat('d M Y H:i')); ?>

                                </small>
                            <?php endif; ?>
                        </div>
                        <p class="mb-0"><?php echo e($review->balasan); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h6 class="font-semibold mb-3">Informasi Transaksi</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <table class="w-full text-sm">
                            <tr>
                                <td class="w-2/5">No. Transaksi:</td>
                                <td><strong><?php echo e($review->transaksi->kode_transaksi ?? '-'); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal Sewa:</td>
                                <td>
                                    <?php echo e($review->transaksi && $review->transaksi->tanggal_pengambilan ? \Carbon\Carbon::parse($review->transaksi->tanggal_pengambilan)->translatedFormat('d M Y') : '-'); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>Durasi:</td>
                                <td><?php echo e($review->transaksi->lama_sewa ?? '-'); ?> hari</td>
                            </tr>
                        </table>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="w-2/5">Total:</td>
                                <td class="text-[#34c759]">
                                    <strong>Rp <?php echo e($review->transaksi ? number_format($review->transaksi->grand_total, 0, ',', '.') : '-'); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Metode Bayar:</td>
                                <td><?php echo e($review->transaksi->paymentMethod->name ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td>Customer:</td>
                                <td><?php echo e($review->transaksi->nama_customer ?? $review->user->nama ?? '-'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <?php if($review->status == 'pending'): ?>
                <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                    <div class="p-5">
                        <h5 class="font-semibold mb-4">Aksi</h5>
                        <div class="flex flex-col gap-3">
                            <form action="<?php echo e(route('admin.reviews.approve', $review->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-[#34c759] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#2db84d] transition-colors w-full" onclick="return confirm('Setujui ulasan ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Ulasan
                                </button>
                            </form>
                            <form action="<?php echo e(route('admin.reviews.reject', $review->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors w-full" onclick="return confirm('Tolak ulasan ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tolak Ulasan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h5 class="font-semibold mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Balas Ulasan
                    </h5>
                    <form action="<?php echo e(route('admin.reviews.reply', $review->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <textarea name="balasan" rows="4" class="input-apple resize-none w-full" 
                                      placeholder="Tulis balasan untuk ulasan ini..."><?php echo e(old('balasan', $review->balasan)); ?></textarea>
                            <?php $__errorArgs = ['balasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-[#d70015]"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <button type="submit" class="btn-dark-apple w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <?php echo e($review->balasan ? 'Perbarui Balasan' : 'Kirim Balasan'); ?>

                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h5 class="font-semibold mb-4">Info User</h5>
                    <div class="flex items-center gap-3 mb-3">
                        <?php if($review->user && $review->user->foto_profile): ?>
                            <img src="<?php echo e(asset('storage/' . $review->user->foto_profile)); ?>" 
                                 alt="<?php echo e($review->user->nama); ?>" 
                                 class="rounded-full w-12 h-12 object-cover">
                        <?php else: ?>
                            <div class="bg-[#f5f5f7] rounded-full flex items-center justify-center w-12 h-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div>
                            <strong><?php echo e($review->user->nama ?? 'Anonim'); ?></strong>
                            <br>
                            <small class="text-[#6e6e73]"><?php echo e($review->user->email ?? '-'); ?></small>
                        </div>
                    </div>
                    <?php if($review->user): ?>
                        <a href="<?php echo e(route('admin.users.show', $review->user->id)); ?>" class="btn-dark-apple-outline-apple !text-sm !px-3 !py-1.5 w-full">
                            Lihat Profile User
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': "Gambar %1 dari %2"
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\reviews\show.blade.php ENDPATH**/ ?>