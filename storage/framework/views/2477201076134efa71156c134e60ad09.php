<?php
    $flashTypes = [
        'success' => [
            'bg' => 'bg-[#f0faf1] border-[#d1f5d5] text-[#248a3d]',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'persistent' => false,
        ],
        'error' => [
            'bg' => 'bg-[#fef2f2] border-[#fecaca] text-[#d70015]',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'persistent' => true,
        ],
        'warning' => [
            'bg' => 'bg-[#fffbeb] border-[#fde68a] text-[#b45309]',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>',
            'persistent' => true,
        ],
        'info' => [
            'bg' => 'bg-[#eff6ff] border-[#bfdbfe] text-[#1d4ed8]',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'persistent' => false,
        ],
    ];
?>

<?php if(session('success') || session('error') || session('warning') || session('info') || $errors->any()): ?>
    <div class="space-y-3 mb-5" aria-live="polite">
        <?php $__currentLoopData = $flashTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(session($key)): ?>
                <div role="alert" <?php echo e($type['persistent'] ? 'data-persistent="true"' : ''); ?> class="flex items-start gap-3 px-4 py-3 rounded-xl border <?php echo e($type['bg']); ?> text-sm">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?php echo $type['icon']; ?></svg>
                    <span class="flex-1"><?php echo e(session($key)); ?></span>
                    <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup pesan" class="shrink-0 p-1 hover:bg-black/5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($errors->any()): ?>
            <div role="alert" data-persistent="true" class="flex items-start gap-3 px-4 py-3 rounded-xl border bg-[#fef2f2] border-[#fecaca] text-[#d70015] text-sm">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <ul class="list-disc list-inside flex-1 space-y-0.5"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                <button type="button" onclick="this.parentElement.remove()" aria-label="Tutup pesan" class="shrink-0 p-1 hover:bg-black/5 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\PROJECT KP\resources\views\components\flash-messages.blade.php ENDPATH**/ ?>