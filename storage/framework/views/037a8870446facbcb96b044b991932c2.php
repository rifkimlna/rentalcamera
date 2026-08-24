<?php $__env->startSection('title', 'Notifikasi - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('page-title', 'Notifikasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.notification-icon { width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border-radius: 9999px; flex-shrink: 0; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex items-center gap-2">
        <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'all'])); ?>" class="rounded-xl px-4 py-2 text-sm font-medium transition-all <?php echo e(request('filter', 'all') === 'all' ? 'bg-[#1d1d1f] text-white' : 'text-[#6e6e73] hover:bg-[#f5f5f7]'); ?>">Semua</a>
        <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'unread'])); ?>" class="rounded-xl px-4 py-2 text-sm font-medium transition-all <?php echo e(request('filter') === 'unread' ? 'bg-[#1d1d1f] text-white' : 'text-[#6e6e73] hover:bg-[#f5f5f7]'); ?>">Belum Dibaca</a>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card-apple-static">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <?php
                    $icons = [
                        'transaction' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                        'payment' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
                        'system' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                        'promotion' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>',
                    ];
                ?>
                <div class="notification-icon bg-[#f5f5f7] text-[#6e6e73]">
                    <?php echo $icons[$notification->type] ?? $icons['system']; ?>

                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium <?php echo e(!$notification->is_read ? 'text-[#1d1d1f]' : 'text-[#6e6e73]'); ?>">
                                <?php echo e($notification->title); ?>

                            </p>
                            <p class="text-xs text-[#86868b] mt-0.5"><?php echo e($notification->message); ?></p>
                        </div>
                        <span class="text-xs text-[#86868b] whitespace-nowrap"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge-apple !text-[10px] !px-2 !py-0.5 border border-[#e5e5e7]"><?php echo e($notification->type_label); ?></span>
                        <?php if(!$notification->is_read): ?>
                        <span class="badge-apple !text-[10px] !px-2 !py-0.5 bg-[#e5e5e7] text-[#6e6e73] border-0">Baru</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card-apple-static">
        <div class="p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-sm text-[#6e6e73]">Tidak ada notifikasi</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex justify-center">
        <?php echo e($notifications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\dashboard\notifications.blade.php ENDPATH**/ ?>