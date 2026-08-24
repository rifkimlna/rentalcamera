<?php $__env->startSection('title', 'Activity Logs'); ?>
<?php $__env->startSection('page-title', 'Activity Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs uppercase text-[#86868b]">
                        <th class="w-10">#</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Tipe</th>
                        <th>IP Address</th>
                        <th class="w-32">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-[#f5f5f7]/50">
                        <td class="text-[#86868b]"><?php echo e($loop->iteration); ?></td>
                        <td>
                            <div class="font-medium text-sm"><?php echo e($log->user->nama ?? 'User #' . $log->user_id); ?></div>
                            <div class="text-xs text-[#86868b]"><?php echo e($log->user->email ?? ''); ?></div>
                        </td>
                        <td>
                            <span class="text-sm"><?php echo e($log->description); ?></span>
                            <?php if($log->data): ?>
                                <button type="button" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors ms-1" onclick="lihatData(<?php echo e($log->id); ?>)">Lihat Data</button>
                                <pre id="data-<?php echo e($log->id); ?>" class="hidden text-xs bg-[#f5f5f7] p-2 rounded mt-1 max-w-md overflow-x-auto"><?php echo e(json_encode($log->data, JSON_PRETTY_PRINT)); ?></pre>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 border border-[#e5e5e7]"><?php echo e($log->type_label ?? $log->type); ?></span>
                        </td>
                        <td class="text-xs text-[#86868b]"><?php echo e($log->ip_address ?? '-'); ?></td>
                        <td class="text-xs text-[#86868b]"><?php echo e($log->created_at->format('d M Y H:i')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-[#86868b] py-8">Belum ada aktivitas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($logs->hasPages()): ?>
        <div class="p-4 border-t border-[#f0f0f2]"><?php echo e($logs->links()); ?></div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function lihatData(id) {
        const el = document.getElementById('data-' + id);
        el.classList.toggle('hidden');
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\activity-logs.blade.php ENDPATH**/ ?>