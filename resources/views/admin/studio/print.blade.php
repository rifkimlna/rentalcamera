<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Booking - {{ $booking->studio->nama_studio }}</title>
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
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
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
        <h2>Sewa Kamera Pro</h2>
        <p>Struk Booking Studio</p>
        <p>#{{ $booking->id }} - {{ $booking->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="divider"></div>

    <div class="section-title">Data Penyewa</div>
    <div class="row"><span class="label">Nama</span><span class="value">{{ $booking->user->nama ?? '-' }}</span></div>
    <div class="row"><span class="label">Email</span><span class="value">{{ $booking->user->email ?? '-' }}</span></div>
    @if($booking->user->telepon)
    <div class="row"><span class="label">Telepon</span><span class="value">{{ $booking->user->telepon }}</span></div>
    @endif

    <div class="divider"></div>

    <div class="section-title">Detail Studio</div>
    <div class="row"><span class="label">Studio</span><span class="value">{{ $booking->studio->nama_studio }}</span></div>
    <div class="row"><span class="label">Tipe</span><span class="value">{{ $booking->tipe_booking_label }}</span></div>
    @if($booking->paketStudio)
    <div class="row"><span class="label">Paket</span><span class="value">{{ $booking->paketStudio->nama_paket }}</span></div>
    @endif
    <div class="row"><span class="label">Tanggal</span><span class="value">{{ $booking->tanggal_booking->format('d M Y') }}</span></div>
    <div class="row"><span class="label">Jam</span><span class="value">{{ $booking->jam_mulai->format('H:i') }} - {{ $booking->jam_selesai->format('H:i') }}</span></div>
    <div class="row"><span class="label">Durasi</span><span class="value">{{ $booking->durasi_jam }} jam</span></div>

    <div class="divider"></div>

    <div class="section-title">Pembayaran</div>
    <div class="row"><span class="label">Metode</span><span class="value">{{ $booking->paymentMethod->name ?? '-' }}</span></div>
    <div class="row"><span class="label">Status</span><span class="value">
        @if($booking->payment_status == 'paid') LUNAS
        @elseif($booking->payment_status == 'pending') MENUNGGU
        @elseif($booking->payment_status == 'failed') GAGAL
        @elseif($booking->payment_status == 'expired') KEDALUWARSA
        @elseif($booking->payment_status == 'refunded') DIREFUND
        @else {{ strtoupper($booking->payment_status) }}
        @endif
    </span></div>

    <div class="divider"></div>

    <div class="row"><span class="label">Biaya Studio</span><span class="value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span></div>
    @if($booking->admin_fee > 0)
    <div class="row"><span class="label">Biaya Admin</span><span class="value">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</span></div>
    @endif
    <div class="divider"></div>
    <div class="row" style="font-size: 14px; font-weight: bold;">
        <span class="label">Grand Total</span>
        <span class="value">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
    </div>

    @if($booking->catatan)
    <div class="divider"></div>
    <div class="section-title">Catatan</div>
    <p style="font-size: 10px;">{{ $booking->catatan }}</p>
    @endif

    <div class="divider"></div>

    <div class="footer">
        <p>Terima kasih telah menggunakan Sewa Kamera Pro</p>
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
