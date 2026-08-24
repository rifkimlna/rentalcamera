<?php $__env->startSection('title', 'Brands'); ?>
<?php $__env->startSection('page-title', 'Brands'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold">Manajemen Brand</h2>
            <button class="btn-dark-apple !text-sm !px-3 !py-1.5" onclick="document.getElementById('addBrandModal').showModal()">Tambah Brand</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Nama Brand</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Produk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="font-medium"><?php echo e($brand->nama_brand); ?></td>
                        <td class="text-xs text-[#6e6e73]"><?php echo e(Str::limit($brand->deskripsi, 60)); ?></td>
                        <td><?php echo e($brand->products_count ?? $brand->products()->count()); ?></td>
                        <td>
                            <span class="badge <?php echo e($brand->status === 'active' ? 'badge-success' : 'badge-apple'); ?> text-xs">
                                <?php echo e($brand->status === 'active' ? 'Aktif' : 'Nonaktif'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#ff9500]"
                                        onclick="editBrand('<?php echo e($brand->id); ?>', '<?php echo e(addslashes($brand->nama_brand)); ?>', '<?php echo e(addslashes($brand->deskripsi ?? '')); ?>', '<?php echo e($brand->status); ?>')">Edit</button>
                                <form method="POST" action="<?php echo e(route('admin.brands.destroy', $brand->id)); ?>" onsubmit="return confirm('Hapus brand ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada brand</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="addBrandModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 z-50 flex items-center justify-center-box">
        <h3 class="font-semibold text-lg mb-4">Tambah Brand</h3>
        <form method="POST" action="<?php echo e(route('admin.brands.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Brand</span></label>
                <input type="text" name="nama_brand" class="input-apple" required>
            </div>
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" class="input-apple resize-none" rows="3"></textarea>
            </div>
            <div class=" mb-4">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" class="select-apple">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center-action">
                <button type="button" class="btn-dark-apple" onclick="document.getElementById('addBrandModal').close()">Batal</button>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="editBrandModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 z-50 flex items-center justify-center-box">
        <h3 class="font-semibold text-lg mb-4">Edit Brand</h3>
        <form method="POST" id="editBrandForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Brand</span></label>
                <input type="text" name="nama_brand" id="editBrandName" class="input-apple" required>
            </div>
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" id="editBrandDesc" class="input-apple resize-none" rows="3"></textarea>
            </div>
            <div class=" mb-4">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" id="editBrandStatus" class="select-apple">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center-action">
                <button type="button" class="btn-dark-apple" onclick="document.getElementById('editBrandModal').close()">Batal</button>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<?php $__env->startPush('scripts'); ?>
<script>
function editBrand(id, name, desc, status) {
    document.getElementById('editBrandForm').action = "<?php echo e(url('admin/brands')); ?>/" + id;
    document.getElementById('editBrandName').value = name;
    document.getElementById('editBrandDesc').value = desc;
    document.getElementById('editBrandStatus').value = status;
    document.getElementById('editBrandModal').showModal();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\products\brands.blade.php ENDPATH**/ ?>