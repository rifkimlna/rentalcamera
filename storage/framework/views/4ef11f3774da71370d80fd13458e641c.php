<?php $__env->startSection('title', 'Pembayaran Pending - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full bg-[#f5f5f7] flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl tracking-tight font-light mb-2">Menunggu Pembayaran</h1>
            <p class="text-sm text-[#6e6e73] leading-relaxed">Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran Anda.</p>
        </div>

        <div class="card-apple-static">
            <div class="p-5 space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-sm text-[#6e6e73]">Kode Transaksi</span>
                    <span class="text-sm font-medium"><?php echo e($transaksi->kode_transaksi); ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-sm text-[#6e6e73]">Total Pembayaran</span>
                    <span class="text-sm font-bold text-[#0071e3]">Rp <?php echo e(number_format($transaksi->grand_total, 0, ',', '.')); ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-sm text-[#6e6e73]">Status</span>
                    <span class="badge-apple">Menunggu Pembayaran</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="<?php echo e(route('customer.checkout.payment', $transaksi->id)); ?>" class="btn-dark-apple">
                Lanjutkan Pembayaran
            </a>
            <a href="<?php echo e(route('customer.transactions.show', $transaksi->id)); ?>" class="btn-outline-apple ml-2">
                Lihat Detail Transaksi
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\checkout\pending.blade.php ENDPATH**/ ?>