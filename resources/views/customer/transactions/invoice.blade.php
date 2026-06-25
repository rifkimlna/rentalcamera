@extends('layouts.customer')

@section('title', 'Invoice - Sewa Kamera Pro')
@section('page-title', 'Invoice')

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #invoice-area, #invoice-area * { visibility: visible; }
        #invoice-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .card { border: 1px solid #e5e7eb !important; box-shadow: none !important; }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('customer.transactions.show', $transaction->id) }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="btn btn-neutral btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak
        </button>
    </div>

    <div id="invoice-area" class="card bg-white border border-base-300">
        <div class="card-body p-6 sm:p-10">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 pb-6 border-b border-base-300">
                <div>
                    <h2 class="text-lg tracking-tight font-light mb-1">Sewa Kamera Pro</h2>
                    <p class="text-xs text-base-content/60 leading-relaxed">
                        Jl. Contoh No. 123<br>
                        Jakarta, Indonesia<br>
                        info@sewakamerapro.com
                    </p>
                </div>
                <div class="text-left sm:text-right">
                    <h1 class="text-xl tracking-tight font-light mb-1">INVOICE</h1>
                    <p class="text-xs text-base-content/60">
                        <span class="inline-block w-20">No. Invoice</span>
                        <span class="font-mono">{{ $transaction->kode_transaksi }}</span><br>
                        <span class="inline-block w-20">Tanggal</span>
                        <span>{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y') }}</span><br>
                        <span class="inline-block w-20">Status</span>
                        <span>{{ $transaction->status_pembayaran_label }}</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-base-300">
                <div>
                    <h4 class="text-xs uppercase tracking-wider text-base-content/60 mb-2">Informasi Pelanggan</h4>
                    <p class="text-sm leading-relaxed">
                        <span class="font-medium">{{ $transaction->nama_customer }}</span><br>
                        <span class="text-xs text-base-content/60">{{ $transaction->telepon_customer }}</span><br>
                        <span class="text-xs text-base-content/60">{{ $transaction->email_customer }}</span>
                    </p>
                </div>
                <div>
                    <h4 class="text-xs uppercase tracking-wider text-base-content/60 mb-2">Informasi Sewa</h4>
                    <p class="text-sm leading-relaxed">
                        <span class="text-xs text-base-content/60">Tanggal Sewa:</span>
                        <span class="text-sm">{{ \Carbon\Carbon::parse($transaction->tanggal_pengambilan)->translatedFormat('d M Y') }}</span><br>
                        <span class="text-xs text-base-content/60">Tanggal Kembali:</span>
                        <span class="text-sm">{{ \Carbon\Carbon::parse($transaction->tanggal_pengembalian)->translatedFormat('d M Y') }}</span><br>
                        <span class="text-xs text-base-content/60">Lama Sewa:</span>
                        <span class="text-sm">{{ $transaction->lama_sewa }} hari</span>
                    </p>
                </div>
            </div>

            <div class="py-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-base-300">
                            <th class="text-left py-2 text-xs uppercase tracking-wider text-base-content/60 font-normal">Produk</th>
                            <th class="text-center py-2 text-xs uppercase tracking-wider text-base-content/60 font-normal">Qty</th>
                            <th class="text-center py-2 text-xs uppercase tracking-wider text-base-content/60 font-normal">Harga/Hari</th>
                            <th class="text-center py-2 text-xs uppercase tracking-wider text-base-content/60 font-normal">Hari</th>
                            <th class="text-right py-2 text-xs uppercase tracking-wider text-base-content/60 font-normal">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->detailTransaksis as $detail)
                            <tr class="border-b border-base-200">
                                <td class="py-3 text-sm">{{ $detail->nama_produk }}</td>
                                <td class="py-3 text-center text-xs">{{ $detail->jumlah }}</td>
                                <td class="py-3 text-center text-xs">Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}</td>
                                <td class="py-3 text-center text-xs">{{ $detail->lama_sewa }}</td>
                                <td class="py-3 text-right text-xs">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end border-t border-base-300 pt-4">
                <div class="w-full sm:w-64 space-y-2 text-sm">
                    <div class="flex justify-between text-xs">
                        <span class="text-base-content/60">Subtotal</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->diskon > 0)
                        <div class="flex justify-between text-xs">
                            <span class="text-base-content/60">Diskon</span>
                            <span>-Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->biaya_pengiriman > 0)
                        <div class="flex justify-between text-xs">
                            <span class="text-base-content/60">Biaya Kirim</span>
                            <span>Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->biaya_asuransi > 0)
                        <div class="flex justify-between text-xs">
                            <span class="text-base-content/60">Asuransi</span>
                            <span>Rp {{ number_format($transaction->biaya_asuransi, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->admin_fee > 0)
                        <div class="flex justify-between text-xs">
                            <span class="text-base-content/60">Biaya Admin</span>
                            <span>Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm font-medium border-t border-base-300 pt-2">
                        <span>Total</span>
                        <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-base-300 text-center text-xs text-base-content/40">
                <p class="leading-relaxed">Terima kasih telah menggunakan layanan Sewa Kamera Pro.</p>
                <p class="leading-relaxed">Invoice ini adalah bukti pembayaran yang sah.</p>
            </div>
        </div>
    </div>
</div>
@endsection
