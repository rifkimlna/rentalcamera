

<?php $__env->startSection('title', 'Invoice - Stekpro Multimedia & Broadcast'); ?>
<?php $__env->startSection('page-title', 'Invoice'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        body * { visibility: hidden; }
        #invoice-area, #invoice-area * { visibility: visible; }
        #invoice-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .card { border: 1px solid #e5e7eb !important; box-shadow: none !important; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex items-center justify-between no-print">
        <a href="<?php echo e(route('customer.transactions.show', $transaction->id)); ?>" class="inline-flex items-center gap-1.5 text-sm font-medium px-4 py-2 rounded-full bg-[#f5f5f7] hover:bg-[#e5e5e7] text-[#1d1d1f] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="btn-dark-apple !py-2 !px-5 !text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak
        </button>
    </div>

    <div id="invoice-area" class="card-apple-static">
        <div class="p-6 sm:p-10">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 pb-6 border-b border-[#e5e5e7]">
                <div>
                    <h2 class="text-lg tracking-tight font-light mb-1">Stekpro Multimedia & Broadcast</h2>
                    <p class="text-xs text-[#6e6e73] leading-relaxed">
                        Jl. Contoh No. 123<br>
                        Sukabumi, Indonesia<br>
                        info@sewakamerapro.com
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <h1 class="text-xl tracking-tight font-light mb-1">INVOICE</h1>
                    <p class="text-xs text-[#6e6e73]">
                        <span class="inline-block w-20">No. Invoice</span>
                        <span class="font-mono"><?php echo e($transaction->kode_transaksi); ?></span><br>
                        <span class="inline-block w-20">Tanggal</span>
                        <span><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y')); ?></span><br>
                        <span class="inline-block w-20">Status</span>
                        <span><?php echo e($transaction->status_pembayaran_label); ?></span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-[#e5e5e7]">
                <div>
                    <h4 class="text-xs uppercase tracking-wider text-[#6e6e73] mb-2">Informasi Pelanggan</h4>
                    <p class="text-sm leading-relaxed">
                        <span class="font-medium"><?php echo e($transaction->nama_customer); ?></span><br>
                        <span class="text-xs text-[#6e6e73]"><?php echo e($transaction->telepon_customer); ?></span><br>
                        <span class="text-xs text-[#6e6e73]"><?php echo e($transaction->email_customer); ?></span>
                    </p>
                </div>
                <div>
                    <h4 class="text-xs uppercase tracking-wider text-[#6e6e73] mb-2">Informasi Sewa</h4>
                    <p class="text-sm leading-relaxed">
                        <span class="text-xs text-[#6e6e73]">Tanggal Sewa:</span>
                        <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($transaction->tanggal_pengambilan)->translatedFormat('d M Y')); ?></span><br>
                        <span class="text-xs text-[#6e6e73]">Tanggal Kembali:</span>
                        <span class="text-sm"><?php echo e(\Carbon\Carbon::parse($transaction->tanggal_pengembalian)->translatedFormat('d M Y')); ?></span><br>
                        <span class="text-xs text-[#6e6e73]">Lama Sewa:</span>
                        <span class="text-sm"><?php echo e($transaction->lama_sewa); ?> hari</span>
                    </p>
                </div>
            </div>

            <div class="py-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[#e5e5e7]">
                            <th class="text-left py-2 text-xs uppercase tracking-wider text-[#6e6e73] font-normal">Produk</th>
                            <th class="text-center py-2 text-xs uppercase tracking-wider text-[#6e6e73] font-normal">Qty</th>
                            <th class="text-center py-2 text-xs uppercase tracking-wider text-[#6e6e73] font-normal">Harga/Hari</th>
                            <th class="text-center py-2 text-xs uppercase tracking-wider text-[#6e6e73] font-normal">Hari</th>
                            <th class="text-right py-2 text-xs uppercase tracking-wider text-[#6e6e73] font-normal">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transaction->detailTransaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-[#f0f0f2]">
                                <td class="py-3 text-sm"><?php echo e($detail->nama_produk); ?></td>
                                <td class="py-3 text-center text-xs"><?php echo e($detail->jumlah); ?></td>
                                <td class="py-3 text-center text-xs">Rp <?php echo e(number_format($detail->harga_per_hari, 0, ',', '.')); ?></td>
                                <td class="py-3 text-center text-xs"><?php echo e($detail->lama_sewa); ?></td>
                                <td class="py-3 text-right text-xs">Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end border-t border-[#e5e5e7] pt-4">
                <div class="w-full sm:w-64 space-y-2 text-sm">
                    <div class="flex justify-between text-xs">
                        <span class="text-[#6e6e73]">Subtotal</span>
                        <span>Rp <?php echo e(number_format($transaction->subtotal, 0, ',', '.')); ?></span>
                    </div>
                    <?php if($transaction->diskon > 0): ?>
                        <div class="flex justify-between text-xs">
                            <span class="text-[#6e6e73]">Diskon</span>
                            <span>-Rp <?php echo e(number_format($transaction->diskon, 0, ',', '.')); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($transaction->biaya_asuransi > 0): ?>
                        <div class="flex justify-between text-xs">
                            <span class="text-[#6e6e73]">Asuransi</span>
                            <span>Rp <?php echo e(number_format($transaction->biaya_asuransi, 0, ',', '.')); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($transaction->admin_fee > 0): ?>
                        <div class="flex justify-between text-xs">
                            <span class="text-[#6e6e73]">Biaya Admin</span>
                            <span>Rp <?php echo e(number_format($transaction->admin_fee, 0, ',', '.')); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-sm font-medium border-t border-[#e5e5e7] pt-2">
                        <span>Total</span>
                        <span>Rp <?php echo e(number_format($transaction->grand_total, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-[#e5e5e7] text-center text-xs text-[#86868b]">
                <p class="leading-relaxed">Terima kasih telah menggunakan layanan Stekpro Multimedia & Broadcast.</p>
                <p class="leading-relaxed">Invoice ini adalah bukti pembayaran yang sah.</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\PROJECT KP\resources\views/customer/transactions/invoice.blade.php ENDPATH**/ ?>