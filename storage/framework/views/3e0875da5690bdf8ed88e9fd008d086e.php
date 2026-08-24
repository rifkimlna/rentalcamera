

<?php $__env->startSection('title', $studio->nama_studio); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <ul class="flex items-center gap-2 text-[#6e6e73]">
            <li><a href="<?php echo e(route('home')); ?>" class="hover:text-[#1d1d1f] transition-all">Beranda</a></li>
            <li>/</li>
            <li><a href="<?php echo e(route('customer.studio.index')); ?>" class="hover:text-[#1d1d1f] transition-all">Studio</a></li>
            <li>/</li>
            <li class="text-[#1d1d1f] font-medium"><?php echo e($studio->nama_studio); ?></li>
        </ul>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Images -->
        <div>
            <div class="card-apple-static mb-4">
                <div class="p-5">
                    <?php if($studio->gambar_utama): ?>
                        <img src="<?php echo e(asset('storage/' . $studio->gambar_utama)); ?>"
                             class="w-full h-72 object-cover rounded-xl" alt="<?php echo e($studio->nama_studio); ?>">
                    <?php else: ?>
                        <div class="bg-[#f5f5f7] rounded-xl h-72 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Share -->
            <div class="card-apple-static">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-[#6e6e73] me-1">Bagikan:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>"
                               target="_blank" class="text-[#1877F2] hover:bg-[#1877F2]/10 rounded-xl p-2 transition-all border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?php echo e(urlencode($studio->nama_studio)); ?>&url=<?php echo e(urlencode(request()->url())); ?>"
                               target="_blank" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl p-2 transition-all border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                            <a href="https://wa.me/?text=<?php echo e(urlencode($studio->nama_studio . ' - ' . request()->url())); ?>"
                               target="_blank" class="text-[#25D366] hover:bg-[#25D366]/10 rounded-xl p-2 transition-all border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </a>
                            <button onclick="copyLink()" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl p-2 transition-all border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Info -->
        <div>
            <div class="card-apple-static mb-4">
                <div class="p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h1 class="text-2xl font-bold mb-1"><?php echo e($studio->nama_studio); ?></h1>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center gap-0.5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo e($i <= $studio->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]'); ?>" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-[#6e6e73] text-sm"><?php echo e($studio->rating); ?> (<?php echo e($studio->jumlah_ulasan); ?> ulasan)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <h2 class="text-[#1d1d1f] text-2xl mb-1"><?php echo e($studio->harga_per_jam_formatted); ?> / jam</h2>
                        <?php if($studio->paketActive->count() > 0): ?>
                            <p class="text-sm text-[#6e6e73]"><?php echo e($studio->paketActive->count()); ?> paket tersedia</p>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="font-semibold mb-2">Deskripsi</h5>
                        <p><?php echo e($studio->deskripsi ?? 'Tidak ada deskripsi.'); ?></p>
                    </div>

                    <!-- Facilities -->
                    <?php if($studio->fasilitas): ?>
                    <div class="mb-4">
                        <h5 class="font-semibold mb-2">Fasilitas</h5>
                        <?php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : array_map('trim', explode(',', $studio->fasilitas)); ?>
                        <div class="flex flex-wrap gap-1">
                            <?php $__currentLoopData = $fasilitasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge-apple border border-[#e5e5e7]"><?php echo e($f); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Packages -->
                    <?php if($studio->paketActive->count() > 0): ?>
                    <div class="mb-4">
                        <h5 class="font-semibold mb-2">Paket Sewa</h5>
                        <div class="space-y-2">
                            <?php $__currentLoopData = $studio->paketActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-[#f0f0f2] rounded-xl p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <h6 class="text-sm font-medium"><?php echo e($paket->nama_paket); ?></h6>
                                        <p class="text-xs text-[#6e6e73]"><?php echo e($paket->deskripsi); ?></p>
                                        <?php if($paket->include_alat): ?>
                                            <?php $alatList = array_map('trim', explode(',', $paket->include_alat)); ?>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <?php $__currentLoopData = $alatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge-apple"><?php echo e($alat); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <strong class="text-sm"><?php echo e($paket->harga_formatted); ?></strong>
                                        <p class="text-xs text-[#6e6e73]"><?php echo e($paket->durasi_jam); ?> jam</p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Booking Form -->
                    <div class="bg-[#f5f5f7] rounded-xl p-5">
                        <h5 class="font-semibold text-lg mb-3">Booking Studio</h5>
                        <form method="POST" action="<?php echo e(route('customer.studio.direct-booking')); ?>" id="bookingForm">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="studio_id" value="<?php echo e($studio->id); ?>">
                            <input type="hidden" name="tipe_booking" id="tipe_booking" value="studio">
                            <input type="hidden" name="paket_studio_id" id="paket_studio_id" value="">
                            <input type="hidden" name="durasi_jam" id="durasi_jam" value="1">

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Sewa per Jam</label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="tipe_radio" value="studio" class="radio radio-sm" checked onchange="selectStudio()">
                                    <span class="text-sm"><?php echo e($studio->harga_per_jam_formatted); ?> / jam</span>
                                </label>
                            </div>

                            <div id="durasi_input" class="mb-3">
                                <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Durasi (jam)</label>
                                <input type="number" name="durasi_manual" class="input-apple w-full text-sm" value="1" min="1">
                            </div>

                            <?php $__currentLoopData = $studio->paketActive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-2">
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl border border-[#f0f0f2] has-checked:border-[#1d1d1f] has-checked:bg-white transition-all">
                                    <input type="radio" name="tipe_radio" value="paket"
                                           data-paket-id="<?php echo e($paket->id); ?>"
                                           data-durasi="<?php echo e(intval($paket->durasi_jam)); ?>"
                                           class="radio radio-sm booking-type-paket"
                                           onchange="selectPaket(this)">
                                    <div class="flex-1">
                                        <span class="text-sm font-medium"><?php echo e($paket->nama_paket); ?></span>
                                        <span class="text-xs text-[#6e6e73] block"><?php echo e($paket->deskripsi); ?></span>
                                    </div>
                                    <span class="text-sm font-bold"><?php echo e($paket->harga_formatted); ?></span>
                                </label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <div class="grid grid-cols-2 gap-3 mb-3 mt-3">
                                <div>
                                    <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Tanggal</label>
                                    <input type="date" name="tanggal_booking" class="input-apple w-full text-sm" required min="<?php echo e(date('Y-m-d')); ?>">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" class="input-apple w-full text-sm" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-[#1d1d1f] mb-1">Catatan</label>
                                <textarea name="catatan" class="input-apple resize-none w-full text-sm" rows="2" placeholder="Catatan tambahan..."></textarea>
                            </div>

                            <div class="border-t border-[#e5e5e7] pt-3 mb-3 space-y-1">
                                <div class="flex justify-between text-sm">
                                    <span>Estimasi Biaya Studio:</span>
                                    <span id="estimasi_harga"><?php echo e($studio->harga_per_jam_formatted); ?></span>
                                </div>
                            </div>

                            <button type="submit" class="btn-dark-apple w-full" id="bookingSubmitBtn">
                                Lanjutkan ke Checkout
                            </button>
                        </form>
                        <?php $waPhone = '6281234567890'; $waText = rawurlencode('Halo, saya tertarik dengan studio ' . $studio->nama_studio . ' - ' . request()->url()); ?>
                        <a href="https://wa.me/<?php echo e($waPhone); ?>?text=<?php echo e($waText); ?>" target="_blank"
                           class="btn-outline-apple w-full mt-3" rel="noopener">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Tanya via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-6">
        <div class="card-apple-static">
            <div class="p-5">
                <h3 class="text-xl font-bold mb-4">Ulasan Pelanggan</h3>

                <?php $approvedReviews = $studio->ulasan->where('status', 'approved'); ?>

                <?php if($approvedReviews->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $approvedReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ulasan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b border-[#f0f0f2] pb-4 last:border-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h6 class="font-semibold mb-1"><?php echo e(optional($ulasan->user)->nama ?? 'Anonim'); ?></h6>
                                        <div class="flex items-center gap-1">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 <?php echo e($i <= $ulasan->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]'); ?>" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            <?php endfor; ?>
                                            <span class="text-[#6e6e73] text-xs ms-2"><?php echo e($ulasan->created_at->format('d M Y')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($ulasan->judul): ?>
                                    <h6 class="font-semibold mb-2"><?php echo e($ulasan->judul); ?></h6>
                                <?php endif; ?>
                                <p class="mb-2"><?php echo e($ulasan->komentar); ?></p>
                                <?php if($ulasan->foto_ulasan): ?>
                                    <div class="flex gap-2 mt-2">
                                        <?php $__currentLoopData = json_decode($ulasan->foto_ulasan, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <img src="<?php echo e(asset('storage/' . $foto)); ?>" alt="Review photo" class="rounded-xl w-20 h-20 object-cover">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($ulasan->balasan): ?>
                                    <div class="mt-3 p-3 bg-[#f5f5f7] rounded-xl text-sm">
                                        <strong>Balasan dari Admin:</strong> <?php echo e($ulasan->balasan); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h5 class="mt-3">Belum ada ulasan</h5>
                        <p class="text-[#6e6e73]">Jadilah yang pertama memberikan ulasan untuk studio ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Related Studios -->
    <?php if($relatedStudios->count() > 0): ?>
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Studio Lainnya</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php $__currentLoopData = $relatedStudios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-apple-static rounded-2xl overflow-hidden">
                <figure class="relative h-36 overflow-hidden">
                    <?php if($rs->gambar_utama): ?>
                        <img src="<?php echo e(asset('storage/' . $rs->gambar_utama)); ?>" class="w-full h-full object-cover" alt="<?php echo e($rs->nama_studio); ?>">
                    <?php else: ?>
                        <div class="w-full h-full bg-[#f5f5f7] flex items-center justify-center text-[#86868b] text-sm">No Image</div>
                    <?php endif; ?>
                </figure>
                <div class="p-3">
                    <h4 class="text-sm font-medium"><?php echo e($rs->nama_studio); ?></h4>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-sm font-bold"><?php echo e($rs->harga_per_jam_formatted); ?></span>
                        <a href="<?php echo e(route('customer.studio.show', $rs->slug)); ?>" class="btn-outline-apple !text-xs">Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const btn = event.currentTarget;
            const original = btn.innerHTML;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#34c759]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
            setTimeout(() => btn.innerHTML = original, 2000);
        });
    }

    const hargaPerJam = <?php echo e($studio->harga_per_jam); ?>;

    function selectPaket(el) {
        document.getElementById('tipe_booking').value = 'paket';
        document.getElementById('paket_studio_id').value = el.dataset.paketId || el.value;
        document.getElementById('durasi_jam').value = parseInt(el.dataset.durasi) || 1;
        document.getElementById('durasi_input').classList.add('hidden');
        document.querySelector('input[name="tipe_radio"][value="studio"]').checked = false;
        updateEstimasi();
    }

    function selectStudio() {
        document.getElementById('tipe_booking').value = 'studio';
        document.getElementById('paket_studio_id').value = '';
        document.getElementById('durasi_input').classList.remove('hidden');
        document.querySelectorAll('.booking-type-paket').forEach(el => el.checked = false);
        updateEstimasi();
    }

    function updateEstimasi() {
        const tipe = document.getElementById('tipe_booking').value;
        let harga = 0;
        if (tipe === 'paket') {
            const selected = document.querySelector('.booking-type-paket:checked');
            if (selected) {
                const paketEl = selected.closest('.mb-2');
                const hargaText = paketEl.querySelector('.font-bold').textContent;
                document.getElementById('estimasi_harga').textContent = hargaText;
            }
        } else {
            const durasi = parseInt(document.querySelector('input[name="durasi_manual"]')?.value) || 1;
            document.getElementById('durasi_jam').value = durasi;
            harga = hargaPerJam * durasi;
            document.getElementById('estimasi_harga').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        }
    }

    document.querySelector('input[name="durasi_manual"]')?.addEventListener('change', updateEstimasi);

    // Submit via AJAX -> store in session -> redirect to checkout
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('bookingSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin w-4 h-4 border-2 border-current border-t-transparent rounded-full"></span> Memproses...';

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?php echo e(route("customer.checkout.index")); ?>';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Studio Tidak Tersedia',
                    text: data.message || 'Terjadi kesalahan. Silakan coba jadwal lain.',
                    confirmButtonColor: '#1d1d1f',
                    confirmButtonText: 'OK',
                });
                btn.disabled = false;
                btn.textContent = 'Lanjutkan ke Checkout';
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                confirmButtonColor: '#1d1d1f',
                confirmButtonText: 'OK',
            });
            btn.disabled = false;
            btn.textContent = 'Lanjutkan ke Checkout';
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\studio\show.blade.php ENDPATH**/ ?>