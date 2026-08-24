<?php $__env->startSection('title', 'Booking Berhasil'); ?>
<?php $__env->startSection('page-title', 'Booking Berhasil'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4">
    <div class="card-apple-static">
        <div class="p-6 text-center">
            <?php if($booking->payment_status == 'paid'): ?>
            <div class="text-[#6e6e73] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Pembayaran Berhasil!</h4>
            <p class="text-sm text-[#6e6e73] mb-4">Booking studio kamu telah dikonfirmasi.</p>
            <?php else: ?>
            <div class="text-[#6e6e73] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-medium mb-2">Booking Dibuat!</h4>
            <p class="text-sm text-[#6e6e73] mb-4">Booking kamu menunggu pembayaran.</p>
            <?php endif; ?>

            <div class="text-left border border-[#e5e5e7] rounded-xl p-3 mb-4">
                <table class="w-full text-sm">
                    <tr><td class="font-medium py-1">Studio</td><td class="py-1"><?php echo e($booking->studio->nama_studio); ?></td></tr>
                    <tr><td class="font-medium py-1">Tipe</td><td class="py-1"><?php echo e($booking->tipe_booking_label); ?></td></tr>
                    <?php if($booking->paketStudio): ?>
                    <tr><td class="font-medium py-1">Paket</td><td class="py-1"><?php echo e($booking->paketStudio->nama_paket); ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="font-medium py-1">Tanggal</td><td class="py-1"><?php echo e($booking->tanggal_booking->format('d M Y')); ?></td></tr>
                    <tr><td class="font-medium py-1">Jam</td><td class="py-1"><?php echo e($booking->jam_mulai->format('H:i')); ?> - <?php echo e($booking->jam_selesai->format('H:i')); ?></td></tr>
                    <tr><td class="font-medium py-1">Durasi</td><td class="py-1"><?php echo e($booking->durasi_jam); ?> jam</td></tr>
                    <?php if($booking->diskon_voucher > 0): ?>
                    <tr><td class="font-medium py-1">Diskon Voucher</td><td class="text-[#6e6e73] py-1">-<?php echo e($booking->diskon_voucher_formatted); ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="font-medium py-1">Grand Total</td><td class="font-bold py-1"><?php echo e($booking->grand_total_formatted); ?></td></tr>
                    <tr><td class="font-medium py-1">Status</td><td class="py-1"><span class="badge-apple <?php echo e($booking->payment_status_badge); ?>"><?php echo e($booking->payment_status_label); ?></span></td></tr>
                </table>
            </div>

            <?php if($booking->payment_status != 'paid'): ?>
            <a href="<?php echo e(route('customer.studio.payment', $booking->id)); ?>" class="btn-dark-apple mb-2">Bayar Sekarang</a>
            <?php endif; ?>

            <div class="flex gap-2 justify-center">
                <a href="<?php echo e(route('customer.studio.my-bookings')); ?>" class="btn-outline-apple">Booking Saya</a>
                <a href="<?php echo e(route('customer.studio.index')); ?>" class="btn-outline-apple">Kembali</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\studio\booking_success.blade.php ENDPATH**/ ?>