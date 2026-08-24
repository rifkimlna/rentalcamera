<?php $__env->startSection('title', 'Laporan Transaksi'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-lg font-semibold">Laporan Transaksi</h1>
        <form action="<?php echo e(route('admin.reports.transactions')); ?>" method="GET" class="flex items-center gap-2">
            <input type="date" class="input-apple input-xs w-36" name="start_date" value="<?php echo e($startDate->format('Y-m-d')); ?>">
            <span class="text-xs text-[#86868b]">s/d</span>
            <input type="date" class="input-apple input-xs w-36" name="end_date" value="<?php echo e($endDate->format('Y-m-d')); ?>">
            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Tampilkan</button>
            <a href="<?php echo e(route('admin.reports.transactions')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Reset</a>
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'transactions']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors btn-outline">Export</a>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="pb-2 font-medium">Kode</th>
                        <th class="pb-2 font-medium">Tanggal</th>
                        <th class="pb-2 font-medium">Customer</th>
                        <th class="pb-2 font-medium">Metode</th>
                        <th class="pb-2 font-medium text-right">Total</th>
                        <th class="pb-2 font-medium">Status</th>
                        <th class="pb-2 font-medium text-right">Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-medium"><?php echo e($transaction->kode_transaksi); ?></td>
                        <td class="py-2"><?php echo e($transaction->created_at->format('d M Y H:i')); ?></td>
                        <td class="py-2">
                            <div class="font-medium"><?php echo e($transaction->nama_customer); ?></div>
                            <div class="text-gray-400"><?php echo e($transaction->email_customer); ?></div>
                        </td>
                        <td class="py-2"><?php echo e($transaction->paymentMethod ? $transaction->paymentMethod->name : '-'); ?></td>
                        <td class="py-2 text-right font-medium">Rp <?php echo e(number_format($transaction->grand_total, 0, ',', '.')); ?></td>
                        <td class="py-2"><?php echo e($transaction->status_transaksi); ?></td>
                        <td class="py-2 text-right"><?php if($transaction->paid_at): ?><?php echo e($transaction->paid_at->format('d M Y H:i')); ?><?php else: ?>-<?php endif; ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($transactions->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\reports\transactions.blade.php ENDPATH**/ ?>