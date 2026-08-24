<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Transaksi #{{ $transaction->kode_transaksi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1e3a5f;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
            border: 1px solid #dee2e6;
        }
        .transaction-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stekpro Multimedia & Broadcast</h1>
        <p>Konfirmasi Transaksi</p>
    </div>
    
    <div class="content">
        <h2>Halo, {{ $transaction->nama_customer }}!</h2>
        <p>Terima kasih telah melakukan pemesanan di Stekpro Multimedia & Broadcast. Berikut adalah detail transaksi Anda:</p>
        
        <div class="transaction-details">
            <h3>Detail Transaksi</h3>
            <table style="width: 100%;">
                <tr>
                    <td><strong>Kode Transaksi</strong></td>
                    <td>{{ $transaction->kode_transaksi }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>{{ $transaction->created_at->format('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>
                        <span class="status-badge status-{{ $transaction->status_pembayaran }}">
                            {{ $transaction->status_pembayaran_label }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Total Pembayaran</strong></td>
                    <td style="font-size: 18px; color: #059669; font-weight: bold;">
                        Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
            
            <h4 style="margin-top: 20px;">Detail Penyewaan:</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="padding: 10px; text-align: left;">Produk</th>
                        <th style="padding: 10px; text-align: center;">Durasi</th>
                        <th style="padding: 10px; text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->detailTransaksis as $detail)
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 10px;">
                            <strong>{{ $detail->nama_produk }}</strong><br>
                            <small>{{ $detail->jumlah }} unit &times; Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}/hari</small>
                        </td>
                        <td style="padding: 10px; text-align: center;">
                            {{ $detail->lama_sewa }} hari
                        </td>
                        <td style="padding: 10px; text-align: right;">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="padding: 10px; text-align: right;">
                            <strong>Subtotal:</strong>
                        </td>
                        <td style="padding: 10px; text-align: right;">
                            Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 10px; text-align: right;">
                            <strong>Total:</strong>
                        </td>
                        <td style="padding: 10px; text-align: right; font-size: 16px; font-weight: bold; color: #059669;">
                            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        @if($transaction->status_pembayaran == 'pending')
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $transaction->midtrans_redirect_url ?? '#' }}" class="button">
                Lanjutkan Pembayaran
            </a>
        </p>
        <p>
            <strong>Instruksi Pembayaran:</strong><br>
            Silakan selesaikan pembayaran dalam waktu 24 jam. Setelah pembayaran berhasil,
            kami akan memproses pesanan Anda.
        </p>
        @endif
        
        <p>
            <strong>Informasi Pengambilan:</strong><br>
            Tanggal Pengambilan: {{ \Carbon\Carbon::parse($transaction->tanggal_pengambilan)->format('d F Y H:i') }}<br>
            Lokasi: Ambil di toko
        </p>
        
        <p>
            Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami:<br>
            &9742; (021) 1234-5678<br>
            &9993; support@sewakamerapro.com<br>
            &128172; WhatsApp: 0812-3456-7890
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Stekpro Multimedia & Broadcast. All rights reserved.</p>
        <p>Jl. Contoh No. 123, Sukabumi, Indonesia</p>
    </div>
</body>
</html>
