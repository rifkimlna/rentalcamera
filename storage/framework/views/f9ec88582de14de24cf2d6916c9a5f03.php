

<?php $__env->startSection('title', 'Detail Transaksi'); ?>
<?php $__env->startSection('page-title', 'Detail Transaksi'); ?>

<?php
    $waPhone = config('app.wa_phone', '6281234567890');
    $isSewa = $bookingType === 'sewa_kamera';
    $isStudio = $bookingType === 'studio';
    $isLayanan = $bookingType === 'layanan';
    $t = $transaction;

    // Normalize status fields
    if ($isSewa) {
        $statusTrans = $t->status_transaksi;
        $statusBayar = $t->status_pembayaran;
        $statusTransLabel = $t->status_transaksi_label;
        $statusBayarLabel = $t->status_pembayaran_label;
        $kode = $t->kode_transaksi;
        $namaItem = $t->product_names;
        $grandTotal = $t->grand_total;
        $paidAt = $t->paid_at;
        $catatan = $t->catatan;
    } else {
        $statusTrans = $t->status;
        $statusBayar = $t->payment_status;
        $statusTransLabel = $t->status_label;
        $statusBayarLabel = $t->payment_status_label;
        $kode = $isStudio ? 'STD-' . $t->id : 'LYN-' . $t->id;
        $namaItem = $isStudio ? $t->studio->nama_studio : $t->layanan->nama_layanan;
        $grandTotal = $t->grand_total ?? $t->total_harga;
        $paidAt = $t->paid_at;
        $catatan = $t->catatan;
    }

    // Palet banner status (Apple palette)
    $statusColorMap = [
        'menunggu_pembayaran' => ['bg' => 'bg-[#fffbeb]', 'text' => 'text-[#b45309]', 'border' => 'border-[#fde68a]', 'icon' => 'clock'],
        'pending' => ['bg' => 'bg-[#fffbeb]', 'text' => 'text-[#b45309]', 'border' => 'border-[#fde68a]', 'icon' => 'clock'],
        'dikonfirmasi' => ['bg' => 'bg-[#eff6ff]', 'text' => 'text-[#0071e3]', 'border' => 'border-[#bfdbfe]', 'icon' => 'check'],
        'confirmed' => ['bg' => 'bg-[#eff6ff]', 'text' => 'text-[#0071e3]', 'border' => 'border-[#bfdbfe]', 'icon' => 'check'],
        'siap_diambil' => ['bg' => 'bg-[#eff6ff]', 'text' => 'text-[#0071e3]', 'border' => 'border-[#bfdbfe]', 'icon' => 'hand'],
        'selesai' => ['bg' => 'bg-[#f0faf1]', 'text' => 'text-[#248a3d]', 'border' => 'border-[#d1f5d5]', 'icon' => 'check'],
        'completed' => ['bg' => 'bg-[#f0faf1]', 'text' => 'text-[#248a3d]', 'border' => 'border-[#d1f5d5]', 'icon' => 'check'],
        'dibatalkan' => ['bg' => 'bg-[#fef2f2]', 'text' => 'text-[#d70015]', 'border' => 'border-[#fecaca]', 'icon' => 'x'],
        'cancelled' => ['bg' => 'bg-[#fef2f2]', 'text' => 'text-[#d70015]', 'border' => 'border-[#fecaca]', 'icon' => 'x'],
    ];
    $sc = $statusColorMap[$statusTrans] ?? ['bg' => 'bg-[#f5f5f7]', 'text' => 'text-[#1d1d1f]', 'border' => 'border-[#e5e5e7]', 'icon' => 'clock'];

    // Badge helper untuk panel informasi
    $transBadge = match ($statusTrans) {
        'selesai', 'completed' => 'bg-[#f0faf1] text-[#248a3d]',
        'dibatalkan', 'cancelled' => 'bg-[#fef2f2] text-[#d70015]',
        'siap_diambil', 'dikonfirmasi', 'confirmed' => 'bg-[#eff6ff] text-[#0071e3]',
        default => 'bg-[#fffbeb] text-[#b45309]',
    };
    $bayarBadge = match (true) {
        in_array($statusBayar, ['settlement', 'capture', 'paid']) => 'bg-[#f0faf1] text-[#248a3d]',
        in_array($statusBayar, ['failed', 'expired', 'cancel', 'deny', 'cancelled']) => 'bg-[#fef2f2] text-[#d70015]',
        default => 'bg-[#fffbeb] text-[#b45309]',
    };
