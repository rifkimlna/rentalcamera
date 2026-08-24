<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Booking - <?php echo e($booking->studio->nama_studio); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { font-size: 14px; text-transform: uppercase; margin-bottom: 4px; }
        .header p { font-size: 10px; }
        .border-t border-[#f0f0f2] { border-top: 1px dashed #000; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; padding: 2px 0; }
        .row .label { font-weight: bold; }
        .row .value { text-align: right; }
        .section-title { font-weight: bold; margin-top: 8px; margin-bottom: 4px; font-size: 11px; text-transform: uppercase; }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid { color: #fff; background: #000; }
        .footer { text-align: center; margin-top: 10px; font-size: 10px; }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Stekpro Multimedia & Broadcast</h2>
        <p>Struk Booking Studio</p>
        <p>#<?php echo e($booking->id); ?> - <?php echo e($booking->created_at->format('d/m/Y H:i')); ?></p>
    </div>

    <div class="border-t border-[#f0f0f2]"></div>

    <div class="section-title">Data Penyewa</div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Nama</span><span class="value"><?php echo e($booking->user->nama ?? '-'); ?></span></div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Email</span><span class="value"><?php echo e($booking->user->email ?? '-'); ?></span></div>
    <?php if($booking->user->telepon): ?>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Telepon</span><span class="value"><?php echo e($booking->user->telepon); ?></span></div>
    <?php endif; ?>

    <div class="border-t border-[#f0f0f2]"></div>

    <div class="section-title">Detail Studio</div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Studio</span><span class="value"><?php echo e($booking->studio->nama_studio); ?></span></div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Tipe</span><span class="value"><?php echo e($booking->tipe_booking_label); ?></span></div>
    <?php if($booking->paketStudio): ?>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Paket</span><span class="value"><?php echo e($booking->paketStudio->nama_paket); ?></span></div>
    <?php endif; ?>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Tanggal</span><span class="value"><?php echo e($booking->tanggal_booking->format('d M Y')); ?></span></div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Jam</span><span class="value"><?php echo e($booking->jam_mulai->format('H:i')); ?> - <?php echo e($booking->jam_selesai->format('H:i')); ?></span></div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Durasi</span><span class="value"><?php echo e($booking->durasi_jam); ?> jam</span></div>

    <div class="border-t border-[#f0f0f2]"></div>

    <div class="section-title">Pembayaran</div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Metode</span><span class="value"><?php echo e($booking->paymentMethod->name ?? '-'); ?></span></div>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Status</span><span class="value">
        <?php if($booking->payment_status == 'paid'): ?> LUNAS
        <?php elseif($booking->payment_status == 'pending'): ?> MENUNGGU
        <?php elseif($booking->payment_status == 'failed'): ?> GAGAL
        <?php elseif($booking->payment_status == 'expired'): ?> KEDALUWARSA
        <?php elseif($booking->payment_status == 'refunded'): ?> DIREFUND
        <?php else: ?> <?php echo e(strtoupper($booking->payment_status)); ?>

        <?php endif; ?>
    </span></div>

    <div class="border-t border-[#f0f0f2]"></div>

    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Biaya Studio</span><span class="value">Rp <?php echo e(number_format($booking->total_harga, 0, ',', '.')); ?></span></div>
    <?php if($booking->diskon_voucher > 0): ?>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Diskon Voucher</span><span class="value" style="color: green;">-Rp <?php echo e(number_format($booking->diskon_voucher, 0, ',', '.')); ?></span></div>
    <?php endif; ?>
    <?php if($booking->admin_fee > 0): ?>
    <div class="row"><span class="block text-xs font-medium text-[#86868b] mb-1.5">Biaya Admin</span><span class="value">Rp <?php echo e(number_format($booking->admin_fee, 0, ',', '.')); ?></span></div>
    <?php endif; ?>
    <div class="border-t border-[#f0f0f2]"></div>
    <div class="row" style="font-size: 14px; font-weight: bold;">
        <span class="block text-xs font-medium text-[#86868b] mb-1.5">Grand Total</span>
        <span class="value">Rp <?php echo e(number_format($booking->grand_total, 0, ',', '.')); ?></span>
    </div>

    <?php if($booking->catatan): ?>
    <div class="border-t border-[#f0f0f2]"></div>
    <div class="section-title">Catatan</div>
    <p style="font-size: 10px;"><?php echo e($booking->catatan); ?></p>
    <?php endif; ?>

    <div class="border-t border-[#f0f0f2]"></div>

    <div class="footer">
        <p>Terima kasih telah menggunakan Stekpro Multimedia & Broadcast</p>
        <p style="margin-top: 4px;">Struk ini adalah bukti booking yang sah</p>
    </div>

    <div style="text-align: center; margin-top: 12px;" class="no-print">
        <button onclick="window.print()" style="padding: 8px 24px; font-size: 12px; cursor: pointer; background: #000; color: #fff; border: none; border-radius: 4px;">Cetak</button>
        <button onclick="window.close()" style="padding: 8px 24px; font-size: 12px; cursor: pointer; background: #ccc; color: #000; border: none; border-radius: 4px; margin-left: 8px;">Tutup</button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\PROJECT KP\resources\views\admin\studio\print.blade.php ENDPATH**/ ?>