<?php $__env->startSection('title', 'Detail Voucher - Stekpro Multimedia & Broadcast'); ?>
<?php $__env->startSection('page-title', 'Detail Voucher'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Detail Voucher</h1>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.vouchers.edit', $voucher->id)); ?>" class="bg-[#ff9500] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#e68600] transition-colors">
                Edit Voucher
            </a>
            <a href="<?php echo e(route('admin.vouchers.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-6">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Voucher</h2>
                    <table class="w-full text-sm">
                        <tr>
                            <th class="w-40">Kode Voucher</th>
                            <td><span class="font-mono font-bold text-lg"><?php echo e($voucher->kode_voucher); ?></span></td>
                        </tr>
                        <tr>
                            <th>Nama Voucher</th>
                            <td><?php echo e($voucher->nama_voucher); ?></td>
                        </tr>
                        <tr>
                            <th>Tipe</th>
                            <td><span class="badge-apple"><?php echo e($voucher->type_label); ?></span></td>
                        </tr>
                        <tr>
                            <th>Nilai</th>
                            <td class="font-semibold"><?php echo e($voucher->value_formatted); ?></td>
                        </tr>
                        <tr>
                            <th>Min. Pembelian</th>
                            <td><?php echo e($voucher->min_purchase_formatted); ?></td>
                        </tr>
                        <tr>
                            <th>Maks. Diskon</th>
                            <td><?php echo e($voucher->max_discount_formatted ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Kuota</th>
                            <td><?php echo e($voucher->kuota ?? 'Tidak terbatas'); ?></td>
                        </tr>
                        <tr>
                            <th>Terpakai</th>
                            <td><?php echo e($voucher->kuota_terpakai); ?></td>
                        </tr>
                        <tr>
                            <th>Sisa Kuota</th>
                            <td>
                                <?php if($voucher->kuota !== null): ?>
                                    <?php echo e($voucher->remaining_quota); ?>

                                <?php else: ?>
                                    &infin;
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($voucher->start_date)->format('d F Y H:i')); ?>

                                &mdash;
                                <?php echo e(\Carbon\Carbon::parse($voucher->end_date)->format('d F Y H:i')); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php
                                    $isExpired = now()->gt($voucher->end_date);
                                    $isUpcoming = now()->lt($voucher->start_date);
                                ?>
                                <?php if(!$voucher->is_active): ?>
                                    <span class="badge-apple">Nonaktif</span>
                                <?php elseif($isExpired): ?>
                                    <span class="badge-error">Kedaluwarsa</span>
                                <?php elseif($isUpcoming): ?>
                                    <span class="badge-warning">Akan Datang (<?php echo e($voucher->days_remaining); ?> hari lagi)</span>
                                <?php else: ?>
                                    <span class="badge-success">Aktif (tersisa <?php echo e($voucher->days_remaining); ?> hari)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td><?php echo e($voucher->created_at->format('d F Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Terakhir Diupdate</th>
                            <td><?php echo e($voucher->updated_at->format('d F Y H:i')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Riwayat Penggunaan</h2>
                    <?php if($voucher->usages->count() > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pengguna</th>
                                        <th>Diskon</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $voucher->usages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($usage->user->nama ?? '-'); ?></td>
                                            <td><?php echo e($usage->discount_amount_formatted); ?></td>
                                            <td><?php echo e($usage->created_at->format('d/m/Y H:i')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-[#6e6e73] text-center py-4">Belum ada penggunaan voucher ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Batasan</h2>
                    <table class="w-full text-sm">
                        <tr>
                            <th>Pengguna</th>
                            <td>
                                <?php if($voucher->user): ?>
                                    <?php echo e($voucher->user->nama); ?>

                                <?php else: ?>
                                    <span class="text-[#6e6e73]">Semua pengguna</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                <?php if($voucher->kategori): ?>
                                    <?php echo e($voucher->kategori->nama_kategori); ?>

                                <?php else: ?>
                                    <span class="text-[#6e6e73]">Semua kategori</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Produk</th>
                            <td>
                                <?php if($voucher->produk): ?>
                                    <?php echo e($voucher->produk->nama_produk); ?>

                                <?php else: ?>
                                    <span class="text-[#6e6e73]">Semua produk</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\vouchers\show.blade.php ENDPATH**/ ?>