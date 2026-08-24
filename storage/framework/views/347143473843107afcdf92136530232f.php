<?php $__env->startSection('title', 'Dashboard - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4 sm:space-y-5">

    <!-- Hero / Welcome -->
    <div class="card-apple-static bg-[#0071e3] text-white overflow-hidden relative">
        <div class="p-5 relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-2xl font-bold shrink-0">
                        <?php echo e(substr(Auth::user()->nama, 0, 1)); ?>

                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold truncate">Halo, <?php echo e(Auth::user()->nama); ?>!</h2>
                        <p class="mb-0 text-sm opacity-90">Sewa kamera, studio, dan layanan jadi mudah di sini.</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mt-4">
                <a href="<?php echo e(route('customer.products.index')); ?>" class="btn-sm bg-white text-[#1d1d1f] border-0 hover:bg-white/90 rounded-xl px-4 py-2 text-sm font-medium inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Sewa Equipment
                </a>
                <a href="<?php echo e(route('customer.studio.index')); ?>" class="btn-sm border border-white/40 text-white hover:bg-white/20 hover:border-white/40 rounded-xl px-4 py-2 text-sm font-medium inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sewa Studio
                </a>
                <a href="<?php echo e(route('customer.layanan.index')); ?>" class="btn-sm border border-white/40 text-white hover:bg-white/20 hover:border-white/40 rounded-xl px-4 py-2 text-sm font-medium inline-flex items-center gap-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    Pesan Layanan
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="card-apple-static p-4 sm:p-5">
            <div class="w-10 h-10 rounded-xl bg-[#0071e3]/10 text-[#0071e3] flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-[#6e6e73] text-sm mb-0">Total Transaksi</p>
            <p class="text-2xl font-bold mb-0"><?php echo e($stats['total_orders']); ?></p>
            <a href="<?php echo e(route('customer.transactions.index')); ?>" class="text-[#0071e3] text-xs mt-1 inline-block">Lihat semua &rarr;</a>
        </div>

        <div class="card-apple-static p-4 sm:p-5">
            <div class="w-10 h-10 rounded-xl bg-[#34c759]/10 text-[#34c759] flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-[#6e6e73] text-sm mb-0">Selesai</p>
            <p class="text-2xl font-bold mb-0"><?php echo e($stats['completed_orders']); ?></p>
        </div>

        <div class="card-apple-static p-4 sm:p-5">
            <div class="w-10 h-10 rounded-xl bg-[#ff9500]/10 text-[#ff9500] flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-[#6e6e73] text-sm mb-0">Dalam Proses</p>
            <p class="text-2xl font-bold mb-0"><?php echo e($stats['pending_orders']); ?></p>
        </div>

        <div class="card-apple-static p-4 sm:p-5">
            <div class="w-10 h-10 rounded-xl bg-[#f5f5f7] text-[#1d1d1f] flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-[#6e6e73] text-xs sm:text-sm mb-0">Total Pengeluaran</p>
            <p class="text-base sm:text-lg font-bold mb-0 leading-tight truncate" title="Rp <?php echo e(number_format($stats['total_spent'], 0, ',', '.')); ?>">Rp <?php echo e(number_format($stats['total_spent'], 0, ',', '.')); ?></p>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-5">
            <div class="card-apple-static">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="font-semibold">Transaksi Terbaru</h5>
                        <a href="<?php echo e(route('customer.transactions.index')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] px-3 py-1.5 rounded-xl hover:bg-[#f5f5f7] transition-all">Lihat Semua</a>
                    </div>

                    <?php
                        $statusColors = ['menunggu_pembayaran' => 'badge-warning', 'diproses' => 'badge-apple', 'dikonfirmasi' => 'badge-brand', 'siap_diambil' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                        $statusTexts = ['menunggu_pembayaran' => 'Menunggu Bayar', 'diproses' => 'Diproses', 'dikonfirmasi' => 'Dikonfirmasi', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'];
                    ?>

                    <?php if($recentTransactions->isNotEmpty()): ?>
                        <!-- Mobile list -->
                        <div class="md:hidden divide-y divide-[#f0f0f2]">
                            <?php $__currentLoopData = $recentTransactions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('customer.transactions.show', $transaction->id)); ?>" class="flex items-center gap-3 py-3 px-1 -mx-1 rounded-xl hover:bg-[#f5f5f7] transition-all">
                                    <div class="w-10 h-10 rounded-full bg-[#f5f5f7] flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <strong class="text-sm block truncate"><?php echo e($transaction->kode_transaksi); ?></strong>
                                        <span class="text-xs text-[#86868b] block truncate">
                                            <?php $__currentLoopData = optional($transaction->detailTransaksis)->take(1) ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($detail->nama_produk); ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </span>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="text-sm font-semibold text-[#0071e3]">Rp <?php echo e(number_format($transaction->grand_total, 0, ',', '.')); ?></div>
                                        <span class="badge-apple !text-[10px] !px-2 !py-0.5 <?php echo e($statusColors[$transaction->status_transaksi] ?? ''); ?> mt-0.5"><?php echo e($statusTexts[$transaction->status_transaksi] ?? $transaction->status_transaksi); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="md:hidden text-center mt-3">
                            <a href="<?php echo e(route('customer.transactions.index')); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] px-3 py-1.5 rounded-xl hover:bg-[#f5f5f7] transition-all">Lihat Semua Transaksi</a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <p class="text-[#6e6e73]">Belum ada transaksi</p>
                            <a href="<?php echo e(route('customer.products.index')); ?>" class="btn-dark-apple !text-sm mt-2">Sewa Sekarang</a>
                        </div>
                    <?php endif; ?>

                    <!-- Desktop table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">ID</th>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Tanggal</th>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Produk</th>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Total</th>
                                    <th class="text-left text-xs font-medium text-[#6e6e73] uppercase tracking-wider py-3 px-2">Status</th>
                                    <th class="py-3 px-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-t border-[#f0f0f2]">
                                        <td class="py-3 px-2"><strong><?php echo e($transaction->kode_transaksi); ?></strong></td>
                                        <td class="py-3 px-2">
                                            <div><?php echo e($transaction->created_at->format('d M Y')); ?></div>
                                            <div class="text-[#6e6e73] text-xs"><?php echo e($transaction->created_at->format('H:i')); ?></div>
                                        </td>
                                        <td class="py-3 px-2">
                                            <?php $__currentLoopData = optional($transaction->detailTransaksis)->take(2) ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="text-sm"><?php echo e($detail->nama_produk); ?></div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(optional($transaction->detailTransaksis)->count() > 2): ?>
                                                <div class="text-[#6e6e73] text-xs">+<?php echo e(optional($transaction->detailTransaksis)->count() - 2); ?> lainnya</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-2"><strong class="text-[#0071e3]">Rp <?php echo e(number_format($transaction->grand_total, 0, ',', '.')); ?></strong></td>
                                        <td class="py-3 px-2">
                                            <span class="badge-apple <?php echo e($statusColors[$transaction->status_transaksi] ?? ''); ?>"><?php echo e($statusTexts[$transaction->status_transaksi] ?? $transaction->status_transaksi); ?></span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <a href="<?php echo e(route('customer.transactions.show', $transaction->id)); ?>" class="text-sm text-[#6e6e73] hover:text-[#1d1d1f] px-2 py-1 rounded-lg hover:bg-[#f5f5f7] transition-all inline-flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-8">
                                            <p class="text-[#6e6e73]">Belum ada transaksi</p>
                                            <a href="<?php echo e(route('customer.products.index')); ?>" class="btn-dark-apple !text-sm mt-2">Sewa Sekarang</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upcoming Rentals -->
            <?php if($upcomingRentals->isNotEmpty()): ?>
                <div class="card-apple-static">
                    <div class="p-5">
                        <h5 class="font-semibold mb-4">Booking Mendatang</h5>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $upcomingRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3 border border-[#f0f0f2] rounded-xl p-3">
                                    <div class="w-12 h-12 rounded-xl bg-[#0071e3]/10 text-[#0071e3] flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <?php $__currentLoopData = optional($rental->detailTransaksis)->take(1) ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <h6 class="text-sm font-semibold mb-0.5 truncate"><?php echo e($detail->nama_produk); ?></h6>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <small class="text-[#6e6e73]">
                                            <?php echo e(\Carbon\Carbon::parse($rental->tanggal_pengambilan)->translatedFormat('d M Y')); ?>

                                            <span class="mx-1">-</span>
                                            <?php echo e(\Carbon\Carbon::parse($rental->tanggal_pengembalian)->translatedFormat('d M Y')); ?>

                                        </small>
                                    </div>
                                    <a href="<?php echo e(route('customer.transactions.show', $rental->id)); ?>" class="btn-outline-apple !text-xs shrink-0">Detail</a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4 sm:space-y-5">
            <div class="card-apple-static">
                <div class="p-5">
                    <h5 class="font-semibold mb-4">Verifikasi Akun</h5>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between rounded-xl border border-[#f0f0f2] px-3 py-2.5">
                            <span class="text-sm text-[#6e6e73]">Email</span>
                            <?php if($user->email_verified_at): ?>
                                <span class="badge-success !text-[10px] !px-2 !py-0.5">Terverifikasi</span>
                            <?php else: ?>
                                <span class="badge-apple !text-[10px] !px-2 !py-0.5">Belum Verifikasi</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border border-[#f0f0f2] px-3 py-2.5">
                            <span class="text-sm text-[#6e6e73]">Telepon</span>
                            <?php if($user->telepon_verified_at): ?>
                                <span class="badge-success !text-[10px] !px-2 !py-0.5">Terverifikasi</span>
                            <?php else: ?>
                                <span class="badge-apple !text-[10px] !px-2 !py-0.5">Belum Verifikasi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo e(route('profile')); ?>" class="btn-outline-apple w-full mt-4">Kelola Profil</a>
                </div>
            </div>

            <!-- Transactions to review -->
            <?php if($transactionsToReview->isNotEmpty()): ?>
                <div class="card-apple-static">
                    <div class="p-5">
                        <h5 class="font-semibold mb-4">Belum Diulas</h5>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $transactionsToReview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3 rounded-xl border border-[#f0f0f2] p-3">
                                    <div class="shrink-0">
                                        <?php $firstDetail = optional($item->detailTransaksis)->first(); ?>
                                        <?php if($firstDetail && $firstDetail->produk && $firstDetail->produk->gambar_utama): ?>
                                            <img src="<?php echo e(asset('storage/' . $firstDetail->produk->gambar_utama)); ?>" class="rounded-xl w-12 h-12 object-cover" alt="">
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-xl bg-[#f5f5f7] flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="text-sm font-semibold mb-0.5 truncate"><?php echo e($firstDetail->nama_produk ?? 'Transaksi'); ?></h6>
                                        <small class="text-[#6e6e73] text-xs"><?php echo e($item->kode_transaksi); ?></small>
                                    </div>
                                    <a href="<?php echo e(route('customer.reviews.create', $item->id)); ?>" class="btn-outline-apple !text-xs shrink-0">Ulas</a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Links -->
            <div class="card-apple-static">
                <div class="p-5">
                    <h5 class="font-semibold mb-3">Menu Cepat</h5>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="<?php echo e(route('customer.transactions.index')); ?>" class="btn-outline-apple">Transaksi</a>
                        <a href="<?php echo e(route('customer.dashboard.vouchers')); ?>" class="btn-outline-apple">Voucher</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\dashboard\index.blade.php ENDPATH**/ ?>