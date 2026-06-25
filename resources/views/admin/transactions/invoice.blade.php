<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->kode_transaksi }}</title>
    <style>
        @page { margin: 0; padding: 0; size: 80mm auto; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Courier New', monospace; }
        body { width: 80mm; margin: 0 auto; padding: 5mm; font-size: 12px; line-height: 1.3; color: #000; background: #fff; }
        .invoice { width: 100%; }
        .header { text-align: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed #000; }
        .company-name { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        .company-address { font-size: 10px; margin-bottom: 2px; }
        .company-contact { font-size: 10px; margin-bottom: 3px; }
        .invoice-info { text-align: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed #000; }
        .invoice-title { font-size: 13px; font-weight: bold; margin-bottom: 3px; }
        .invoice-number { font-size: 11px; margin-bottom: 2px; }
        .invoice-date { font-size: 11px; margin-bottom: 2px; }
        .status-badge { display: inline-block; padding: 1px 6px; border: 1px solid #000; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .section { margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed #000; }
        .section-title { font-size: 11px; font-weight: bold; margin-bottom: 4px; text-decoration: underline; }
        .customer-row { display: flex; margin-bottom: 2px; font-size: 11px; }
        .customer-label { width: 35mm; font-weight: bold; }
        .customer-value { flex: 1; }
        .rental-row { display: flex; margin-bottom: 2px; font-size: 11px; }
        .rental-label { width: 30mm; font-weight: bold; }
        .rental-value { flex: 1; }
        .products-table { width: 100%; margin: 8px 0; border-collapse: collapse; font-size: 10px; }
        .products-table th { text-align: left; padding: 3px 2px; border-bottom: 1px solid #000; font-weight: bold; }
        .products-table td { padding: 3px 2px; border-bottom: 1px dashed #ccc; }
        .product-name { max-width: 30mm; word-wrap: break-word; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { margin-top: 8px; padding-top: 8px; border-top: 1px dashed #000; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 11px; }
        .summary-label { font-weight: bold; }
        .grand-total-row { border-top: 2px solid #000; padding-top: 5px; margin-top: 5px; font-weight: bold; font-size: 12px; }
        .payment-info { font-size: 10px; }
        .payment-row { display: flex; margin-bottom: 2px; }
        .payment-label { width: 25mm; font-weight: bold; }
        .payment-value { flex: 1; }
        .notes { margin-top: 8px; padding-top: 8px; border-top: 1px dashed #000; font-size: 10px; }
        .notes-title { font-weight: bold; margin-bottom: 3px; }
        .footer { margin-top: 10px; padding-top: 8px; border-top: 2px solid #000; text-align: center; font-size: 9px; line-height: 1.2; }
        .thank-you { font-weight: bold; margin-bottom: 3px; }
        .terms { font-style: italic; margin-bottom: 3px; }
        .print-time { font-size: 8px; color: #666; }
        .divider { text-align: center; margin: 8px 0; font-size: 11px; }
        @media print {
            body { padding: 2mm; }
            .no-print { display: none; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .page-break { page-break-after: always; }
        }
        .barcode-container { text-align: center; margin: 5px 0; }
        .barcode { font-family: 'Libre Barcode 39', monospace; font-size: 20px; letter-spacing: 2px; }
        .barcode-number { font-size: 9px; margin-top: 2px; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="company-name">SEWA KAMERA PRO</div>
            <div class="company-address">Jl. Contoh No. 123, Jakarta</div>
            <div class="company-contact">Tel: 0812-3456-7890</div>
            <div class="company-contact">www.sewakamerapro.com</div>
        </div>

        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">No: {{ $transaction->kode_transaksi }}</div>
            <div class="invoice-date">Tgl: {{ $transaction->created_at->format('d/m/Y H:i') }}</div>
            <div style="margin-top: 3px;">
                <span class="status-badge">{{ $transaction->status_pembayaran_label }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">PELANGGAN</div>
            <div class="customer-row"><div class="customer-label">Nama:</div><div class="customer-value">{{ $transaction->nama_customer }}</div></div>
            <div class="customer-row"><div class="customer-label">Telepon:</div><div class="customer-value">{{ $transaction->telepon_customer }}</div></div>
            @if($transaction->email_customer)
            <div class="customer-row"><div class="customer-label">Email:</div><div class="customer-value">{{ $transaction->email_customer }}</div></div>
            @endif
            @if($transaction->user && $transaction->user->uuid)
            <div class="customer-row"><div class="customer-label">ID Member:</div><div class="customer-value">{{ substr($transaction->user->uuid, 0, 8) }}</div></div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">PERIODE SEWA</div>
            <div class="rental-row">
                <div class="rental-label">Ambil:</div>
                <div class="rental-value">@if($transaction->tanggal_pengambilan){{ \Carbon\Carbon::parse($transaction->tanggal_pengambilan)->format('d/m/Y H:i') }}@else Belum ditentukan @endif</div>
            </div>
            <div class="rental-row">
                <div class="rental-label">Kembali:</div>
                <div class="rental-value">@if($transaction->tanggal_pengembalian){{ \Carbon\Carbon::parse($transaction->tanggal_pengembalian)->format('d/m/Y H:i') }}@else Belum ditentukan @endif</div>
            </div>
            <div class="rental-row"><div class="rental-label">Lama Sewa:</div><div class="rental-value">{{ $transaction->lama_sewa }} hari</div></div>
            <div class="rental-row"><div class="rental-label">Metode:</div><div class="rental-value">{{ $transaction->metode_pengambilan_label }}</div></div>
        </div>

        @if($transaction->alamat_pengiriman)
        <div class="section">
            <div class="section-title">ALAMAT PENGIRIMAN</div>
            <div style="font-size: 10px; line-height: 1.2;">
                {{ $transaction->alamat_pengiriman }}<br>
                {{ $transaction->kota_pengiriman }}, {{ $transaction->provinsi_pengiriman }}
            </div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">DETAIL PRODUK</div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="45%">Produk</th>
                        <th width="15%" class="text-center">Qty</th>
                        <th width="15%" class="text-center">Hari</th>
                        <th width="20%" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; $totalItems = 0; @endphp
                    @foreach($transaction->detailTransaksis as $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="product-name">{{ substr($detail->nama_produk, 0, 20) }}@if(strlen($detail->nama_produk) > 20)...@endif</td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-center">{{ $detail->lama_sewa }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalItems += $detail->jumlah; @endphp
                    @endforeach
                </tbody>
            </table>
            <div style="font-size: 10px; text-align: center; margin-top: 3px;">Total Item: {{ $totalItems }} barang</div>
        </div>

        <div class="divider">---------------------------</div>

        <div class="summary">
            <div class="summary-row"><div class="summary-label">Subtotal:</div><div class="summary-value">{{ number_format($transaction->subtotal, 0, ',', '.') }}</div></div>
            @if($transaction->diskon > 0)
            <div class="summary-row"><div class="summary-label">Diskon:</div><div class="summary-value">-{{ number_format($transaction->diskon, 0, ',', '.') }}</div></div>
            @endif
            @if($transaction->biaya_pengiriman > 0)
            <div class="summary-row"><div class="summary-label">Ongkir:</div><div class="summary-value">{{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</div></div>
            @endif
            @if($transaction->biaya_asuransi > 0)
            <div class="summary-row"><div class="summary-label">Asuransi:</div><div class="summary-value">{{ number_format($transaction->biaya_asuransi, 0, ',', '.') }}</div></div>
            @endif
            @if($transaction->biaya_lainnya > 0)
            <div class="summary-row"><div class="summary-label">Lainnya:</div><div class="summary-value">{{ number_format($transaction->biaya_lainnya, 0, ',', '.') }}</div></div>
            @endif
            <div class="summary-row"><div class="summary-label">Total Sewa:</div><div class="summary-value">{{ number_format($transaction->total_sewa, 0, ',', '.') }}</div></div>
            <div class="summary-row"><div class="summary-label">Deposit:</div><div class="summary-value">{{ number_format($transaction->deposit_amount, 0, ',', '.') }}</div></div>
            @if($transaction->admin_fee > 0)
            <div class="summary-row"><div class="summary-label">Biaya Admin:</div><div class="summary-value">{{ number_format($transaction->admin_fee, 0, ',', '.') }}</div></div>
            @endif
            <div class="summary-row grand-total-row"><div class="summary-label">GRAND TOTAL:</div><div class="summary-value">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</div></div>
        </div>

        <div class="divider">---------------------------</div>

        @if($transaction->paymentMethod)
        <div class="payment-info">
            <div class="section-title">PEMBAYARAN</div>
            <div class="payment-row"><div class="payment-label">Metode:</div><div class="payment-value">{{ $transaction->paymentMethod->name }}</div></div>
            @if($transaction->bank)<div class="payment-row"><div class="payment-label">Bank:</div><div class="payment-value">{{ $transaction->bank }}</div></div>@endif
            @if($transaction->va_number)<div class="payment-row"><div class="payment-label">VA Number:</div><div class="payment-value">{{ $transaction->va_number }}</div></div>@endif
            @if($transaction->payment_code)<div class="payment-row"><div class="payment-label">Kode Bayar:</div><div class="payment-value">{{ $transaction->payment_code }}</div></div>@endif
            @if($transaction->paid_at)<div class="payment-row"><div class="payment-label">Dibayar:</div><div class="payment-value">{{ \Carbon\Carbon::parse($transaction->paid_at)->format('d/m/Y H:i') }}</div></div>@endif
            @if($transaction->payment_expired_at)<div class="payment-row"><div class="payment-label">Batas Bayar:</div><div class="payment-value">{{ \Carbon\Carbon::parse($transaction->payment_expired_at)->format('d/m/Y H:i') }}</div></div>@endif
        </div>
        @endif

        <div class="barcode-container">
            <div class="barcode">*{{ $transaction->kode_transaksi }}*</div>
            <div class="barcode-number">{{ $transaction->kode_transaksi }}</div>
        </div>

        @if($transaction->catatan || $transaction->catatan_admin)
        <div class="notes">
            <div class="section-title">CATATAN</div>
            @if($transaction->catatan)<div><strong>Customer:</strong> {{ substr($transaction->catatan, 0, 50) }}</div>@endif
            @if($transaction->catatan_admin)<div><strong>Admin:</strong> {{ substr($transaction->catatan_admin, 0, 50) }}</div>@endif
        </div>
        @endif

        <div class="footer">
            <div class="thank-you">TERIMA KASIH</div>
            <div class="terms">* Deposit akan dikembalikan setelah barang dikembalikan dalam kondisi baik</div>
            <div class="terms">* Denda keterlambatan 10%/hari dari deposit</div>
            <div class="print-time">Dicetak: {{ now()->format('d/m/Y H:i:s') }}</div>
        </div>

        <div class="no-print" style="text-align: center; margin-top: 15px;">
            <button onclick="window.print()" style="padding: 8px 15px; background: #000; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; font-weight: bold;">CETAK INVOICE</button>
            <button onclick="window.close()" style="padding: 8px 15px; background: #666; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; margin-left: 5px;">✕ TUTUP</button>
        </div>
    </div>

    <script>
        window.onload = function() { setTimeout(function() { window.print(); }, 1000); };
        window.onafterprint = function() {};
    </script>
</body>
</html>
