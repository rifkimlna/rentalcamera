<?php $__env->startSection('title', 'Profil Saya'); ?>
<?php $__env->startSection('page-title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Identitas -->
    <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8">
        <div class="flex items-center gap-4">
            <div class="rounded-full w-16 h-16 bg-[#f5f5f7] overflow-hidden flex-shrink-0">
                <?php if(auth()->user()->foto_profil): ?>
                    <img src="<?php echo e(asset('storage/' . auth()->user()->foto_profil)); ?>" alt="Foto Profil" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-xl font-semibold text-[#86868b]">
                        <?php echo e(substr(auth()->user()->nama, 0, 1)); ?>

                    </div>
                <?php endif; ?>
            </div>
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-[#1d1d1f] truncate"><?php echo e($user->nama); ?></h2>
                <p class="text-sm text-[#6e6e73] truncate"><?php echo e($user->email); ?></p>
            </div>
        </div>
    </div>

    <!-- Verifikasi Telepon -->
    <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8">
        <h3 class="text-sm font-semibold text-[#1d1d1f] mb-3">Verifikasi Telepon</h3>

        <?php if($user->telepon_verified_at): ?>
            <p class="text-sm text-[#6e6e73] mb-0">
                Nomor <span class="font-medium text-[#1d1d1f]"><?php echo e($user->telepon); ?></span> telah terverifikasi pada <?php echo e($user->telepon_verified_at->format('d M Y H:i')); ?>.
            </p>
        <?php else: ?>
            <p class="text-sm text-[#6e6e73] mb-4">Verifikasi nomor telepon untuk mengamankan akunmu. Kode OTP dikirim ke email <span class="font-medium text-[#1d1d1f]"><?php echo e($user->email); ?></span>.</p>

            <div class="space-y-3">
                <form method="POST" action="<?php echo e(route('profile.phone.send-otp')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-dark-apple text-sm">
                        Kirim Kode OTP
                    </button>
                </form>

                <form method="POST" action="<?php echo e(route('profile.phone.verify')); ?>" class="flex flex-col sm:flex-row sm:items-start gap-2">
                    <?php echo csrf_field(); ?>
                    <div class="flex-1">
                        <input type="text" name="phone_otp" inputmode="numeric" maxlength="6"
                               placeholder="Masukkan kode OTP"
                               class="input-apple w-full text-center tracking-[0.35em] <?php $__errorArgs = ['phone_otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['phone_otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[#d70015] text-xs mt-1 block"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <button type="submit" class="btn-outline-apple text-sm">Verifikasi</button>
                </form>
                <p class="text-xs text-[#86868b] mb-0">Kode berlaku 5 menit. Cek folder spam bila tidak menerima email.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Edit Profil -->
    <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8">
        <h3 class="text-sm font-semibold text-[#1d1d1f] mb-4">Edit Profil</h3>

        <form method="POST" action="<?php echo e(route('profile')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4 max-w-sm">
                <label for="foto_profil" class="block text-xs font-medium text-[#86868b] mb-1.5">Foto Profil</label>
                <div class="flex items-center gap-3">
                    <div class="rounded-full w-12 h-12 bg-[#f5f5f7] overflow-hidden flex-shrink-0" id="fotoPreview">
                        <?php if($user->foto_profil): ?>
                            <img src="<?php echo e(asset('storage/' . $user->foto_profil)); ?>" class="w-full h-full object-cover" alt="Foto Profil">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-lg font-semibold text-[#86868b]" id="fotoInitial">
                                <?php echo e(substr($user->nama, 0, 1)); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" id="foto_profil" name="foto_profil" accept="image/*"
                           class="input-apple w-full text-sm <?php $__errorArgs = ['foto_profil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                </div>
                <?php $__errorArgs = ['foto_profil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-[#d70015] text-xs"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label for="nama" class="block text-xs font-medium text-[#86868b] mb-1.5">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="input-apple w-full <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nama', $user->nama)); ?>" required>
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-[#d70015] text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="email" class="block text-xs font-medium text-[#86868b] mb-1.5">Email</label>
                    <input type="email" id="email" name="email" class="input-apple w-full <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('email', $user->email)); ?>" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-[#d70015] text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="telepon" class="block text-xs font-medium text-[#86868b] mb-1.5">Telepon</label>
                    <input type="tel" id="telepon" name="telepon" class="input-apple w-full <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('telepon', $user->telepon)); ?>" required>
                    <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-[#d70015] text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="jenis_kelamin" class="block text-xs font-medium text-[#86868b] mb-1.5">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="input-apple w-full <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Pilih...</option>
                        <option value="L" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                    </select>
                    <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-[#d70015] text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mt-4">
                <label for="alamat" class="block text-xs font-medium text-[#86868b] mb-1.5">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" class="input-apple w-full <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('alamat', $user->alamat)); ?></textarea>
                <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-[#d70015] text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 gap-y-4 mt-4">
                <div>
                    <label for="kota" class="block text-xs font-medium text-[#86868b] mb-1.5">Kota</label>
                    <input type="text" id="kota" name="kota" class="input-apple w-full" value="<?php echo e(old('kota', $user->kota)); ?>">
                </div>
                <div>
                    <label for="provinsi" class="block text-xs font-medium text-[#86868b] mb-1.5">Provinsi</label>
                    <input type="text" id="provinsi" name="provinsi" class="input-apple w-full" value="<?php echo e(old('provinsi', $user->provinsi)); ?>">
                </div>
                <div>
                    <label for="kode_pos" class="block text-xs font-medium text-[#86868b] mb-1.5">Kode Pos</label>
                    <input type="text" id="kode_pos" name="kode_pos" class="input-apple w-full" value="<?php echo e(old('kode_pos', $user->kode_pos)); ?>" inputmode="numeric">
                </div>
            </div>

            <div class="mt-4">
                <label for="tanggal_lahir" class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="input-apple w-full"
                       value="<?php echo e(old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '')); ?>">
            </div>

            <div class="mt-5">
                <button type="submit" class="btn-dark-apple">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Ubah Password -->
    <div class="bg-white rounded-2xl border border-[#f0f0f2] p-8">
        <h3 class="text-sm font-semibold text-[#1d1d1f] mb-4">Ubah Password</h3>

        <form method="POST" action="<?php echo e(route('profile.password')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php if($user->auth_provider): ?>
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0f7ff] border border-[#bfdbfe] text-[#0071e3] text-sm mb-4">
                    <span>Kamu mendaftar menggunakan <?php echo e($user->auth_provider); ?>, langsung buat password baru di bawah.</span>
                </div>
            <?php else: ?>
                <div class="sm:max-w-sm mb-3">
                    <label for="current_password" class="block text-xs font-medium text-[#86868b] mb-1.5">Password Saat Ini</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password"
                               class="input-apple w-full pr-10 <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 text-[#86868b] hover:text-[#1d1d1f] transition-colors p-1 rounded-lg" type="button" data-toggle-pw="current_password">
                            <svg class="h-4 w-4 eye-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg class="h-4 w-4 eye-icon-closed hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.985 9.985 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-[#d70015] text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-medium text-[#86868b] mb-1.5">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               class="input-apple w-full pr-10 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> text-[#d70015] border-[#d70015] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <button class="absolute right-2 top-1/2 -translate-y-1/2 text-[#86868b] hover:text-[#1d1d1f] transition-colors p-1 rounded-lg border border-[#e5e5e7]" type="button" data-toggle-pw="password">
                            <svg class="h-4 w-4 eye-icon-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg class="h-4 w-4 eye-icon-closed hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 3.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.985 9.985 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        </button>
                    </div>
                    <span class="text-xs text-[#86868b] mt-1">Minimal 6 karakter</span>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-[#86868b] mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input-apple w-full" required>
                </div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn-dark-apple">Ubah Password</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Toggle show/hide password
    document.querySelectorAll('[data-toggle-pw]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var input = document.getElementById(this.getAttribute('data-toggle-pw'));
            if (!input) return;
            var open = this.querySelector('.eye-icon-open');
            var closed = this.querySelector('.eye-icon-closed');
            if (input.type === 'password') {
                input.type = 'text';
                if (open) open.classList.add('hidden');
                if (closed) closed.classList.remove('hidden');
            } else {
                input.type = 'password';
                if (open) open.classList.remove('hidden');
                if (closed) closed.classList.add('hidden');
            }
        });
    });

    // Preview foto profil
    var fileInput = document.getElementById('foto_profil');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var el = document.getElementById('fotoPreview');
                    var initial = document.getElementById('fotoInitial');
                    if (el) el.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" alt="Preview">';
                    if (initial) initial.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\auth\profile.blade.php ENDPATH**/ ?>