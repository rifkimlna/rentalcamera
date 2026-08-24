<?php $__env->startSection('title', 'Verifikasi Email'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 min-h-screen bg-[#f5f5f7]">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8 sm:p-10">
            <div class="text-center mb-6">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Stekpro Logo" class="h-12 w-auto mx-auto mb-3 object-contain">
                <h1 class="text-xl sm:text-2xl font-bold text-[#1d1d1f]">Verifikasi Email Anda</h1>
                <p class="text-[#6e6e73] text-sm mt-1.5">Kami telah mengirimkan link verifikasi ke email Anda. Silakan klik link tersebut untuk memverifikasi akun Anda.</p>
            </div>

            <?php if(session('status')): ?>
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0fdf4] border border-[#bbf7d0] text-[#16a34a] text-sm mb-4">
                <?php echo e(session('status')); ?>

            </div>
            <?php endif; ?>

            <?php if(session('resent')): ?>
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0fdf4] border border-[#bbf7d0] text-[#16a34a] text-sm mb-4">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-dark-apple w-full mb-4">Kirim Ulang Link Verifikasi</button>
            </form>

            <div class="text-center">
                <a href="<?php echo e(route('logout')); ?>" class="text-sm text-[#0071e3] hover:underline"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>