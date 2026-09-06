

<?php $__env->startSection('title', 'Studio - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal5b09c79149dfb771c232996af5f9dae4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b09c79149dfb771c232996af5f9dae4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.flash-messages','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b09c79149dfb771c232996af5f9dae4)): ?>
<?php $attributes = $__attributesOriginal5b09c79149dfb771c232996af5f9dae4; ?>
<?php unset($__attributesOriginal5b09c79149dfb771c232996af5f9dae4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b09c79149dfb771c232996af5f9dae4)): ?>
<?php $component = $__componentOriginal5b09c79149dfb771c232996af5f9dae4; ?>
<?php unset($__componentOriginal5b09c79149dfb771c232996af5f9dae4); ?>
<?php endif; ?>
<div>
    <div class="flex items-center justify-between mb-4 sm:mb-6 gap-2">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-bold text-[#1d1d1f] tracking-tight">Sewa Studio</h1>
            <p class="text-xs sm:text-sm text-[#6e6e73] mt-0.5 hidden sm:block">Temukan studio terbaik untuk kebutuhan foto, video, dan konten Anda</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-apple-static mb-4 sm:mb-6 p-3 sm:p-4 lg:p-5">
        <!-- Desktop: grid -->
        <form method="GET" class="hidden lg:grid lg:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-[#86868b] mb-1.5">Cari Studio</label>
                <input type="text" name="search" class="input-apple" placeholder="Cari..." value="<?php echo e(request('search')); ?>">
            </div>
            <div></div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-dark-apple flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                </button>
                <a href="<?php echo e(route('customer.studio.index')); ?>" class="btn-outline-apple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>

        <!-- Mobile: compact -->
        <form method="GET" class="lg:hidden flex items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#86868b]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                </span>
                <input type="text" name="search" class="input-apple !pl-9" placeholder="Cari studio..." value="<?php echo e(request('search')); ?>">
            </div>
            <a href="<?php echo e(route('customer.studio.index')); ?>" class="btn-outline-apple !px-3" title="Reset">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </a>
            <button type="submit" class="btn-dark-apple">Cari</button>
        </form>
    </div>

    <!-- Studio grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
    <?php $__empty_1 = true; $__currentLoopData = $studios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card-apple-static rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <!-- Image (like home category box) -->
            <a href="<?php echo e(route('customer.studio.show', $studio->slug)); ?>" class="group relative overflow-hidden bg-[#f5f5f7] aspect-square block">
                <?php if($studio->gambar_utama): ?>
                    <img src="<?php echo e(asset('storage/' . $studio->gambar_utama)); ?>"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="<?php echo e($studio->nama_studio); ?>">
                <?php else: ?>
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-slate-200 via-slate-100 to-[#f5f5f7]">
                        <svg class="h-16 w-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                <?php endif; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-2.5 sm:p-3 lg:p-4 text-white">
                    <p class="text-xs sm:text-sm lg:text-base font-semibold truncate"><?php echo e($studio->nama_studio); ?></p>
                    <div class="flex items-center gap-0.5 sm:gap-1 mt-0.5 sm:mt-1">
                        <div class="flex items-center gap-0.5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 sm:h-3 sm:w-3 <?php echo e($i <= round($studio->rating) ? 'text-[#ff9500]' : 'text-white/30'); ?>" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-[10px] sm:text-xs text-white/80">(<?php echo e($studio->rating); ?>)</span>
                    </div>
                </div>
            </a>

            <!-- Body -->
            <div class="p-2.5 sm:p-3 lg:p-4 bg-white">
                <div class="flex items-center justify-between gap-1.5 mb-1.5 sm:mb-2">
                    <div>
                        <span class="text-xs sm:text-sm lg:text-base font-bold text-[#1d1d1f]"><?php echo e($studio->harga_per_jam_formatted); ?></span>
                        <span class="text-[10px] sm:text-xs text-[#6e6e73]">/jam</span>
                        <?php if($studio->paket_active_count > 0): ?>
                            <span class="text-[10px] sm:text-xs text-[#86868b] block mt-0.5"><?php echo e($studio->paket_active_count); ?> paket</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($studio->fasilitas): ?>
                    <?php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : array_map('trim', explode(',', $studio->fasilitas)); ?>
                    <div class="flex flex-wrap gap-1 mb-2 sm:mb-3">
                        <?php $__currentLoopData = array_slice($fasilitasList, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0"><?php echo e($f); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($fasilitasList) > 3): ?>
                            <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0">+<?php echo e(count($fasilitasList) - 3); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="flex gap-1.5 sm:gap-2">
                    <a href="<?php echo e(route('customer.studio.show', $studio->slug)); ?>" class="btn-outline-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2">Detail</a>
                    <?php if($studio->paketActive->count() > 0): ?>
                        
                        <?php if(auth()->guard()->check()): ?>
                            <button type="button" class="btn-dark-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2" onclick="openStudioModal(<?php echo e($studio->id); ?>)">Sewa</button>
                        <?php else: ?>
                            <a href="<?php echo e(route('customer.studio.show', $studio->slug)); ?>" class="btn-dark-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2 text-center">Sewa</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('customer.studio.show', $studio->slug)); ?>" class="btn-dark-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2 text-center">Sewa</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card-apple-static col-span-full">
            <div class="p-5 text-center py-8">
                <p class="text-[#6e6e73]">Studio tidak ditemukan</p>
                <a href="<?php echo e(route('customer.studio.index')); ?>" class="btn-dark-apple mt-2">Reset Pencarian</a>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <?php if($studios->hasPages()): ?>
        <div class="flex justify-center mt-4">
            <?php echo e($studios->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Modal Sewa Studio -->
<dialog id="studioModal" class="modal">
    <div class="modal-box max-w-lg rounded-2xl">
        <form method="dialog">
            <button class="absolute right-4 top-4 text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-[#f5f5f7] rounded-full p-2 transition-all">&times;</button>
        </form>
        <h3 class="font-bold text-lg mb-1" id="modalStudioName">Sewa Studio</h3>
        <p class="text-sm text-[#6e6e73] mb-4" id="modalStudioPrice">Pilih paket atau sewa per jam</p>

        <form id="studioBookingForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="studio_id" id="modal_studio_id">
            <input type="hidden" name="tipe_booking" id="modal_tipe_booking" value="studio">
            <input type="hidden" name="paket_studio_id" id="modal_paket_studio_id" value="">

            <!-- Paket List -->
            <div id="modalPaketList" class="space-y-2 mb-4"></div>

            <!-- Booking Date & Time -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Tanggal</label>
                    <input type="date" name="tanggal_booking" class="input-apple w-full" required min="<?php echo e(date('Y-m-d')); ?>">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="input-apple w-full" required>
                </div>
            </div>

            <button type="submit" class="btn-dark-apple w-full" id="modalBookingBtn">
                Lanjutkan ke Checkout
            </button>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<?php $__env->startPush('scripts'); ?>
<script>
    const studiosData = <?php echo json_encode($studiosJson); ?>;
    const STUDIO_IS_GUEST = <?php echo e(auth()->check() ? 'false' : 'true'); ?>;
    const STUDIO_LOGIN_URL = '<?php echo e(route("login")); ?>';

    function openStudioModal(studioId) {
        if (STUDIO_IS_GUEST) {
            alert('Silakan login terlebih dahulu untuk menyewa.');
            window.location.href = STUDIO_LOGIN_URL;
            return;
        }
        const studio = studiosData[studioId];
        if (!studio) return;

        document.getElementById('modalStudioName').textContent = studio.nama_studio;
        document.getElementById('modalStudioPrice').textContent = 'Mulai dari ' + studio.harga_per_jam_formatted + '/jam';
        document.getElementById('modal_studio_id').value = studio.id;

        const paketList = document.getElementById('modalPaketList');
        paketList.innerHTML = '';

        // Custom jam option
        const customDiv = document.createElement('div');
        customDiv.className = 'flex items-center gap-3 p-3 rounded-xl border border-[#f0f0f2] cursor-pointer hover:bg-[#f5f5f7] has-checked:border-[#1d1d1f] has-checked:bg-[#f5f5f7] transition-all';
        customDiv.innerHTML = `
            <input type="radio" name="tipe_booking_radio" value="studio" class="radio radio-sm" checked>
            <div class="flex-1">
                <strong class="text-sm">Sewa per Jam</strong>
                <p class="text-xs text-[#6e6e73]">Fleksibel, bayar per jam</p>
            </div>
            <div class="text-right">
                <strong class="text-sm">${studio.harga_per_jam_formatted}</strong>
                <p class="text-xs text-[#6e6e73]">/ jam</p>
            </div>
        `;
        paketList.appendChild(customDiv);

        // Duration input (for custom hours)
        const durasiDiv = document.createElement('div');
        durasiDiv.id = 'durasiManual';
        durasiDiv.className = 'mt-2';
        durasiDiv.innerHTML = `
            <label class="block text-xs font-medium text-[#6e6e73] mb-1">Durasi (jam)</label>
            <input type="number" name="durasi_jam" class="input-apple w-full" value="1" min="1" max="8">
        `;
        paketList.appendChild(durasiDiv);

        // Paket options
        studio.paketActive.forEach(function(paket) {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 p-3 rounded-xl border border-[#f0f0f2] cursor-pointer hover:bg-[#f5f5f7] has-checked:border-[#1d1d1f] has-checked:bg-[#f5f5f7] transition-all';
            div.innerHTML = `
                <input type="radio" name="tipe_booking_radio" value="paket" data-paket-id="${paket.id}" data-durasi="${paket.durasi_jam}" class="radio radio-sm">
                <div class="flex-1 min-w-0">
                    <strong class="text-sm">${paket.nama_paket}</strong>
                    <p class="text-xs text-[#6e6e73] truncate">${paket.deskripsi || ''}</p>
                    <span class="badge-apple mt-1">${paket.durasi_jam} jam</span>
                    ${paket.include_alat ? `<span class="badge-apple border border-[#e5e5e7] mt-1">+ Alat</span>` : ''}
                </div>
                <div class="text-right shrink-0">
                    <strong class="text-sm">${paket.harga_formatted}</strong>
                </div>
            `;
            div.querySelector('input').addEventListener('change', function() {
                togglePaketInput();
            });
            paketList.appendChild(div);
        });

        document.getElementById('studioModal').showModal();
    }

    function togglePaketInput() {
        const selected = document.querySelector('input[name="tipe_booking_radio"]:checked');
        const durasiManual = document.getElementById('durasiManual');
        if (selected && selected.value === 'paket') {
            durasiManual.classList.add('hidden');
            document.getElementById('modal_tipe_booking').value = 'paket';
            document.getElementById('modal_paket_studio_id').value = selected.dataset.paketId || '';
        } else {
            durasiManual.classList.remove('hidden');
            document.getElementById('modal_tipe_booking').value = 'studio';
            document.getElementById('modal_paket_studio_id').value = '';
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'tipe_booking_radio') {
            togglePaketInput();
        }
    });

    // AJAX booking submission -> store in session, go to checkout
    document.getElementById('studioBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (STUDIO_IS_GUEST) { window.location.href = STUDIO_LOGIN_URL; return; }
        const btn = document.getElementById('modalBookingBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="gooey-loader" style="--gooey-dot:7px;margin-right:8px"><i></i><i></i><i></i></span>Memproses...';

        const formData = new FormData(this);
        formData.append('_token', '<?php echo e(csrf_token()); ?>');

        fetch('<?php echo e(route("customer.studio.direct-booking")); ?>', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => {
            if (res.status === 401) { window.location.href = STUDIO_LOGIN_URL; return null; }
            return res.json();
        })
        .then(data => {
            if (!data) return;
            if (data.success) {
                document.getElementById('studioModal').close();
                window.location.href = '<?php echo e(route("customer.checkout.index")); ?>';
            } else {
                document.getElementById('studioModal').close();
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
            document.getElementById('studioModal').close();
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


<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/customer/studio/index.blade.php ENDPATH**/ ?>