

<?php $__env->startSection('title', 'Checkout - Stekpro Multimedia & Broadcast'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4">
    <div class="text-sm mb-4">
        <ul class="flex items-center gap-2 text-[#6e6e73]">
            <li><a href="<?php echo e(route('customer.dashboard')); ?>" class="hover:text-[#1d1d1f] transition-all">Dashboard</a></li>
            <li>/</li>
            <?php if($isStudioBooking): ?>
                <li><a href="<?php echo e(route('customer.studio.index')); ?>" class="hover:text-[#1d1d1f] transition-all">Studio</a></li>
            <?php elseif($isLayananBooking): ?>
                <li><a href="<?php echo e(route('customer.layanan.index')); ?>" class="hover:text-[#1d1d1f] transition-all">Layanan</a></li>
            <?php else: ?>
                <li><a href="<?php echo e(route('customer.cart.index')); ?>" class="hover:text-[#1d1d1f] transition-all">Keranjang</a></li>
            <?php endif; ?>
            <li>/</li>
            <li class="text-[#1d1d1f] font-medium">Checkout</li>
        </ul>
    </div>

    <?php if(session('success')): ?>
        <div class="rounded-xl bg-[#34c759]/10 border border-[#34c759]/20 p-3 mb-4 flex items-center gap-2 text-sm text-[#34c759]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1"><?php echo e(session('success')); ?></span>
            <button class="hover:bg-[#34c759]/10 rounded-full p-1 transition-all" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="rounded-xl bg-[#d70015]/10 border border-[#d70015]/20 p-3 mb-4 flex items-center gap-2 text-sm text-[#d70015]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1"><?php echo e(session('error')); ?></span>
            <button class="hover:bg-[#d70015]/10 rounded-full p-1 transition-all" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(!$isStudioBooking && !$isLayananBooking && count($keranjangItems) == 0): ?>
        <div class="card-apple-static">
            <div class="p-5 text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                <h4 class="mt-4 mb-2">Keranjang Anda kosong</h4>
                <p class="text-[#6e6e73] mb-4">Silakan tambahkan produk ke keranjang terlebih dahulu</p>
                <a href="<?php echo e(route('customer.products.index')); ?>" class="btn-dark-apple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lihat Produk
                </a>
            </div>
        </div>
    <?php else: ?>
        <form action="<?php echo e(route('customer.checkout.process')); ?>" method="POST" id="checkoutForm"
              data-subtotal="<?php echo e($subtotal); ?>"
              data-validate-url="<?php echo e(route('customer.checkout.validate-voucher')); ?>">
            <?php echo csrf_field(); ?>

            <?php
                $totalSubtotal = $subtotal;
                $totalDays = 0;
                $isBooking = $isStudioBooking || $isLayananBooking;
                $firstItem = !$isBooking ? $keranjangItems->first() : null;
                $totalJam = $isBooking ? $bookingData['durasi_jam'] : 0;
            ?>
            <?php if(!$isBooking): ?>
                <?php
                    $totalDays = $keranjangItems->sum('lama_sewa');
                ?>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">

                    <?php if($isBooking): ?>
                    <!-- Booking Period -->
                    <div class="card-apple-static">
                        <div class="p-4 sm:p-6">
                            <h5 class="font-semibold text-sm flex items-center gap-2 mb-4">
                                <svg class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Jadwal Booking <?php echo e($isStudioBooking ? 'Studio' : 'Layanan'); ?>

                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Tanggal</label>
                                    <div class="flex items-center gap-2 input-apple w-full">
                                        <svg class="h-4 w-4 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($bookingData['tanggal_booking'])->format('d M Y')); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Jam Mulai</label>
                                    <div class="flex items-center gap-2 input-apple w-full">
                                        <svg class="h-4 w-4 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm"><?php echo e($bookingData['jam_mulai']); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Jam Selesai</label>
                                    <div class="flex items-center gap-2 input-apple w-full">
                                        <svg class="h-4 w-4 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm"><?php echo e($bookingData['jam_selesai']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Rental Period -->
                    <?php if($firstItem): ?>
                    <div class="card-apple-static">
                        <div class="p-4 sm:p-6">
                            <h5 class="font-semibold text-sm flex items-center gap-2 mb-4">
                                <svg class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Periode Sewa
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Tanggal Ambil</label>
                                    <div class="flex items-center gap-2 input-apple w-full">
                                        <svg class="h-4 w-4 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($firstItem->tanggal_sewa)->format('d M Y')); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Jam Ambil</label>
                                    <div class="flex items-center gap-2 input-apple w-full">
                                        <svg class="h-4 w-4 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm"><?php echo e($firstItem->jam_mulai ?? '08:00'); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Tanggal Kembali</label>
                                    <div class="flex items-center gap-2 input-apple w-full">
                                        <svg class="h-4 w-4 text-[#86868b] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($firstItem->tanggal_kembali)->format('d M Y')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>

                    <!-- Customer Info -->
                    <div class="card-apple-static">
                        <div class="p-4 sm:p-6">
                            <h5 class="font-semibold text-sm flex items-center gap-2 mb-4">
                                <svg class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Penyewa
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Nama Lengkap</label>
                                    <input type="text" class="input-apple w-full" name="nama_customer" value="<?php echo e(auth()->user()->nama); ?>" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Email</label>
                                    <input type="email" class="input-apple w-full" name="email_customer" value="<?php echo e(auth()->user()->email); ?>" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">No. Telepon</label>
                                    <input type="tel" class="input-apple w-full" name="telepon_customer" value="<?php echo e(auth()->user()->telepon); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($isBooking): ?>
                    <!-- Booking Detail -->
                    <div class="card-apple-static">
                        <div class="p-4 sm:p-6">
                            <h5 class="font-semibold text-sm flex items-center gap-2 mb-4">
                                <svg class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Detail <?php echo e($isStudioBooking ? 'Studio' : 'Layanan'); ?>

                            </h5>
                            <div class="flex items-center justify-between py-2">
                                <div class="flex-1 min-w-0">
                                    <h6 class="text-sm font-medium"><?php echo e($bookingData['nama_studio'] ?? $bookingData['nama_layanan']); ?></h6>
                                    <?php if($bookingData['tipe_booking'] === 'paket' && $bookingData['paket']): ?>
                                        <p class="text-xs text-[#6e6e73]">
                                            Paket: <?php echo e($bookingData['paket']['nama_paket']); ?> (<?php echo e($bookingData['paket']['durasi_jam']); ?> jam)
                                        </p>
                                    <?php else: ?>
                                        <p class="text-xs text-[#6e6e73]">
                                            <?php echo e($isStudioBooking ? 'Sewa per Jam' : 'Sewa Layanan'); ?> - <?php echo e($bookingData['durasi_jam']); ?> jam x <?php echo e(number_format($bookingData['harga_per_jam'] ?? $bookingData['harga_mulai'], 0, ',', '.')); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right shrink-0 ml-4">
                                    <span class="text-sm font-bold">Rp <?php echo e(number_format($bookingData['total_harga'], 0, ',', '.')); ?></span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs font-medium text-[#6e6e73] mb-1">Catatan</label>
                                <textarea name="catatan" class="input-apple resize-none w-full text-sm" rows="2" placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Order Items -->
                    <div class="card-apple-static">
                        <div class="p-4 sm:p-6">
                            <h5 class="font-semibold text-sm flex items-center gap-2 mb-4">
                                <svg class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Detail Equipment
                            </h5>
                            <div class="divide-y divide-[#f0f0f2]">
                                <?php $__currentLoopData = $keranjangItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $days = $item->lama_sewa;
                                        $harga = optional($item->produk)->harga_per_hari ?? 0;
                                        $subtotalItem = $harga * $days * $item->jumlah;
                                    ?>
                                    <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                        <div class="flex-1 min-w-0">
                                            <h6 class="text-sm font-medium truncate"><?php echo e(optional($item->produk)->nama_produk ?? 'Produk tidak tersedia'); ?></h6>
                                            <p class="text-xs text-[#6e6e73]">
                                                Rp <?php echo e(number_format($harga, 0, ',', '.')); ?> x <?php echo e($item->jumlah); ?> barang x <?php echo e($days); ?> hari
                                            </p>
                                        </div>
                                        <div class="text-right shrink-0 ml-4">
                                            <span class="text-sm font-bold">Rp <?php echo e(number_format($subtotalItem, 0, ',', '.')); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <?php endif; ?>
                </div>

                <!-- Right Column: Summary -->
                <div>
                    <div class="card-apple-static sticky top-4">
                        <div class="p-4 sm:p-6">
                            <h5 class="font-semibold text-sm flex items-center gap-2 mb-4">
                                <svg class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Ringkasan
                            </h5>

                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex justify-between">
                                    <span class="text-[#6e6e73]">Subtotal <?php echo e($isStudioBooking ? '(' . $totalJam . ' jam)' : '(' . $totalDays . ' hari)'); ?></span>
                                    <span class="font-medium">Rp <?php echo e(number_format($totalSubtotal, 0, ',', '.')); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#6e6e73]">Biaya Layanan</span>
                                    <span id="serviceFeeDisplay" class="font-medium">Rp 0</span>
                                </div>

                                <!-- Voucher -->
                                <div class="pt-2">
                                    <label class="block text-xs font-medium text-[#6e6e73] mb-1">Kode Voucher</label>
                                    <div class="flex w-full">
                                        <input type="text" class="input-apple flex-1" id="voucher_code" name="voucher_code" placeholder="Kode">
                                        <button type="button" class="btn-dark-apple ml-2" id="applyVoucherBtn">Pakai</button>
                                    </div>
                                    <div id="voucherMessage" class="mt-1"></div>
                                    <input type="hidden" name="voucher_id" id="voucher_id">
                                    <input type="hidden" name="diskon" id="diskon" value="0">
                                </div>

                                <div class="border-t border-[#f0f0f2]"></div>

                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">Total</span>
                                    <span class="font-bold text-base" id="totalAmount">Rp <?php echo e(number_format($totalSubtotal, 0, ',', '.')); ?></span>
                                    <input type="hidden" name="grand_total" id="grand_total" value="<?php echo e($totalSubtotal); ?>">
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="block text-xs font-medium text-[#6e6e73] mb-1">Metode Pembayaran</label>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-start gap-3 cursor-pointer p-3 rounded-xl border border-[#f0f0f2] has-checked:border-[#1d1d1f] has-checked:bg-[#f5f5f7] transition-all">
                                            <input class="radio radio-sm mt-0.5 payment-method" type="radio" name="payment_method_id" id="method<?php echo e($method->id); ?>" value="<?php echo e($method->id); ?>"
                                                   data-fee-percentage="<?php echo e($method->fee_percentage); ?>" data-fee-flat="<?php echo e($method->fee_flat); ?>" <?php echo e($loop->first ? 'checked' : ''); ?>>
                                            <div class="flex-1">
                                                <strong class="text-sm"><?php echo e($method->name); ?></strong>
                                                <?php if($method->fee_percentage > 0 || $method->fee_flat > 0): ?>
                                                    <small class="text-[#6e6e73] block text-xs">
                                                        Admin: <?php if($method->fee_percentage > 0): ?><?php echo e($method->fee_percentage); ?>%<?php endif; ?> <?php if($method->fee_flat > 0): ?>+ Rp <?php echo e(number_format($method->fee_flat, 0, ',', '.')); ?><?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Terms -->
                            <label class="flex items-start gap-2 mb-4 cursor-pointer">
                                <input class="checkbox checkbox-sm mt-0.5" type="checkbox" id="agree_terms" name="agree_terms" value="1" required>
                                <span class="text-xs text-[#6e6e73]">
                                    Saya setuju <a href="#" class="text-[#1d1d1f] font-medium hover:underline" onclick="termsModal.showModal(); return false;">Syarat & Ketentuan</a>
                                </span>
                            </label>

                            <button type="submit" class="btn-dark-apple w-full" id="payButton">
                                <svg class="h-4 w-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Lanjutkan Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<!-- Terms & Conditions Modal -->
<dialog id="termsModal" class="modal">
    <div class="modal-box max-w-2xl rounded-2xl">
        <form method="dialog">
            <button class="absolute right-4 top-4 text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-[#f5f5f7] rounded-full p-2 transition-all">&times;</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Syarat & Ketentuan Penyewaan</h3>
        <div class="space-y-4 text-sm">
            <div>
                <h6 class="font-semibold">1. Persyaratan Umum</h6>
                <ul class="list-disc list-inside space-y-1 text-[#6e6e73]">
                    <li>Penyewa minimal berusia 17 tahun dan memiliki KTP/Kartu Pelajar yang valid</li>
                    <li>Penyewa bertanggung jawab penuh atas barang sewaan selama masa sewa</li>
                    <li>Pembayaran harus lunas sebelum barang diambil</li>
                </ul>
            </div>
            <div>
                <h6 class="font-semibold">2. Ketentuan Penyewaan</h6>
                <ul class="list-disc list-inside space-y-1 text-[#6e6e73]">
                    <li>Barang harus dikembalikan tepat waktu sesuai tanggal yang disepakati</li>
                    <li>Keterlambatan pengembalian dikenakan denda 50% dari harga sewa per hari</li>
                    <li>Barang harus dikembalikan dalam kondisi baik</li>
                </ul>
            </div>
            <div>
                <h6 class="font-semibold">3. Kebijakan Pembatalan</h6>
                <ul class="list-disc list-inside space-y-1 text-[#6e6e73]">
                    <li>Pembatalan 7 hari sebelum sewa: refund 100%</li>
                    <li>Pembatalan 3-6 hari sebelum: refund 50%</li>
                    <li>Pembatalan kurang dari 3 hari: tidak ada refund</li>
                </ul>
            </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <form method="dialog">
                <button class="px-4 py-2 text-sm text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl transition-all">Tutup</button>
                <button class="btn-dark-apple">Saya Mengerti</button>
            </form>
        </div>
    </div>
</dialog>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    const currentSubtotal = parseFloat(checkoutForm.dataset.subtotal) || 0;
    let discount = 0;
    let voucherId = null;

    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    if (applyVoucherBtn) {
        applyVoucherBtn.addEventListener('click', function() {
            const code = document.getElementById('voucher_code').value.trim();
            if (!code) { showVoucherMessage('Masukkan kode', 'danger'); return; }

            const params = new URLSearchParams();
            params.append('_token', document.querySelector('input[name="_token"]').value);
            params.append('voucher_code', code);
            params.append('subtotal', currentSubtotal);

            fetch(checkoutForm.dataset.validateUrl, {
                method: 'POST',
                body: params,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(r => r.json())
            .then(r => {
                if (r.success) {
                    showVoucherMessage(r.message, 'success');
                    voucherId = r.voucher.id;
                    discount = r.voucher.diskon;
                    document.getElementById('voucher_id').value = voucherId;
                    document.getElementById('diskon').value = discount;
                    calculateTotal();
                } else {
                    showVoucherMessage(r.message, 'danger');
                    resetVoucher();
                }
            })
            .catch(() => { showVoucherMessage('Error', 'danger'); resetVoucher(); });
        });
    }

    function showVoucherMessage(msg, type) {
        const el = document.getElementById('voucherMessage');
        if (!el) return;
        const cls = type === 'success' ? 'bg-[#34c759]/10 text-[#34c759] border-[#34c759]/20' : 'bg-[#d70015]/10 text-[#d70015] border-[#d70015]/20';
        el.innerHTML = '<div class="rounded-xl border ' + cls + ' flex items-center gap-2 p-2 text-xs"><span>' + msg + '</span></div>';
    }

    function resetVoucher() {
        voucherId = null; discount = 0;
        document.getElementById('voucher_id').value = '';
        document.getElementById('diskon').value = 0;
        document.getElementById('voucher_code').value = '';
        calculateTotal();
    }

    function calculateTotal() {
        let total = currentSubtotal - discount;
        const selected = document.querySelector('input[name="payment_method_id"]:checked');
        if (selected) {
            const pct = parseFloat(selected.dataset.feePercentage || 0);
            const flat = parseFloat(selected.dataset.feeFlat || 0);
            if (pct > 0) total += total * pct / 100;
            if (flat > 0) total += flat;
        }
        const el = document.getElementById('totalAmount');
        if (el) el.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
        const input = document.getElementById('grand_total');
        if (input) input.value = Math.round(total);
    }

    document.querySelectorAll('input[name="payment_method_id"]').forEach(m => {
        m.addEventListener('change', calculateTotal);
    });

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const agree = document.getElementById('agree_terms');
            if (agree && !agree.checked) {
                e.preventDefault();
                Swal ? Swal.fire({ icon: 'warning', title: 'Persetujuan Diperlukan', text: 'Harap setujui Syarat & Ketentuan', confirmButtonColor: '#1d1d1f' }) : confirm('Setujui Syarat & Ketentuan');
                return;
            }
            const btn = document.getElementById('payButton');
            if (btn) { btn.disabled = true; btn.innerHTML = '<span class="inline-block animate-spin w-4 h-4 border-2 border-current border-t-transparent rounded-full me-2"></span>Memproses...'; }
        });
    }
    calculateTotal();
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views\customer\checkout\index.blade.php ENDPATH**/ ?>