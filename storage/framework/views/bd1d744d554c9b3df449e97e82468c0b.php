<?php $__env->startSection('title', $product->nama_produk); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-8 lg:py-12">

    
    <nav class="flex items-center gap-1.5 text-xs text-[#86868b] mb-8 overflow-x-auto">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-[#1d1d1f] transition-colors shrink-0">Beranda</a>
        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <a href="<?php echo e(route('customer.products.index')); ?>" class="hover:text-[#1d1d1f] transition-colors shrink-0">Equipment</a>
        <?php if($product->kategori): ?>
            <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="<?php echo e(route('customer.products.index', ['kategori' => $product->kategori->slug])); ?>" class="hover:text-[#1d1d1f] transition-colors shrink-0"><?php echo e($product->kategori->nama_kategori); ?></a>
        <?php endif; ?>
        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-[#1d1d1f] font-medium truncate"><?php echo e($product->nama_produk); ?></span>
    </nav>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">

        
        <div class="space-y-4">
            <div class="relative rounded-3xl overflow-hidden bg-[#f5f5f7] aspect-square flex items-center justify-center">
                <?php if($product->gambar_utama): ?>
                    <img id="main-image" src="<?php echo e(asset('storage/' . $product->gambar_utama)); ?>" alt="<?php echo e($product->nama_produk); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="flex items-center justify-center w-full h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                <?php endif; ?>

                <?php if($product->is_featured || $product->is_recommended): ?>
                    <div class="absolute top-4 right-4 flex flex-col items-end gap-1.5">
                        <?php if($product->is_featured): ?>
                            <span class="inline-flex items-center text-xs font-medium text-white/90 bg-white/15 backdrop-blur-md px-3 py-1.5 rounded-full">Featured</span>
                        <?php endif; ?>
                        <?php if($product->is_recommended): ?>
                            <span class="inline-flex items-center text-xs font-medium text-white/90 bg-white/15 backdrop-blur-md px-3 py-1.5 rounded-full">Recommended</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php
                $allImages = [$product->gambar_utama];
                if ($product->gambar_tambahan) {
                    $additionalImages = is_array($product->gambar_tambahan) ? $product->gambar_tambahan : (json_decode($product->gambar_tambahan, true) ?? []);
                    $allImages = array_merge($allImages, $additionalImages);
                }
                $allImages = array_filter($allImages);
            ?>

            <?php if(count($allImages) > 1): ?>
                <div class="grid grid-cols-5 gap-2">
                    <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/' . $image)); ?>" alt="Gambar <?php echo e($index + 1); ?>"
                             class="rounded-xl aspect-square object-cover cursor-pointer border-2 transition-all duration-200 thumbnail-image <?php echo e($index == 0 ? 'border-[#1d1d1f]' : 'border-transparent hover:border-[#d1d1d6]'); ?>"
                             data-image="<?php echo e(asset('storage/' . $image)); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-1">
                    <span class="text-xs text-[#86868b] mr-1">Bagikan:</span>
                    <a href="https://wa.me/?text=<?php echo e(urlencode($product->nama_produk . ' - ' . request()->url())); ?>" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#f5f5f7] transition-colors text-[#25D366]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#f5f5f7] transition-colors text-[#1877F2]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href).then(() => { this.innerHTML = '<svg class=\'h-4 w-4 text-[#34c759]\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M5 13l4 4L19 7\'/></svg>'; setTimeout(() => this.innerHTML = '<svg class=\'h-4 w-4 text-[#86868b]\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1\'/></svg>'; }, 2000) })" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#f5f5f7] transition-colors">
                        <svg class="h-4 w-4 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </button>
                </div>
                <button id="saveProductBtn" class="w-9 h-9 rounded-full border border-[#e5e5e7] flex items-center justify-center hover:bg-[#f5f5f7] transition-colors">
                    <svg id="heartIcon" class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </div>
        </div>

        
        <div class="lg:py-4">
            
            <div class="flex items-center gap-2 mb-4">
                <?php if($product->brand): ?>
                    <span class="badge-brand"><?php echo e($product->brand->nama_brand); ?></span>
                <?php endif; ?>
                <?php if($product->kategori): ?>
                    <span class="badge-apple"><?php echo e($product->kategori->nama_kategori); ?></span>
                <?php endif; ?>
            </div>

            <h1 class="text-3xl lg:text-4xl font-bold tracking-tight text-[#1d1d1f] mb-4"><?php echo e($product->nama_produk); ?></h1>

            
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center gap-0.5">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <svg class="h-4 w-4 <?php echo e($i <= $product->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <?php endfor; ?>
                </div>
                <span class="text-sm text-[#6e6e73]"><?php echo e($product->rating); ?> (<?php echo e($product->jumlah_ulasan); ?> ulasan)</span>
                <span class="text-sm text-[#86868b]"><?php echo e($product->jumlah_dipesan); ?>x disewa</span>
            </div>

            
            <div class="mb-6">
                <p class="text-3xl lg:text-4xl font-bold text-[#1d1d1f] tracking-tight">Rp <?php echo e(number_format($product->harga_per_hari, 0, ',', '.')); ?><span class="text-base font-normal text-[#86868b] ml-1">/hari</span></p>
            </div>

            <div class="divider-apple mb-6"></div>

            
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-2">Deskripsi</h3>
                <p class="text-sm text-[#6e6e73] leading-relaxed"><?php echo e($product->deskripsi_singkat); ?></p>
                <?php if($product->deskripsi_lengkap): ?>
                    <div id="descriptionCollapse" class="hidden mt-2">
                        <p class="text-sm text-[#6e6e73] leading-relaxed"><?php echo nl2br(e($product->deskripsi_lengkap)); ?></p>
                    </div>
                    <button type="button" class="text-sm text-[#0071e3] hover:underline mt-2" onclick="document.getElementById('descriptionCollapse').classList.toggle('hidden'); this.textContent = document.getElementById('descriptionCollapse').classList.contains('hidden') ? 'Baca selengkapnya...' : 'Tutup'">Baca selengkapnya...</button>
                <?php endif; ?>
            </div>

            
            <?php if($product->fitur): ?>
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-3">Fitur Utama</h3>
                <ul class="space-y-2">
                    <?php $__currentLoopData = explode("\n", $product->fitur); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fitur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(trim($fitur)): ?>
                            <li class="flex items-start gap-2.5">
                                <svg class="h-4 w-4 mt-0.5 text-[#34c759] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-[#6e6e73]"><?php echo e($fitur); ?></span>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            
            <?php
                $spesifikasi = is_array($product->spesifikasi) ? $product->spesifikasi : (json_decode($product->spesifikasi, true) ?? []);
                $importantSpecs = array_slice($spesifikasi, 0, 3);
            ?>

            <?php if(count($importantSpecs) > 0): ?>
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#1d1d1f] mb-3">Spesifikasi</h3>
                <div class="grid grid-cols-2 gap-3">
                    <?php $__currentLoopData = $importantSpecs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($value): ?>
                            <div class="p-3 rounded-xl bg-[#f5f5f7]">
                                <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5"><?php echo e(str_replace('_', ' ', $key)); ?></p>
                                <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($value); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if(count($spesifikasi) > 3): ?>
                    <button type="button" class="text-sm text-[#0071e3] hover:underline mt-3" onclick="document.getElementById('specsCollapse').classList.toggle('hidden'); this.textContent = document.getElementById('specsCollapse').classList.contains('hidden') ? 'Lihat spesifikasi lengkap...' : 'Tutup'">Lihat spesifikasi lengkap...</button>
                    <div id="specsCollapse" class="hidden mt-3">
                        <div class="grid grid-cols-2 gap-3">
                            <?php $__currentLoopData = array_slice($spesifikasi, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value): ?>
                                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5"><?php echo e(str_replace('_', ' ', $key)); ?></p>
                                        <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($value); ?></p>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="p-3 rounded-xl bg-[#f5f5f7]">
                    <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Kondisi</p>
                    <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($product->kondisi_label); ?></p>
                </div>
                <?php if($product->tahun_pembuatan): ?>
                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Tahun</p>
                        <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($product->tahun_pembuatan); ?></p>
                    </div>
                <?php endif; ?>
                <?php if($product->berat): ?>
                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Berat</p>
                        <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($product->berat); ?> gram</p>
                    </div>
                <?php endif; ?>
                <?php if($product->dimensi): ?>
                    <div class="p-3 rounded-xl bg-[#f5f5f7]">
                        <p class="text-[10px] text-[#86868b] uppercase tracking-wider mb-0.5">Dimensi</p>
                        <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($product->dimensi); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if($product->status == 'available' && $product->stok_tersedia > 0): ?>
            <div class="card-apple-static p-5 lg:p-6 mb-6">
                <h3 class="text-base font-semibold text-[#1d1d1f] mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sewa Sekarang
                </h3>
                <form action="<?php echo e(route('customer.cart.direct-rent')); ?>" method="POST" id="rentalForm"
                      data-price="<?php echo e($product->harga_per_hari); ?>"
                      data-stock="<?php echo e($product->stok_tersedia); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Sewa</label>
                            <input type="date" class="input-apple rental-date" id="tanggal_sewa" name="tanggal_sewa" required min="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal Kembali</label>
                            <input type="date" class="input-apple rental-date" id="tanggal_kembali" name="tanggal_kembali" required min="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Jumlah</label>
                            <div class="flex items-center gap-0 rounded-xl border border-transparent bg-[#f5f5f7] overflow-hidden">
                                <button type="button" class="btn-minus w-10 h-10 flex items-center justify-center text-[#6e6e73] hover:bg-[#e5e5e7] transition-colors" onclick="var i=document.getElementById('jumlah'); if(parseInt(i.value)>1){i.value=parseInt(i.value)-1; calculateRental();}">-</button>
                                <input type="number" class="flex-1 text-center bg-transparent text-sm font-medium outline-none" id="jumlah" name="jumlah" value="1" min="1" max="<?php echo e($product->stok_tersedia); ?>">
                                <button type="button" class="btn-plus w-10 h-10 flex items-center justify-center text-[#6e6e73] hover:bg-[#e5e5e7] transition-colors" onclick="var i=document.getElementById('jumlah'); if(parseInt(i.value)<<?php echo e($product->stok_tersedia); ?>){i.value=parseInt(i.value)+1; calculateRental();}">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5">Lama Sewa</label>
                            <div class="input-apple bg-[#f5f5f7] cursor-default">
                                <span id="lama_sewa_display" class="font-medium text-[#1d1d1f]">1 hari</span>
                                <input type="hidden" id="lama_sewa" name="lama_sewa" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#f5f5f7] mb-4">
                        <span class="text-sm text-[#6e6e73]">Estimasi Total:</span>
                        <strong class="text-base font-bold text-[#1d1d1f]" id="estimated_total">Rp <?php echo e(number_format($product->harga_per_hari, 0, ',', '.')); ?></strong>
                    </div>

                    <button type="submit" class="btn-dark-apple w-full !py-3.5">Sewa Sekarang</button>
                </form>

                <?php $waPhone = '6281234567890'; $waText = rawurlencode('Halo, saya tertarik dengan produk ' . $product->nama_produk . ' - ' . request()->url()); ?>
                <a href="https://wa.me/<?php echo e($waPhone); ?>?text=<?php echo e($waText); ?>" target="_blank" rel="noopener" class="btn-outline-apple w-full !py-3.5 mt-3 text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Tanya via WhatsApp
                </a>
            </div>
            <?php else: ?>
            <div class="p-4 rounded-xl bg-[#fff8ee] border border-[#ffe4b5] mb-6">
                <p class="text-sm text-[#c93400] flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Produk ini sedang tidak tersedia untuk disewa.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php $approvedReviews = optional($product->ulasan)->where('status', 'approved') ?? collect(); ?>

    <?php if($approvedReviews->count() > 0): ?>
    <div class="mt-16">
        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-[#1d1d1f] mb-8">Ulasan Pelanggan</h2>

        
        <div class="card-apple-static p-6 lg:p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <p class="text-5xl font-bold text-[#1d1d1f]"><?php echo e(number_format($product->rating, 1)); ?></p>
                    <div class="flex items-center justify-center gap-0.5 mt-2">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <svg class="h-4 w-4 <?php echo e($i <= $product->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <p class="text-sm text-[#86868b] mt-1"><?php echo e($product->jumlah_ulasan); ?> ulasan</p>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                        <?php
                            $count = $approvedReviews->where('rating', $i)->count();
                            $percentage = $product->jumlah_ulasan > 0 ? ($count / $product->jumlah_ulasan) * 100 : 0;
                        ?>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-[#6e6e73] w-4 text-right"><?php echo e($i); ?></span>
                            <div class="flex-1 progress-apple">
                                <div class="progress-apple-fill" style="width: <?php echo e($percentage); ?>%"></div>
                            </div>
                            <span class="text-xs text-[#86868b] w-6"><?php echo e($count); ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        
        <div class="space-y-4">
            <?php $__currentLoopData = $approvedReviews->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ulasan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card-apple-static p-5 lg:p-6">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-9 h-9 rounded-full bg-[#1d1d1f] text-white flex items-center justify-center text-xs font-semibold shrink-0">
                            <?php echo e(strtoupper(substr(optional($ulasan->user)->nama ?? 'A', 0, 1))); ?>

                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-[#1d1d1f]"><?php echo e(optional($ulasan->user)->nama ?? 'Anonim'); ?></p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="flex items-center gap-0.5">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <svg class="h-3 w-3 <?php echo e($i <= $ulasan->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[10px] text-[#86868b]"><?php echo e($ulasan->created_at->format('d M Y')); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php if($ulasan->judul): ?>
                        <p class="text-sm font-semibold text-[#1d1d1f] mb-1.5"><?php echo e($ulasan->judul); ?></p>
                    <?php endif; ?>
                    <p class="text-sm text-[#6e6e73] leading-relaxed mb-3"><?php echo e($ulasan->komentar); ?></p>
                    <?php if($ulasan->foto_ulasan): ?>
                        <div class="flex gap-2 mb-3">
                            <?php $__currentLoopData = json_decode($ulasan->foto_ulasan, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset('storage/' . $foto)); ?>" alt="Review photo" class="rounded-xl w-16 h-16 sm:w-20 sm:h-20 object-cover border border-[#f0f0f2]">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($ulasan->balasan): ?>
                        <div class="p-3 bg-[#f5f5f7] rounded-xl text-sm flex items-start gap-2">
                            <svg class="h-4 w-4 mt-0.5 shrink-0 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <div class="min-w-0">
                                <p class="text-[10px] font-medium text-[#86868b] uppercase tracking-wider mb-0.5">Balasan dari Admin</p>
                                <p class="text-sm text-[#6e6e73]"><?php echo e($ulasan->balasan); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($relatedProducts->count() > 0): ?>
    <div class="mt-16">
        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-[#1d1d1f] mb-8">Produk Serupa</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('customer.products._product_card', ['product' => $relatedProduct], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Save (heart) toggle
        const saveBtn = document.getElementById('saveProductBtn');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                const icon = document.getElementById('heartIcon');
                const isSaved = icon.getAttribute('fill') !== 'none';
                if (isSaved) {
                    icon.setAttribute('fill', 'none');
                    icon.setAttribute('stroke', 'currentColor');
                } else {
                    icon.setAttribute('fill', '#d70015');
                    icon.setAttribute('stroke', 'none');
                    icon.classList.add('text-[#d70015]');
                }
            });
        }

        // Thumbnail image click
        document.querySelectorAll('.thumbnail-image').forEach(img => {
            img.addEventListener('click', function() {
                const mainImage = document.getElementById('main-image');
                if (mainImage.tagName === 'IMG') {
                    mainImage.src = this.dataset.image;
                } else {
                    const newImg = document.createElement('img');
                    newImg.id = 'main-image';
                    newImg.src = this.dataset.image;
                    newImg.alt = <?php echo json_encode($product->nama_produk); ?>;
                    newImg.className = 'w-full h-full object-cover';
                    mainImage.parentNode.replaceChild(newImg, mainImage);
                }
                document.querySelectorAll('.thumbnail-image').forEach(i => { i.classList.remove('border-[#1d1d1f]'); i.classList.add('border-transparent'); });
                this.classList.add('border-[#1d1d1f]');
                this.classList.remove('border-transparent');
            });
        });

        // Calculate rental days and estimated total
        function calculateRental() {
            const start = new Date(document.getElementById('tanggal_sewa').value);
            const end = new Date(document.getElementById('tanggal_kembali').value);
            const quantity = parseInt(document.getElementById('jumlah').value);
            const pricePerDay = parseFloat(document.getElementById('rentalForm').dataset.price);

            if (start && end && end > start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                document.getElementById('lama_sewa').value = diffDays;
                document.getElementById('lama_sewa_display').textContent = diffDays + ' hari';
                const total = pricePerDay * diffDays * quantity;
                document.getElementById('estimated_total').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }

        document.querySelectorAll('.rental-date, #jumlah').forEach(el => el.addEventListener('change', calculateRental));

        document.getElementById('tanggal_sewa').addEventListener('change', function() {
            const returnDate = document.getElementById('tanggal_kembali');
            const nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            returnDate.min = nextDay.toISOString().split('T')[0];
            if (!returnDate.value || new Date(returnDate.value) <= new Date(this.value)) {
                returnDate.value = nextDay.toISOString().split('T')[0];
            }
            calculateRental();
        });

        // Initialize
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0];
        const tomorrow = new Date(today); tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        document.getElementById('tanggal_sewa').min = todayStr;
        document.getElementById('tanggal_kembali').min = tomorrowStr;
        document.getElementById('tanggal_kembali').value = tomorrowStr;
        calculateRental();

        // Sewa Sekarang via AJAX
        document.getElementById('rentalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            formData.append('ajax', '1');
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.href = '<?php echo e(route("customer.checkout.index")); ?>';
                else alert(data.message || 'Terjadi kesalahan');
            })
            .catch(() => alert('Terjadi kesalahan. Silakan coba lagi.'));
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\products\show.blade.php ENDPATH**/ ?>