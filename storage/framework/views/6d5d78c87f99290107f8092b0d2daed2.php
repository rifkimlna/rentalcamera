<?php $__env->startSection('title', 'Laporan User'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-lg font-semibold">Laporan User</h1>
        <form action="<?php echo e(route('admin.reports.users')); ?>" method="GET" class="flex items-center gap-2">
            <input type="date" class="input-apple input-xs w-36" name="start_date" value="<?php echo e($startDate->format('Y-m-d')); ?>">
            <span class="text-xs text-[#86868b]">s/d</span>
            <input type="date" class="input-apple input-xs w-36" name="end_date" value="<?php echo e($endDate->format('Y-m-d')); ?>">
            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Tampilkan</button>
            <a href="<?php echo e(route('admin.reports.users')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Reset</a>
            <a href="<?php echo e(route('admin.reports.export', ['type' => 'customers']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors btn-outline">Export</a>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="pb-2 font-medium">Nama</th>
                        <th class="pb-2 font-medium">Email</th>
                        <th class="pb-2 font-medium">Telepon</th>
                        <th class="pb-2 font-medium">Kota</th>
                        <th class="pb-2 font-medium text-right">Transaksi (Periode)</th>
                        <th class="pb-2 font-medium text-right">Poin</th>
                        <th class="pb-2 font-medium">Status</th>
                        <th class="pb-2 font-medium">Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-medium"><?php echo e($user->nama); ?></td>
                        <td class="py-2"><?php echo e($user->email); ?></td>
                        <td class="py-2"><?php echo e($user->telepon ?? '-'); ?></td>
                        <td class="py-2"><?php echo e($user->kota ?? '-'); ?></td>
                        <td class="py-2 text-right"><?php echo e($user->transaksis_count); ?></td>
                        <td class="py-2 text-right"><?php echo e($user->poin_reward); ?></td>
                        <td class="py-2">
                            <?php if($user->status === 'active'): ?>
                            <span class="badge-success badge-xs">Aktif</span>
                            <?php elseif($user->status === 'suspended'): ?>
                            <span class="badge-error badge-xs">Ditangguhkan</span>
                            <?php elseif($user->status === 'pending_verification'): ?>
                            <span class="badge-warning badge-xs">Menunggu Verifikasi</span>
                            <?php else: ?>
                            <span class="badge-apple badge-xs"><?php echo e($user->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2"><?php echo e($user->created_at->format('d M Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada user customer.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\reports\users.blade.php ENDPATH**/ ?>