<?php $__env->startSection('title', 'Lupa Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex-1 flex items-center justify-center px-4 py-6 sm:py-8 min-h-screen bg-[#f5f5f7]">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8 sm:p-10">
            <div class="text-center mb-6">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Stekpro Logo" class="h-12 w-auto mx-auto mb-3 object-contain">
                <h1 class="text-xl sm:text-2xl font-bold text-[#1d1d1f]">Lupa Password</h1>
                <p class="text-[#6e6e73] text-sm mt-1.5">Masukkan email Anda dan kami akan mengirimkan tautan reset password.</p>
            </div>

            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label for="email" class="block text-xs font-medium text-[#86868b] mb-1.5">Email</label>
                    <input type="email" id="email" name="email" class="input-apple w-full <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required placeholder="nama@example.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-[#d70015] mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-dark-apple w-full mb-4">Kirim Link Reset</button>

                <div class="text-center">
                    <a href="<?php echo e(route('login')); ?>" class="text-sm text-[#0071e3] hover:underline">Kembali ke halaman login</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>