<?php $__env->startSection('title', 'Booking Studio'); ?>
<?php $__env->startSection('page-title', 'Booking Studio'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
    <div class="py-3 px-4">
        <form method="GET" class="flex flex-wrap gap-x-3 gap-y-2 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Cari</span></label>
                <input type="text" name="search" class="input-apple input-sm w-full" placeholder="Nama user atau studio..." value="<?php echo e(request('search')); ?>">
            </div>
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Studio</span></label>
                <select name="studio_id" class="select-apple !text-sm">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $studios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e(request('studio_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->nama_studio); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Status</span></label>
                <select name="status" class="select-apple !text-sm">
                    <option value="">Semua</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Selesai</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5 py-0 mb-0.5"><span class="text-xs">Pembayaran</span></label>
                <select name="payment_status" class="select-apple !text-sm">
                    <option value="">Semua</option>
                    <option value="pending" <?php echo e(request('payment_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="paid" <?php echo e(request('payment_status') == 'paid' ? 'selected' : ''); ?>>Lunas</option>
                    <option value="failed" <?php echo e(request('payment_status') == 'failed' ? 'selected' : ''); ?>>Gagal</option>
                    <option value="expired" <?php echo e(request('payment_status') == 'expired' ? 'selected' : ''); ?>>Kedaluwarsa</option>
                    <option value="refunded" <?php echo e(request('payment_status') == 'refunded' ? 'selected' : ''); ?>>Refund</option>
                </select>
            </div>
            <div class="flex gap-1">
                <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                <a href="<?php echo e(route('admin.studio.bookings')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs uppercase text-[#86868b]">
                        <th class="w-10">#</th>
                        <th>Penyewa</th>
                        <th>Studio</th>
                        <th class="hidden lg:table-cell">Detail</th>
                        <th class="text-right">Total</th>
                        <th>Pembayaran</th>
                        <th class="hidden md:table-cell">Status</th>
                        <th class="w-[140px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-[#f5f5f7]/50">
                        <td class="text-[#86868b]"><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div class="font-medium text-sm"><?php echo e($booking->user->nama ?? '-'); ?></div>
                            <div class="text-xs text-[#86868b]"><?php echo e($booking->user->email ?? ''); ?></div>
                        </td>
                        <td>
                            <div class="text-sm"><?php echo e($booking->studio->nama_studio ?? '-'); ?></div>
                            <div class="text-xs text-[#86868b]"><?php echo e($booking->tipe_booking_label); ?></div>
                        </td>
                        <td class="hidden lg:table-cell text-xs text-[#6e6e73]">
                            <div><?php echo e($booking->tanggal_booking->format('d M Y')); ?></div>
                            <div><?php echo e($booking->jam_mulai->format('H:i')); ?> - <?php echo e($booking->jam_selesai->format('H:i')); ?></div>
                            <div><?php echo e($booking->durasi_jam); ?> jam</div>
                        </td>
                        <td class="text-right">
                            <div class="font-medium text-sm"><?php echo e(number_format($booking->grand_total, 0, ',', '.')); ?></div>
                            <?php if($booking->admin_fee > 0): ?>
                            <div class="text-xs text-[#86868b]">+<?php echo e(number_format($booking->admin_fee, 0, ',', '.')); ?></div>
                            <?php endif; ?>
                            <?php if($booking->diskon_voucher > 0): ?>
                            <div class="text-xs text-[#34c759]">Diskon: -<?php echo e(number_format($booking->diskon_voucher, 0, ',', '.')); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="text-xs text-[#6e6e73] leading-tight"><?php echo e($booking->paymentMethod->name ?? '-'); ?></div>
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 <?php echo e($booking->payment_status_badge); ?>"><?php echo e($booking->payment_status_label); ?></span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 <?php echo e($booking->status == 'confirmed' ? 'badge-success' : ($booking->status == 'pending' ? 'badge-warning' : ($booking->status == 'completed' ? 'badge-brand' : 'badge-apple'))); ?>">
                                <?php echo e($booking->status_label); ?>

                            </span>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('admin.studio.booking.update-status', $booking->id)); ?>" class="flex gap-1">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="status" value="<?php echo e($booking->status); ?>">
                                <input type="hidden" name="payment_status" value="<?php echo e($booking->payment_status); ?>">
                                <?php if($booking->status == 'pending'): ?>
                                    <button type="submit" name="status" value="confirmed" class="btn-dark-apple !text-[10px] !px-2 !py-0.5" onclick="return confirm('Konfirmasi booking ini?')">Confirm</button>
                                <?php endif; ?>
                                <?php if($booking->status == 'confirmed'): ?>
                                    <button type="submit" name="status" value="completed" class="btn-dark-apple !text-[10px] !px-2 !py-0.5" onclick="return confirm('Tandai selesai?')">Selesai</button>
                                <?php endif; ?>
                                <?php if(in_array($booking->status, ['pending', 'confirmed'])): ?>
                                    <button type="submit" name="status" value="cancelled" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]" onclick="return confirm('Batalkan booking ini?')">Batal</button>
                                <?php endif; ?>
                                <?php if($booking->payment_status == 'pending'): ?>
                                    <button type="submit" name="payment_status" value="paid" class="btn-dark-apple !text-[10px] !px-2 !py-0.5" onclick="return confirm('Tandai sudah bayar?')">Bayar</button>
                                <?php endif; ?>
                                <?php if($booking->payment_status == 'paid'): ?>
                                    <button type="submit" name="payment_status" value="refunded" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#ff9500]" onclick="return confirm('Refund pembayaran?')">Refund</button>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.studio.booking.print', $booking->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors" target="_blank" title="Cetak Struk">Cetak</a>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-[#86868b] py-8">Belum ada booking studio</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($bookings->hasPages()): ?>
        <div class="p-4 border-t border-[#f0f0f2]"><?php echo e($bookings->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\studio\bookings.blade.php ENDPATH**/ ?>