<?php $__env->startSection('title', 'Pembayaran Berhasil - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-full bg-[#f5f5f7] flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h1 class="text-2xl tracking-tight font-light mb-2">Pembayaran Berhasil</h1>
            <p class="text-sm text-[#6e6e73] leading-relaxed">Terima kasih, pembayaran Anda telah dikonfirmasi.</p>
        </div>

        <div class="card-apple-static">
            <div class="p-5 space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-xs text-[#6e6e73]">Kode Transaksi</span>
                    <span class="text-sm font-mono tracking-tight"><?php echo e($transaksi->kode_transaksi); ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-xs text-[#6e6e73]">Total Dibayar</span>
                    <span class="text-sm">Rp <?php echo e(number_format($transaksi->grand_total, 0, ',', '.')); ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-xs text-[#6e6e73]">Metode Pembayaran</span>
                    <span class="text-sm"><?php echo e($transaksi->paymentMethod->nama ?? $transaksi->payment_type); ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e5e5e7]">
                    <span class="text-xs text-[#6e6e73]">Tanggal Sewa</span>
                    <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($transaksi->tanggal_pengambilan)->translatedFormat('d M Y')); ?></span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-xs text-[#6e6e73]">Estimasi Pengembalian</span>
                    <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($transaksi->tanggal_pengembalian)->translatedFormat('d M Y')); ?></span>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="<?php echo e(route('customer.dashboard')); ?>" class="btn-dark-apple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\checkout\success.blade.php ENDPATH**/ ?>