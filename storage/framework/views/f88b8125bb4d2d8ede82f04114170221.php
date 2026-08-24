<?php $__env->startSection('title', 'Voucher Saya - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('page-title', 'Voucher Saya'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.voucher-card { position: relative; }
.voucher-card::before { content: ''; position: absolute; left: -1px; top: 0; bottom: 0; width: 3px; background: #1d1d1f; opacity: 0.15; border-radius: 3px 0 0 3px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <?php if($availableVouchers->isNotEmpty()): ?>
    <div>
        <h3 class="text-xs font-medium text-[#6e6e73] uppercase tracking-wider mb-3">Voucher Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php $__currentLoopData = $availableVouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-apple-static voucher-card">
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <p class="text-sm font-medium"><?php echo e($voucher->nama_voucher); ?></p>
                            <p class="text-lg font-semibold text-[#1d1d1f] mt-0.5"><?php echo e($voucher->kode_voucher); ?></p>
                        </div>
                        <span class="badge-apple !text-[10px] !px-2 !py-0.5 border border-[#e5e5e7]"><?php echo e($voucher->type_label); ?></span>
                    </div>
                    <div class="text-xs text-[#6e6e73] space-y-1 mb-3">
                        <p>Diskon: <span class="text-[#86868b] font-medium"><?php echo e($voucher->value_formatted); ?></span></p>
                        <?php if($voucher->min_purchase > 0): ?>
                        <p>Min. Belanja: <span class="text-[#86868b] font-medium"><?php echo e($voucher->min_purchase_formatted); ?></span></p>
                        <?php endif; ?>
                        <?php if($voucher->max_discount): ?>
                        <p>Maks. Diskon: <span class="text-[#86868b] font-medium"><?php echo e($voucher->max_discount_formatted); ?></span></p>
                        <?php endif; ?>
                        <p>Berlaku: <span class="text-[#86868b] font-medium"><?php echo e($voucher->start_date->format('d M Y')); ?> - <?php echo e($voucher->end_date->format('d M Y')); ?></span></p>
                    </div>
                    <button onclick="copyVoucher('<?php echo e($voucher->kode_voucher); ?>')" class="btn-outline-apple w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Salin Kode
                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php else: ?>
    <div class="card-apple-static">
        <div class="p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-sm text-[#6e6e73]">Belum ada voucher</p>
        </div>
    </div>
    <?php endif; ?>

    <?php if($usedVouchers->isNotEmpty()): ?>
    <div>
        <h3 class="text-xs font-medium text-[#6e6e73] uppercase tracking-wider mb-3">Riwayat Penggunaan Voucher</h3>
        <div class="space-y-2">
            <?php $__currentLoopData = $usedVouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-apple-static">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium"><?php echo e($usage->voucher->kode_voucher ?? 'Voucher'); ?></p>
                            <p class="text-xs text-[#86868b]"><?php echo e($usage->voucher->nama_voucher ?? ''); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium">-<?php echo e($usage->discount_amount_formatted); ?></p>
                            <p class="text-xs text-[#86868b]"><?php echo e($usage->created_at->format('d M Y')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="flex justify-center mt-4">
            <?php echo e($usedVouchers->links()); ?>

        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function copyVoucher(code) {
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Tersalin!';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\dashboard\vouchers.blade.php ENDPATH**/ ?>