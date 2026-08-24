<?php $__env->startSection('title', 'Cetak Booking Layanan'); ?>
<?php $__env->startSection('page-title', 'Cetak Booking'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto" id="print-area">
    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-6">
            <div class="text-center mb-6">
                <h3 class="text-base font-bold">Stekpro Multimedia & Broadcast</h3>
                <p class="text-xs text-[#6e6e73]">Booking Layanan</p>
            </div>

            <table class="w-full text-sm mb-4">
                <tr>
                    <td class="font-medium w-32">Nama Penyewa</td>
                    <td><?php echo e($booking->user->nama ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="font-medium">Layanan</td>
                    <td><?php echo e($booking->layanan->nama_layanan ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="font-medium">Tipe</td>
                    <td><?php echo e($booking->tipe_booking_label); ?></td>
                </tr>
                <?php if($booking->paketLayanan): ?>
                <tr>
                    <td class="font-medium">Paket</td>
                    <td><?php echo e($booking->paketLayanan->nama_paket); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="font-medium">Tanggal</td>
                    <td><?php echo e($booking->tanggal_booking->format('d M Y')); ?></td>
                </tr>
                <tr>
                    <td class="font-medium">Jam</td>
                    <td><?php echo e($booking->jam_mulai->format('H:i')); ?> - <?php echo e($booking->jam_selesai->format('H:i')); ?></td>
                </tr>
                <tr>
                    <td class="font-medium">Durasi</td>
                    <td><?php echo e($booking->durasi_jam); ?> jam</td>
                </tr>
                <tr>
                    <td class="font-medium">Total Harga</td>
                    <td>Rp <?php echo e(number_format($booking->total_harga, 0, ',', '.')); ?></td>
                </tr>
                <?php if($booking->diskon_voucher > 0): ?>
                <tr>
                    <td class="font-medium">Diskon Voucher</td>
                    <td class="text-[#34c759]">-Rp <?php echo e(number_format($booking->diskon_voucher, 0, ',', '.')); ?></td>
                </tr>
                <?php endif; ?>
                <?php if($booking->admin_fee > 0): ?>
                <tr>
                    <td class="font-medium">Biaya Admin</td>
                    <td>Rp <?php echo e(number_format($booking->admin_fee, 0, ',', '.')); ?></td>
                </tr>
                <?php endif; ?>
                <tr class="font-bold">
                    <td>Grand Total</td>
                    <td>Rp <?php echo e(number_format($booking->grand_total, 0, ',', '.')); ?></td>
                </tr>
                <tr>
                    <td class="font-medium">Status Pembayaran</td>
                    <td><?php echo e($booking->payment_status_label); ?></td>
                </tr>
                <tr>
                    <td class="font-medium">Status</td>
                    <td><?php echo e($booking->status_label); ?></td>
                </tr>
            </table>

            <?php if($booking->catatan): ?>
            <div class="mb-3">
                <p class="text-xs font-medium">Catatan:</p>
                <p class="text-xs"><?php echo e($booking->catatan); ?></p>
            </div>
            <?php endif; ?>

            <div class="text-center text-xs text-[#86868b] mt-6">
                Dicetak pada <?php echo e(now()->format('d M Y H:i')); ?>

            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <button onclick="window.print()" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cetak</button>
    <a href="<?php echo e(route('admin.layanan.bookings')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Kembali</a>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    window.onload = function() { window.print(); }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\layanan\print.blade.php ENDPATH**/ ?>