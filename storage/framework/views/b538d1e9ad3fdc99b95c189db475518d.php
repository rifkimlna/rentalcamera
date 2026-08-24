<?php $__env->startSection('title', 'Tambah Equipment'); ?>
<?php $__env->startSection('page-title', 'Tambah Equipment'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Dasar</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nama_produk" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Produk *</span></label>
                                        <input type="text" class="input-apple w-full <?php $__errorArgs = ['nama_produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="nama_produk" name="nama_produk" value="<?php echo e(old('nama_produk')); ?>" required>
                                        <?php $__errorArgs = ['nama_produk'];
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
                                    <div>
                                        <label for="kode_produk" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kode Produk *</span></label>
                                        <input type="text" class="input-apple w-full <?php $__errorArgs = ['kode_produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="kode_produk" name="kode_produk" value="<?php echo e(old('kode_produk')); ?>" required>
                                        <?php $__errorArgs = ['kode_produk'];
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
                                    <div>
                                        <label for="kategori_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kategori *</span></label>
                                        <select class="select-apple w-full <?php $__errorArgs = ['kategori_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="kategori_id" name="kategori_id" required>
                                            <option value="">Pilih Kategori</option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('kategori_id') == $category->id ? 'selected' : ''); ?>>
                                                    <?php echo e($category->nama_kategori); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['kategori_id'];
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
                                    <div>
                                        <label for="brand_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Brand *</span></label>
                                        <select class="select-apple w-full <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="brand_id" name="brand_id" required>
                                            <option value="">Pilih Brand</option>
                                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                                    <?php echo e($brand->nama_brand); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['brand_id'];
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
                                </div>
                                <div class="mt-4">
                                    <label for="deskripsi_singkat" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi Singkat</span></label>
                                    <textarea class="input-apple resize-none w-full <?php $__errorArgs = ['deskripsi_singkat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> textarea-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="deskripsi_singkat" name="deskripsi_singkat" rows="2"><?php echo e(old('deskripsi_singkat')); ?></textarea>
                                    <?php $__errorArgs = ['deskripsi_singkat'];
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
                                <div class="mt-4">
                                    <label for="deskripsi_lengkap" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi Lengkap</span></label>
                                    <textarea class="input-apple resize-none w-full <?php $__errorArgs = ['deskripsi_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> textarea-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="deskripsi_lengkap" name="deskripsi_lengkap" rows="4"><?php echo e(old('deskripsi_lengkap')); ?></textarea>
                                    <?php $__errorArgs = ['deskripsi_lengkap'];
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
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Spesifikasi</h2>
                                <div id="specifications-container">
                                    <div class="specification-row grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end">
                                        <div class="md:col-span-2">
                                            <input type="text" class="input-apple w-full" name="spesifikasi[0][key]" placeholder="Nama Spesifikasi">
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" class="input-apple w-full" name="spesifikasi[0][value]" placeholder="Nilai">
                                        </div>
                                        <div>
                                            <button type="button" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors remove-spec w-full" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-spec" class="btn-dark-apple-outline-apple !text-sm !px-3 !py-1.5 mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Spesifikasi
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Fitur</h2>
                                <div>
                                    <label for="fitur" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Fitur Produk (pisahkan dengan koma)</span></label>
                                    <textarea class="input-apple resize-none w-full <?php $__errorArgs = ['fitur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> textarea-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="fitur" name="fitur" rows="3" placeholder="Contoh: WiFi, GPS, Weather Sealing, Touchscreen"><?php echo e(old('fitur')); ?></textarea>
                                    <?php $__errorArgs = ['fitur'];
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
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Harga Sewa</h2>
                                <div>
                                    <label for="harga_per_hari" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga per Hari *</span></label>
                                    <div class="flex w-full">
                                        <span class=" bg-[#f5f5f7] px-3 flex items-center text-sm">Rp</span>
                                        <input type="number" class="input-apple w-full <?php $__errorArgs = ['harga_per_hari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="harga_per_hari" name="harga_per_hari" value="<?php echo e(old('harga_per_hari')); ?>" required>
                                    </div>
                                    <?php $__errorArgs = ['harga_per_hari'];
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

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="minimum_sewa" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Min. Sewa (hari) *</span></label>
                                        <input type="number" class="input-apple w-full <?php $__errorArgs = ['minimum_sewa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="minimum_sewa" name="minimum_sewa" value="<?php echo e(old('minimum_sewa', 1)); ?>" required>
                                        <?php $__errorArgs = ['minimum_sewa'];
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
                                    <div>
                                        <label for="maximum_sewa" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Max. Sewa (hari) *</span></label>
                                        <input type="number" class="input-apple w-full <?php $__errorArgs = ['maximum_sewa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="maximum_sewa" name="maximum_sewa" value="<?php echo e(old('maximum_sewa', 30)); ?>" required>
                                        <?php $__errorArgs = ['maximum_sewa'];
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
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Stok & Status</h2>
                                <div>
                                    <label for="stok_total" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Stok Total *</span></label>
                                    <input type="number" class="input-apple w-full <?php $__errorArgs = ['stok_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="stok_total" name="stok_total" value="<?php echo e(old('stok_total', 1)); ?>" required>
                                    <?php $__errorArgs = ['stok_total'];
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
                                <div class="mt-4">
                                    <label for="status" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status *</span></label>
                                    <select class="select-apple w-full <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="status" name="status" required>
                                        <option value="available" <?php echo e(old('status') == 'available' ? 'selected' : ''); ?>>Tersedia</option>
                                        <option value="unavailable" <?php echo e(old('status') == 'unavailable' ? 'selected' : ''); ?>>Tidak Tersedia</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
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
                                <div class="mt-4">
                                    <label for="kondisi" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kondisi *</span></label>
                                    <select class="select-apple w-full <?php $__errorArgs = ['kondisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> select-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="kondisi" name="kondisi" required>
                                        <option value="baru" <?php echo e(old('kondisi') == 'baru' ? 'selected' : ''); ?>>Baru</option>
                                        <option value="bekas_excellent" <?php echo e(old('kondisi') == 'bekas_excellent' ? 'selected' : ''); ?>>Bekas (Excellent)</option>
                                        <option value="bekas_good" <?php echo e(old('kondisi') == 'bekas_good' ? 'selected' : ''); ?>>Bekas (Good)</option>
                                        <option value="bekas_fair" <?php echo e(old('kondisi') == 'bekas_fair' ? 'selected' : ''); ?>>Bekas (Fair)</option>
                                    </select>
                                    <?php $__errorArgs = ['kondisi'];
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
                                <div class="mt-4">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5 cursor-pointer">
                                        <span class="">Tampilkan sebagai Featured</span>
                                        <input type="checkbox" class="w-11 h-6 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5 cursor-pointer">
                                        <span class="">Tampilkan sebagai Recommended</span>
                                        <input type="checkbox" class="w-11 h-6 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all" id="is_recommended" name="is_recommended" value="1" <?php echo e(old('is_recommended') ? 'checked' : ''); ?>>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Gambar Produk</h2>
                                <div>
                                    <label for="gambar_utama" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar Utama</span></label>
                                    <input type="file" class="input-apple w-full <?php $__errorArgs = ['gambar_utama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-apple-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="gambar_utama" name="gambar_utama" accept="image/*">
                                    <small class="text-[#6e6e73] text-xs">Format: JPG, PNG, GIF | Maks: 2MB</small>
                                    <?php $__errorArgs = ['gambar_utama'];
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
                                <div class="mt-4">
                                    <label for="gambar_tambahan" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar Tambahan</span></label>
                                    <input type="file" class="input-apple w-full <?php $__errorArgs = ['gambar_tambahan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-apple-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="gambar_tambahan" name="gambar_tambahan[]" accept="image/*" multiple>
                                    <small class="text-[#6e6e73] text-xs">Pilih beberapa gambar</small>
                                    <?php $__errorArgs = ['gambar_tambahan.*'];
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
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Tambahan</h2>
                                <div>
                                    <label for="berat" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Berat (gram)</span></label>
                                    <input type="number" class="input-apple w-full <?php $__errorArgs = ['berat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="berat" name="berat" value="<?php echo e(old('berat')); ?>">
                                    <?php $__errorArgs = ['berat'];
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
                                <div class="mt-4">
                                    <label for="dimensi" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Dimensi (P x L x T cm)</span></label>
                                    <input type="text" class="input-apple w-full <?php $__errorArgs = ['dimensi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="dimensi" name="dimensi" value="<?php echo e(old('dimensi')); ?>" placeholder="Contoh: 15 x 10 x 5">
                                    <?php $__errorArgs = ['dimensi'];
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
                                <div class="mt-4">
                                    <label for="serial_number" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Serial Number</span></label>
                                    <input type="text" class="input-apple w-full <?php $__errorArgs = ['serial_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="serial_number" name="serial_number" value="<?php echo e(old('serial_number')); ?>">
                                    <?php $__errorArgs = ['serial_number'];
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
                                <div class="mt-4">
                                    <label for="tahun_pembuatan" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tahun Pembuatan</span></label>
                                    <input type="text" class="input-apple w-full <?php $__errorArgs = ['tahun_pembuatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="tahun_pembuatan" name="tahun_pembuatan" value="<?php echo e(old('tahun_pembuatan')); ?>" maxlength="4">
                                    <?php $__errorArgs = ['tahun_pembuatan'];
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-dark-apple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
<script>
    let specCounter = 1;

    const specificationsContainer = document.getElementById('specifications-container');

    function refreshRemoveSpecButtons() {
        const rows = document.querySelectorAll('.specification-row');
        const onlyOne = rows.length === 1;
        document.querySelectorAll('.remove-spec').forEach(function (btn) {
            btn.disabled = onlyOne;
        });
    }

    document.getElementById('add-spec').addEventListener('click', function () {
        const row = `
            <div class="specification-row grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end">
                <div class="md:col-span-2">
                    <input type="text" class="input-apple w-full" name="spesifikasi[${specCounter}][key]" placeholder="Nama Spesifikasi">
                </div>
                <div class="md:col-span-2">
                    <input type="text" class="input-apple w-full" name="spesifikasi[${specCounter}][value]" placeholder="Nilai">
                </div>
                <div>
                    <button type="button" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors remove-spec w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `;
        specificationsContainer.insertAdjacentHTML('beforeend', row);
        specCounter++;
        refreshRemoveSpecButtons();
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-spec')) {
            e.target.closest('.specification-row').remove();
            refreshRemoveSpecButtons();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\products\create.blade.php ENDPATH**/ ?>