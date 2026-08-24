<?php $__env->startSection('title', 'Manajemen Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1d1d1f]">Manajemen Pengguna</h1>
            <p class="text-sm text-[#6e6e73]">Kelola data customer dan admin</p>
        </div>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-dark-apple">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <div class="flex items-center gap-3">
                    <div class="bg-[#0071e3]/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#0071e3]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold"><?php echo e(App\Models\User::count()); ?></h5>
                        <small class="text-[#6e6e73]">Total Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <div class="flex items-center gap-3">
                    <div class="bg-[#34c759]/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#34c759]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold"><?php echo e(App\Models\User::where('role', 'customer')->where('status', 'active')->count()); ?></h5>
                        <small class="text-[#6e6e73]">Customer Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <div class="flex items-center gap-3">
                    <div class="bg-[#ff9500]/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#ff9500]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold"><?php echo e(App\Models\User::where('status', 'pending_verification')->count()); ?></h5>
                        <small class="text-[#6e6e73]">Pending Verifikasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
        <div class="p-5">
            <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Role</span></label>
                    <select name="role" class="select-apple w-full">
                        <option value="">Semua Role</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('role') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                    <select name="status" class="select-apple w-full">
                        <option value="">Semua Status</option>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('status') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Pencarian</span></label>
                    <div class="flex w-full">
                        <input type="text" name="search" class="input-apple flex-1" value="<?php echo e(request('search')); ?>" placeholder="Nama, email, atau telepon...">
                        <button type="submit" class="btn-dark-apple ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari
                        </button>
                    </div>
                </div>
                <div class="flex items-end">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Kontak</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-[#6e6e73] text-sm"><?php echo e(($users->currentPage() - 1) * $users->perPage() + $loop->iteration); ?></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="bg-[#f5f5f7] rounded-full flex items-center justify-center w-8 h-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <div class="font-medium"><?php echo e($user->nama); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                <div class="truncate max-w-[200px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <?php echo e($user->email); ?>

                                </div>
                                <div class="text-[#6e6e73]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <?php echo e($user->telepon ?? '-'); ?>

                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php
                                $roleConfig = ['superadmin' => 'badge-error', 'admin' => 'badge-brand', 'customer' => 'badge-brand'];
                            ?>
                            <span class="badge <?php echo e($roleConfig[$user->role] ?? 'badge-apple'); ?>">
                                <?php echo e($roles[$user->role] ?? $user->role); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <?php
                                $statusConfig = ['active' => 'badge-success', 'inactive' => 'badge-apple', 'suspended' => 'badge-error', 'pending_verification' => 'badge-warning'];
                            ?>
                            <span class="badge <?php echo e($statusConfig[$user->status] ?? 'badge-apple'); ?>">
                                <?php echo e($statuses[$user->status] ?? $user->status); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors text-[#ff9500]" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <?php if($user->role !== 'superadmin'): ?>
                                <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" class="inline delete-form">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors text-[#d70015]" title="Hapus" onclick="return confirm('Hapus pengguna ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-[#6e6e73]">Tidak ada data pengguna</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($users->hasPages()): ?>
        <div class="border-t border-[#f0f0f2] p-4">
            <div class="flex justify-between items-center">
                <div class="text-sm text-[#6e6e73]">
                    Menampilkan <?php echo e($users->firstItem()); ?> - <?php echo e($users->lastItem()); ?> dari <?php echo e($users->total()); ?>

                </div>
                <div><?php echo e($users->links('vendor.pagination.simple-bootstrap')); ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/admin/users/index.blade.php ENDPATH**/ ?>