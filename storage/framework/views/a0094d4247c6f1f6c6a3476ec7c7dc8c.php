<?php $__env->startSection('title', 'Profil Saya - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('page-title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl space-y-6">
    <div class="card-apple-static">
        <div class="p-6">
            <h3 class="text-sm font-medium mb-4">Informasi Profil</h3>
            <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 bg-[#f5f5f7] rounded-full flex items-center justify-center overflow-hidden">
                            <?php if($user->foto_profil): ?>
                            <img src="<?php echo e(asset('storage/' . $user->foto_profil)); ?>" alt="<?php echo e($user->nama); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <input type="file" name="foto_profil" class="input-apple w-full max-w-xs text-sm" accept="image/*">
                            <p class="text-xs text-[#86868b] mt-1">Format: JPG, PNG | Maks: 2MB</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" class="input-apple w-full text-sm" value="<?php echo e(old('nama', $user->nama)); ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Email</label>
                            <input type="email" name="email" class="input-apple w-full text-sm" value="<?php echo e(old('email', $user->email)); ?>" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Telepon</label>
                            <input type="tel" name="telepon" class="input-apple w-full text-sm" value="<?php echo e(old('telepon', $user->telepon)); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="input-apple w-full text-sm" value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '')); ?>">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Alamat</label>
                        <textarea name="alamat" class="input-apple resize-none w-full text-sm" rows="2"><?php echo e(old('alamat', $user->alamat)); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Kota</label>
                            <input type="text" name="kota" class="input-apple w-full text-sm" value="<?php echo e(old('kota', $user->kota)); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Provinsi</label>
                            <input type="text" name="provinsi" class="input-apple w-full text-sm" value="<?php echo e(old('provinsi', $user->provinsi)); ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" class="input-apple w-full text-sm" value="<?php echo e(old('kode_pos', $user->kode_pos)); ?>">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-dark-apple">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-apple-static">
        <div class="p-6">
            <h3 class="text-sm font-medium mb-4">Ubah Password</h3>
            <form action="<?php echo e(route('profile.password')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="space-y-4">
                    <?php if(auth()->user()->auth_provider): ?>
                        <div class="rounded-xl bg-[#0071e3]/10 border border-[#0071e3]/20 p-3 text-sm text-[#0071e3]">
                            Kamu mendaftar menggunakan <?php echo e(auth()->user()->auth_provider); ?>, langsung buat password baru di bawah.
                        </div>
                    <?php else: ?>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" class="input-apple w-full text-sm" required>
                        </div>
                    <?php endif; ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Password Baru</label>
                            <input type="password" name="password" class="input-apple w-full text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="input-apple w-full text-sm" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="btn-dark-apple">Ubah Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\dashboard\profile.blade.php ENDPATH**/ ?>