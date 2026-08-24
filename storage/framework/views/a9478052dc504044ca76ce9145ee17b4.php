<?php $__env->startSection('title', 'Manajemen Transaksi - Stekpro Multimedia & Broadcast'); ?>
<?php $__env->startSection('page-title', 'Manajemen Transaksi'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Manajemen Transaksi</h1>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.transactions.create.manual')); ?>" class="btn-dark-apple !text-sm !px-3 !py-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Transaksi Manual
            </a>
            <a href="<?php echo e(route('admin.transactions.export')); ?>" class="btn-dark-apple-outline-apple btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
        <div class="p-5">
            <form method="GET" action="<?php echo e(route('admin.transactions.index')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Cari</span></label>
                    <input type="text" class="input-apple w-full input-sm" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nama atau kode...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status Transaksi</span></label>
                    <select class="select-apple w-full select-sm" name="status_transaksi">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $statusTransaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('status_transaksi') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status Pembayaran</span></label>
                    <select class="select-apple w-full select-sm" name="status_pembayaran">
                        <option value="">Semua</option>
                        <?php $__currentLoopData = $statusPembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('status_pembayaran') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Mulai</span></label>
                    <input type="date" class="input-apple w-full input-sm" name="start_date" value="<?php echo e(request('start_date')); ?>">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tanggal Akhir</span></label>
                    <input type="date" class="input-apple w-full input-sm" name="end_date" value="<?php echo e(request('end_date')); ?>">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Filter</button>
                    <a href="<?php echo e(route('admin.transactions.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Tipe</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $paginated; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration + ($paginated->currentPage() - 1) * $paginated->perPage()); ?></td>
                                <td>
                                    <strong class="text-xs font-mono"><?php echo e($transaction->kode); ?></strong>
                                </td>
                                <td>
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 <?php echo e($transaction->tipe == 'sewa_kamera' ? 'badge-brand' : ($transaction->tipe == 'studio' ? 'badge-brand' : 'badge-apple')); ?>">
                                        <?php echo e($transaction->tipe_label); ?>

                                    </span>
                                </td>
                                <td>
                                    <strong class="text-sm"><?php echo e($transaction->nama_pelanggan); ?></strong>
                                    <div class="text-xs text-[#6e6e73]"><?php echo e($transaction->user->email ?? ($transaction->email_customer ?? '')); ?></div>
                                </td>
                                <td>
                                    <div class="text-xs"><?php echo e($transaction->created_at ? $transaction->created_at->format('d M Y H:i') : '-'); ?></div>
                                </td>
                                <td>
                                    <strong class="text-[#0071e3] text-xs">Rp <?php echo e(number_format($transaction->grand_total ?? $transaction->total_harga ?? 0, 0, ',', '.')); ?></strong>
                                </td>
                                <td>
                                    <?php
                                        $sColors = ['pending' => 'badge-warning', 'confirmed' => 'badge-success', 'completed' => 'badge-brand', 'cancelled' => 'badge-apple', 'menunggu_pembayaran' => 'badge-warning', 'dikonfirmasi' => 'badge-brand', 'siap_diambil' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                                        $sLabels = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'menunggu_pembayaran' => 'Menunggu Bayar', 'dikonfirmasi' => 'Dikonfirmasi', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'];
                                    ?>
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 <?php echo e($sColors[$transaction->status_global] ?? 'badge-apple'); ?>">
                                        <?php echo e($sLabels[$transaction->status_global] ?? $transaction->status_global); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $pColors = ['pending' => 'badge-warning', 'paid' => 'badge-success', 'settlement' => 'badge-success', 'capture' => 'badge-brand', 'failed' => 'badge-error', 'expired' => 'badge-apple', 'cancelled' => 'badge-apple', 'deny' => 'badge-error'];
                                    ?>
                                    <span class="badge-apple !text-[10px] !px-2 !py-0.5 <?php echo e($pColors[$transaction->status_bayar] ?? 'badge-apple'); ?>">
                                        <?php echo e(ucfirst($transaction->status_bayar ?? 'pending')); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e($transaction->detail_link); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-[#6e6e73]">Tidak ada transaksi ditemukan</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-4">
                <div>
                    <p class="text-sm text-[#6e6e73]">
                        Menampilkan <?php echo e($paginated->firstItem()); ?> - <?php echo e($paginated->lastItem()); ?> dari <?php echo e($paginated->total()); ?> transaksi
                    </p>
                </div>
                <div>
                    <?php echo e($paginated->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\transactions\index.blade.php ENDPATH**/ ?>