?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl font-bold text-[#1d1d1f] tracking-tight">Detail <?php echo e($isSewa ? 'Transaksi' : 'Booking'); ?></h1>
            <p class="text-xs font-mono text-[#86868b] mt-0.5"><?php echo e($kode); ?></p>
        </div>
        <a href="<?php echo e(route('customer.transactions.index')); ?>" class="inline-flex items-center gap-1.5 text-sm font-medium px-4 py-2 rounded-full bg-[#f5f5f7] hover:bg-[#e5e5e7] text-[#1d1d1f] transition-colors shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    
    <div class="<?php echo e($sc['bg']); ?> border <?php echo e($sc['border']); ?> rounded-2xl p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 shrink-0 rounded-full bg-white/70 flex items-center justify-center <?php echo e($sc['text']); ?>">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <?php if($sc['icon'] === 'clock'): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <?php elseif($sc['icon'] === 'check'): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <?php elseif($sc['icon'] === 'hand'): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        <?php else: ?>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold <?php echo e($sc['text']); ?>"><?php echo e($statusTransLabel); ?></p>
                    <p class="text-xs <?php echo e($sc['text']); ?> opacity-80 mt-0.5">
                        <?php echo e($statusBayarLabel); ?>

                        <?php if($paidAt): ?>
                            &middot; <?php echo e(\Carbon\Carbon::parse($paidAt)->translatedFormat('d M Y H:i')); ?>

                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 shrink-0">
                <?php if($isSewa && $statusBayar == 'pending' && $statusTrans == 'menunggu_pembayaran'): ?>
                    <a href="<?php echo e(route('customer.checkout.payment', $t->id)); ?>" class="btn-dark-apple !py-2 !px-5 !text-sm">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Bayar Sekarang
                    </a>
                <?php elseif(!$isSewa && $statusBayar === 'pending' && $statusTrans === 'pending'): ?>
                    <a href="<?php echo e(route($isStudio ? 'customer.studio.payment' : 'customer.layanan.payment', $t->id)); ?>" class="btn-dark-apple !py-2 !px-5 !text-sm">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Bayar Sekarang
                    </a>
                <?php endif; ?>

                <?php if($isSewa && $t->canBeCancelled()): ?>
                    <form method="POST" action="<?php echo e(route('customer.transactions.cancel', $t->id)); ?>" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center justify-center text-sm font-medium px-5 py-2 rounded-full bg-[#d70015] text-white hover:bg-[#bf0013] transition-colors">Batalkan</button>
                    </form>
                <?php elseif(!$isSewa && $statusBayar === 'pending' && $statusTrans === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route($isStudio ? 'customer.studio.booking.cancel' : 'customer.layanan.booking.cancel', $t->id)); ?>" onsubmit="return confirm('Batalkan booking ini?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <button type="submit" class="inline-flex items-center justify-center text-sm font-medium px-5 py-2 rounded-full bg-[#d70015] text-white hover:bg-[#bf0013] transition-colors">Batalkan</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6">

        
        <div class="lg:col-span-2 space-y-5 lg:space-y-6">

            <?php if($isSewa): ?>
            
            <section class="card-apple-static p-4 sm:p-6">
                <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Produk Disewa</h3>
                <ul class="divide-y divide-[#f0f0f2]">
                    <?php $__currentLoopData = $t->detailTransaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $review = $t->reviews->firstWhere('produk_id', $detail->produk_id); ?>
                        <li class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-start gap-3.5">
                                <div class="shrink-0 w-14 h-14 rounded-xl overflow-hidden bg-[#f5f5f7]">
                                    <?php if($detail->produk && $detail->produk->gambar_utama): ?>
                                        <img src="<?php echo e(asset('storage/' . $detail->produk->gambar_utama)); ?>" alt="<?php echo e($detail->nama_produk); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-[#c7c7cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($detail->nama_produk); ?></p>
                                    <p class="text-xs text-[#86868b] mt-0.5">
                                        Rp <?php echo e(number_format($detail->harga_per_hari, 0, ',', '.')); ?>/hari
                                        &times; <?php echo e($detail->jumlah); ?> barang
                                        &times; <?php echo e($detail->lama_sewa); ?> hari
                                    </p>
                                </div>

                                <div class="shrink-0 text-right">
                                    <?php if($review): ?>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-[#248a3d]">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            Diulas
                                        </span>
                                    <?php elseif(in_array($statusTrans, ['selesai', 'completed'])): ?>
                                        <a href="<?php echo e(route('customer.reviews.create', ['transaction' => $t->id])); ?>?product=<?php echo e($detail->produk_id); ?>"
                                           class="inline-flex items-center gap-1 text-[11px] font-medium text-[#0071e3] hover:underline">
                                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Beri ulasan
                                        </a>
                                    <?php else: ?>
                                        <span class="text-sm font-bold text-[#1d1d1f]">Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if($review): ?>
                                <div class="mt-3 ml-[62px] p-3.5 rounded-xl bg-[#f5f5f7]">
                                    <div class="flex items-center gap-1 mb-1.5" aria-label="Rating <?php echo e($review->rating); ?> dari 5">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <svg class="h-3.5 w-3.5 <?php echo e($i <= round($review->rating) ? 'text-[#ff9500]' : 'text-[#d1d1d6]'); ?>" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <?php endfor; ?>
                                        <span class="text-[11px] text-[#86868b] ml-1"><?php echo e($review->rating); ?>/5</span>
                                    </div>
                                    <?php if($review->judul): ?>
                                        <p class="text-sm font-medium text-[#1d1d1f]"><?php echo e($review->judul); ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-[#6e6e73] mt-0.5 leading-relaxed"><?php echo e($review->komentar); ?></p>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </section>
            <?php else: ?>
            
            <section class="card-apple-static p-4 sm:p-6">
                <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Detail <?php echo e($isStudio ? 'Studio' : 'Layanan'); ?></h3>
                <div class="flex items-start gap-4">
                    <div class="shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-[#f5f5f7]">
                        <?php $gambar = $isStudio ? $t->studio?->gambar_utama : $t->layanan?->gambar_utama; ?>
                        <?php if($gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $gambar)); ?>" class="w-full h-full object-cover" alt="<?php echo e($namaItem); ?>">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                                <svg class="h-7 w-7 text-[#c7c7cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-base font-semibold text-[#1d1d1f]"><?php echo e($namaItem); ?></h4>
                        <p class="text-sm text-[#6e6e73] mt-1">
                            <?php echo e(\Carbon\Carbon::parse($t->tanggal_booking)->translatedFormat('d M Y')); ?>

                            &middot; <?php echo e($t->jam_mulai ? \Carbon\Carbon::parse($t->jam_mulai)->format('H:i') : '-'); ?>

                            &ndash; <?php echo e($t->jam_selesai ? \Carbon\Carbon::parse($t->jam_selesai)->format('H:i') : '-'); ?>

                            &middot; <?php echo e($t->durasi_jam); ?> jam
                        </p>
                        <span class="inline-flex items-center text-[11px] font-medium px-2.5 py-1 rounded-full bg-[#f5f5f7] text-[#6e6e73] mt-2.5">
                            <?php echo e($t->tipe_booking_label); ?>

                            <?php if($isStudio && $t->paketStudio): ?>
                                &middot; <?php echo e($t->paketStudio->nama_paket); ?>

                            <?php elseif($isLayanan && $t->paketLayanan): ?>
                                &middot; <?php echo e($t->paketLayanan->nama_paket); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            
            <section class="card-apple-static p-4 sm:p-6">
                <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-5">Status Pesanan</h3>

                <?php if($isSewa): ?>
                    <?php
                        $sOrder = ['menunggu_pembayaran', 'dikonfirmasi', 'siap_diambil'];
                        $cIdx = array_search($statusTrans, $sOrder);
                        if ($cIdx === false) { $cIdx = $statusTrans === 'selesai' ? 3 : -1; }
                        $steps = ['menunggu_pembayaran' => 'Pesanan Dibuat', 'dikonfirmasi' => 'Dikonfirmasi', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai'];
                    ?>
                <?php else: ?>
                    <?php
                        $sOrder = ['pending', 'confirmed'];
                        $cIdx = array_search($statusTrans, $sOrder);
                        if ($cIdx === false) { $cIdx = $statusTrans === 'completed' ? 2 : -1; }
                        $steps = ['pending' => 'Booking', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai'];
                    ?>
                <?php endif; ?>

                <ol class="flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-0">
                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $sKeys = array_keys($steps);
                            $sIdx = array_search($key, $sKeys);
                            $isCompleted = $sIdx <= $cIdx;
                            $isCurrent = $key === $statusTrans;
                            $isLast = $sIdx === count($sKeys) - 1;
                        ?>
                        <li class="flex items-center gap-2.5 lg:flex-1 lg:last:flex-none">
                            <span class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center text-[10px] font-bold
                                <?php echo e($isCompleted ? 'bg-[#1d1d1f] text-white' : 'bg-[#e5e5e7] text-[#86868b]'); ?>">
                                <?php if($isCompleted && !$isCurrent): ?>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <?php else: ?>
                                    <?php echo e($sIdx + 1); ?>

                                <?php endif; ?>
                            </span>
                            <span class="text-xs whitespace-nowrap <?php echo e($isCurrent ? 'font-semibold text-[#1d1d1f]' : ($isCompleted ? 'font-medium text-[#1d1d1f]' : 'text-[#86868b]')); ?>"><?php echo e($label); ?></span>
                            <?php if (! ($isLast)): ?>
                                <span class="hidden lg:block flex-1 h-px mx-3 <?php echo e($isCompleted ? 'bg-[#1d1d1f]' : 'bg-[#e5e5e7]'); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>

                <?php if($isSewa && $t->canReturn()): ?>
                    <div class="mt-5 p-4 bg-[#f0faf1] border border-[#d1f5d5] rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <p class="text-xs leading-relaxed text-[#248a3d]">
                            <strong class="font-semibold">
                                <?php if($statusTrans === 'siap_diambil'): ?>
                                    Barang sudah siap diambil di toko.
                                <?php else: ?>
                                    Barang sedang dalam masa sewa.
                                <?php endif; ?>
                            </strong>
                            Setelah dikembalikan, konfirmasi melalui tombol di samping.
                        </p>
                        <form method="POST" action="<?php echo e(route('customer.transactions.confirm-return', $t->id)); ?>" class="shrink-0" onsubmit="return confirm('Konfirmasi bahwa barang sudah dikembalikan?')">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-dark-apple !text-xs !px-4 !py-2">Konfirmasi Pengembalian</button>
                        </form>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        
        <div class="space-y-5 lg:space-y-6">

            
            <section class="card-apple-static p-4 sm:p-6">
                <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Informasi</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-[#86868b]">Status</dt>
                        <dd><span class="inline-flex text-[11px] font-medium px-2.5 py-1 rounded-full <?php echo e($transBadge); ?>"><?php echo e($statusTransLabel); ?></span></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-[#86868b]">Pembayaran</dt>
                        <dd><span class="inline-flex text-[11px] font-medium px-2.5 py-1 rounded-full <?php echo e($bayarBadge); ?>"><?php echo e($statusBayarLabel); ?></span></dd>
                    </div>
                    <?php if($isSewa): ?>
                        <div class="flex items-center justify-between pt-1 border-t border-[#f0f0f2]">
                            <dt class="text-[#86868b]">Tanggal Sewa</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e(\Carbon\Carbon::parse($t->tanggal_pengambilan)->translatedFormat('d M Y')); ?></dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-[#86868b]">Kembali</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e(\Carbon\Carbon::parse($t->tanggal_pengembalian)->translatedFormat('d M Y')); ?></dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-[#86868b]">Lama Sewa</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e($t->lama_sewa); ?> hari</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-[#86868b]">Metode</dt>
                            <dd class="font-medium text-[#1d1d1f]">Ambil &amp; kembali ke toko</dd>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center justify-between pt-1 border-t border-[#f0f0f2]">
                            <dt class="text-[#86868b]">Tanggal</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e(\Carbon\Carbon::parse($t->tanggal_booking)->translatedFormat('d M Y')); ?></dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-[#86868b]">Jam</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e($t->jam_mulai ? \Carbon\Carbon::parse($t->jam_mulai)->format('H:i') : '-'); ?> &ndash; <?php echo e($t->jam_selesai ? \Carbon\Carbon::parse($t->jam_selesai)->format('H:i') : '-'); ?></dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-[#86868b]">Durasi</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e($t->durasi_jam); ?> jam</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-[#86868b]">Tipe</dt>
                            <dd class="font-medium text-[#1d1d1f]"><?php echo e($t->tipe_booking_label); ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </section>

            
            <?php if($t->paymentMethod): ?>
                <section class="card-apple-static p-4 sm:p-6">
                    <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Metode Pembayaran</h3>
                    <div class="flex items-center gap-3.5 p-4 bg-[#f5f5f7] rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center shrink-0">
                            <svg class="h-5 w-5 text-[#1d1d1f]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-[#1d1d1f] truncate"><?php echo e($t->paymentMethod->name); ?></p>
                            <?php if($isSewa && $t->va_number): ?>
                                <p class="text-xs text-[#6e6e73] font-mono mt-0.5">VA: <?php echo e($t->va_number); ?></p>
                            <?php endif; ?>
                            <?php if($isSewa && $t->bank): ?>
                                <p class="text-xs text-[#6e6e73] mt-0.5">Bank: <?php echo e($t->bank); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            
            <section class="card-apple-static p-4 sm:p-6">
                <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-4">Rincian Biaya</h3>
                <div class="space-y-2.5 text-sm">
                    <?php if($isSewa): ?>
                        <div class="flex justify-between">
                            <span class="text-[#86868b]">Subtotal</span>
                            <span class="font-medium text-[#1d1d1f]">Rp <?php echo e(number_format($t->subtotal, 0, ',', '.')); ?></span>
                        </div>
                        <?php if($t->diskon > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-[#86868b]">Diskon</span>
                                <span class="font-medium text-[#248a3d]">&minus;Rp <?php echo e(number_format($t->diskon, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($t->biaya_lainnya > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-[#86868b]">Biaya Lainnya</span>
                                <span class="font-medium text-[#1d1d1f]">Rp <?php echo e(number_format($t->biaya_lainnya, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="flex justify-between">
                            <span class="text-[#86868b]">Biaya Sewa</span>
                            <span class="font-medium text-[#1d1d1f]"><?php echo e($t->total_harga_formatted); ?></span>
                        </div>
                        <?php if($t->diskon_voucher > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-[#86868b]">Diskon Voucher</span>
                                <span class="font-medium text-[#248a3d]">&minus;<?php echo e($t->diskon_voucher_formatted); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($t->admin_fee > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-[#86868b]">Biaya Admin</span>
                            <span class="font-medium text-[#1d1d1f]">Rp <?php echo e(number_format($t->admin_fee, 0, ',', '.')); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between pt-3 border-t border-[#e5e5e7]">
                        <span class="font-semibold text-[#1d1d1f]">Total</span>
                        <span class="font-bold text-base text-[#1d1d1f] tracking-tight">Rp <?php echo e(number_format($grandTotal, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </section>

            
            <?php if($catatan): ?>
                <section class="card-apple-static p-4 sm:p-6">
                    <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-2">Catatan</h3>
                    <p class="text-sm text-[#494950] leading-relaxed whitespace-pre-line"><?php echo e($catatan); ?></p>
                </section>
            <?php endif; ?>

            
            <div class="flex flex-col gap-2.5">
                <a href="https://wa.me/<?php echo e($waPhone); ?>?text=<?php echo e(rawurlencode("Halo, saya mau tanya tentang {$kode}\n{$namaItem}\nStatus: {$statusTransLabel}\nTotal: Rp " . number_format($grandTotal, 0, ',', '.'))); ?>"
                   target="_blank" rel="noopener"
                   class="btn-outline-apple w-full justify-center !text-[#128C4A] hover:!bg-[#25D366]/10 !border-[#25D366]/40">
                    <svg class="h-4.5 w-4.5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Tanya via WhatsApp
                </a>
                <?php if($isSewa && in_array($statusBayar, ['settlement', 'capture'])): ?>
                    <a href="<?php echo e(route('customer.transactions.invoice', $t->id)); ?>" target="_blank" class="btn-outline-apple w-full justify-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lihat Invoice
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/customer/transactions/show.blade.php ENDPATH**/ ?>