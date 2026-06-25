@extends('layouts.admin')

@section('title', 'Detail Transaksi - Sewa Kamera Pro')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Detail Transaksi</h1>
        <div>
            <a href="{{ route('admin.transactions.print', $transaction->id) }}" target="_blank" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Invoice
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title">Detail Transaksi</h2>
                        @php
                            $statusColors = ['draft' => 'badge-ghost', 'menunggu_pembayaran' => 'badge-warning', 'diproses' => 'badge-info', 'dikonfirmasi' => 'badge-primary', 'dikemas' => 'badge-primary', 'dikirim' => 'badge-success', 'dalam_perjalanan' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                            $statusTexts = ['draft' => 'Draft', 'menunggu_pembayaran' => 'Menunggu Pembayaran', 'diproses' => 'Diproses', 'dikonfirmasi' => 'Dikonfirmasi', 'dikemas' => 'Dikemas', 'dikirim' => 'Dikirim', 'dalam_perjalanan' => 'Dalam Perjalanan', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'];
                        @endphp
                        <span class="badge {{ $statusColors[$transaction->status_transaksi] ?? 'badge-ghost' }} text-sm">
                            {{ $statusTexts[$transaction->status_transaksi] ?? $transaction->status_transaksi }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h6 class="font-bold mb-3">Informasi Transaksi</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td class="font-semibold">Kode Transaksi</td><td>{{ $transaction->kode_transaksi }}</td></tr>
                                    <tr><td class="font-semibold">Tanggal Transaksi</td><td>{{ $transaction->created_at->format('d F Y H:i') }}</td></tr>
                                    <tr><td class="font-semibold">Tanggal Sewa</td><td>{{ \Carbon\Carbon::parse($transaction->tanggal_pengambilan)->format('d F Y') }} - {{ \Carbon\Carbon::parse($transaction->tanggal_pengembalian)->format('d F Y') }} ({{ $transaction->lama_sewa }} hari)</td></tr>
                                    <tr><td class="font-semibold">Metode Pengambilan</td><td>{{ ['pickup' => 'Ambil di Tempat', 'delivery' => 'Dikirim', 'both' => 'Kedua-duanya'][$transaction->metode_pengambilan] ?? $transaction->metode_pengambilan }}</td></tr>
                                    <tr><td class="font-semibold">Metode Pengembalian</td><td>{{ ['return' => 'Kembalikan', 'pickup' => 'Diambil', 'both' => 'Kedua-duanya'][$transaction->metode_pengembalian] ?? $transaction->metode_pengembalian }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h6 class="font-bold mb-3">Informasi Customer</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td class="font-semibold">Nama</td><td>{{ $transaction->nama_customer }}</td></tr>
                                    <tr><td class="font-semibold">Email</td><td>{{ $transaction->email_customer }}</td></tr>
                                    <tr><td class="font-semibold">Telepon</td><td>{{ $transaction->telepon_customer }}</td></tr>
                                    <tr><td class="font-semibold">Alamat</td><td>{{ $transaction->alamat_pengiriman ?? '-' }}</td></tr>
                                    <tr><td class="font-semibold">Kota</td><td>{{ $transaction->kota_pengiriman ?? '-' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h6 class="font-bold mb-3">Produk yang Disewa</h6>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Lama Sewa</th>
                                    <th class="text-end">Harga/Hari</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Deposit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($transaction->detailTransaksis && $transaction->detailTransaksis->count() > 0)
                                    @foreach($transaction->detailTransaksis as $detail)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-2">
                                                    @if($detail->produk && $detail->produk->gambar_utama)
                                                        <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-base-200 rounded flex items-center justify-center" style="width: 40px; height: 40px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div>{{ $detail->nama_produk }}</div>
                                                        <small class="text-base-content/60">{{ $detail->kode_produk }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $detail->jumlah }}</td>
                                            <td class="text-center">{{ $detail->lama_sewa }} hari</td>
                                            <td class="text-end">Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->deposit_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-base-content/60">Tidak ada detail transaksi</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-4">
                        <div class="w-full md:w-1/2">
                            <table class="table table-sm">
                                <tbody>
                                    <tr><td class="font-semibold">Subtotal Sewa</td><td class="text-end">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
                                    @if($transaction->diskon > 0)
                                        <tr><td class="font-semibold">Diskon</td><td class="text-end text-error">- Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</td></tr>
                                    @endif
                                    <tr><td class="font-semibold">Biaya Pengiriman</td><td class="text-end">Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</td></tr>
                                    <tr><td class="font-semibold">Biaya Asuransi</td><td class="text-end">Rp {{ number_format($transaction->biaya_asuransi, 0, ',', '.') }}</td></tr>
                                    <tr><td class="font-semibold">Biaya Lainnya</td><td class="text-end">Rp {{ number_format($transaction->biaya_lainnya, 0, ',', '.') }}</td></tr>
                                    <tr><td class="font-semibold">Deposit</td><td class="text-end">Rp {{ number_format($transaction->deposit_amount, 0, ',', '.') }}</td></tr>
                                    <tr><td class="font-semibold">Admin Fee</td><td class="text-end">Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</td></tr>
                                    <tr class="bg-base-200">
                                        <td class="font-bold">Grand Total</td>
                                        <td class="text-end text-lg font-bold text-primary">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($transaction->kode_voucher)
                                <div role="alert" class="alert alert-info mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <strong>Voucher Terpakai:</strong> {{ $transaction->kode_voucher }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h2 class="card-title">Informasi Pembayaran</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="font-semibold">Status Pembayaran</td>
                                        <td>@php $pc = ['pending' => 'badge-warning', 'capture' => 'badge-info', 'settlement' => 'badge-success', 'deny' => 'badge-error', 'cancel' => 'badge-ghost', 'expire' => 'badge-ghost', 'failure' => 'badge-error', 'refund' => 'badge-info', 'partial_refund' => 'badge-info', 'chargeback' => 'badge-error']; @endphp
                                            <span class="badge {{ $pc[$transaction->status_pembayaran] ?? 'badge-ghost' }}">{{ ucfirst($transaction->status_pembayaran) }}</span>
                                        </td>
                                    </tr>
                                    <tr><td class="font-semibold">Metode Pembayaran</td><td>{{ $transaction->paymentMethod->name ?? '-' }}</td></tr>
                                    @if($transaction->bank)<tr><td class="font-semibold">Bank</td><td>{{ $transaction->bank }}</td></tr>@endif
                                    @if($transaction->va_number)<tr><td class="font-semibold">Virtual Account</td><td>{{ $transaction->va_number }}</td></tr>@endif
                                    @if($transaction->payment_code)<tr><td class="font-semibold">Kode Pembayaran</td><td>{{ $transaction->payment_code }}</td></tr>@endif
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="table table-sm">
                                <tbody>
                                    @if($transaction->paid_at)<tr><td class="font-semibold">Tanggal Bayar</td><td>{{ $transaction->paid_at->format('d F Y H:i') }}</td></tr>@endif
                                    @if($transaction->confirmed_at)<tr><td class="font-semibold">Tanggal Konfirmasi</td><td>{{ $transaction->confirmed_at->format('d F Y H:i') }}</td></tr>@endif
                                    @if($transaction->shipped_at)<tr><td class="font-semibold">Tanggal Dikirim</td><td>{{ $transaction->shipped_at->format('d F Y H:i') }}</td></tr>@endif
                                    @if($transaction->completed_at)<tr><td class="font-semibold">Tanggal Selesai</td><td>{{ $transaction->completed_at->format('d F Y H:i') }}</td></tr>@endif
                                    @if($transaction->cancelled_at)<tr><td class="font-semibold">Tanggal Dibatalkan</td><td>{{ $transaction->cancelled_at->format('d F Y H:i') }}</td></tr>@endif
                                    @if($transaction->refunded_at)<tr><td class="font-semibold">Tanggal Refund</td><td>{{ $transaction->refunded_at->format('d F Y H:i') }}</td></tr>@endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($transaction->paymentLogs && $transaction->paymentLogs->count() > 0)
                        <div class="mt-4">
                            <h6 class="font-bold">Riwayat Pembayaran</h6>
                            <div class="overflow-x-auto">
                                <table class="table table-zebra table-sm">
                                    <thead>
                                        <tr><th>Tanggal</th><th>Status</th><th>Payment Type</th><th>Amount</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction->paymentLogs as $log)
                                            <tr>
                                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $log->transaction_status }}</td>
                                                <td>{{ $log->payment_type }}</td>
                                                <td>Rp {{ number_format($log->gross_amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($transaction->catatan || $transaction->catatan_admin)
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <h2 class="card-title">Catatan</h2>
                        @if($transaction->catatan)
                            <div class="mb-3"><strong>Catatan Customer:</strong><p class="mb-0">{{ $transaction->catatan }}</p></div>
                        @endif
                        @if($transaction->catatan_admin)
                            <div><strong>Catatan Admin:</strong><p class="mb-0">{{ $transaction->catatan_admin }}</p></div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div>
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h2 class="card-title">Aksi</h2>
                    <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                        @csrf
                        <div>
                            <label class="label"><span class="label-text">Ubah Status Transaksi</span></label>
                            <select class="select select-bordered w-full" name="status_transaksi" required>
                                <option value="draft" {{ $transaction->status_transaksi == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="menunggu_pembayaran" {{ $transaction->status_transaksi == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="diproses" {{ $transaction->status_transaksi == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikonfirmasi" {{ $transaction->status_transaksi == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="dikemas" {{ $transaction->status_transaksi == 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                                <option value="dikirim" {{ $transaction->status_transaksi == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="dalam_perjalanan" {{ $transaction->status_transaksi == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                <option value="selesai" {{ $transaction->status_transaksi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $transaction->status_transaksi == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="ditolak" {{ $transaction->status_transaksi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label class="label"><span class="label-text">Catatan Admin (Opsional)</span></label>
                            <textarea class="textarea textarea-bordered w-full" name="catatan_admin" rows="3" placeholder="Masukkan catatan jika diperlukan...">{{ $transaction->catatan_admin }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Update Status
                        </button>
                    </form>

                    @if(in_array($transaction->status_transaksi, ['dikirim', 'dalam_perjalanan', 'selesai']))
                        <div class="mt-4">
                            <h6 class="font-bold mb-3">Informasi Pengiriman</h6>
                            @if($transaction->pengiriman)
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><td class="font-semibold">Kurir</td><td>{{ $transaction->pengiriman->kurir ?? '-' }}</td></tr>
                                        <tr><td class="font-semibold">No. Resi</td><td>{{ $transaction->pengiriman->no_resi ?? '-' }}</td></tr>
                                        <tr>
                                            <td class="font-semibold">Status</td>
                                            <td>@php $sc = ['pending' => 'badge-warning', 'picked_up' => 'badge-info', 'in_transit' => 'badge-primary', 'delivered' => 'badge-success', 'returned' => 'badge-ghost', 'cancelled' => 'badge-error']; @endphp
                                                <span class="badge {{ $sc[$transaction->pengiriman->status] ?? 'badge-ghost' }}">{{ ucfirst(str_replace('_', ' ', $transaction->pengiriman->status)) }}</span>
                                            </td>
                                        </tr>
                                        @if($transaction->pengiriman->estimated_delivery)<tr><td class="font-semibold">Estimasi Tiba</td><td>{{ \Carbon\Carbon::parse($transaction->pengiriman->estimated_delivery)->format('d F Y H:i') }}</td></tr>@endif
                                        @if($transaction->pengiriman->actual_delivery)<tr><td class="font-semibold">Tanggal Diterima</td><td>{{ \Carbon\Carbon::parse($transaction->pengiriman->actual_delivery)->format('d F Y H:i') }}</td></tr>@endif
                                    </tbody>
                                </table>
                            @endif
                            <button type="button" class="btn btn-outline w-full mt-2" onclick="document.getElementById('shippingModal').showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                Kelola Pengiriman
                            </button>
                        </div>
                    @endif

                    @if($transaction->deposit_amount > 0)
                        <div role="alert" class="alert alert-info mt-4">
                            <h6 class="font-bold mb-2">Status Deposit</h6>
                            <p class="mb-2">Jumlah Deposit: <strong>Rp {{ number_format($transaction->deposit_amount, 0, ',', '.') }}</strong></p>
                            @php $dc = ['pending' => 'badge-warning', 'dibayar' => 'badge-success', 'dikembalikan' => 'badge-info', 'dipotong' => 'badge-primary']; $dt = ['pending' => 'Menunggu', 'dibayar' => 'Sudah Dibayar', 'dikembalikan' => 'Sudah Dikembalikan', 'dipotong' => 'Dipotong dari Saldo']; @endphp
                            <span class="badge {{ $dc[$transaction->status_deposit] ?? 'badge-ghost' }}">{{ $dt[$transaction->status_deposit] ?? $transaction->status_deposit }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title">Informasi Customer</h2>
                    @if($transaction->user)
                        <div class="text-center mb-3">
                            @if($transaction->user->foto_profil)
                                                            <img src="{{ asset('storage/' . $transaction->user->foto_profil) }}" class="rounded-full mb-3" width="80" height="80" style="object-fit: cover;">
                            @else
                                <div class="rounded-full bg-primary text-primary-content flex items-center justify-center mx-auto mb-3 font-bold text-2xl" style="width: 80px; height: 80px;">{{ strtoupper(substr($transaction->user->nama, 0, 1)) }}</div>
                            @endif
                            <h5 class="font-bold mb-1">{{ $transaction->user->nama }}</h5>
                            <p class="text-base-content/60 mb-3">{{ $transaction->user->email }}</p>
                        </div>
                        <table class="table table-sm">
                            <tbody>
                                <tr><td class="font-semibold">Status Akun</td><td><span class="badge {{ $transaction->user->status == 'active' ? 'badge-success' : 'badge-warning' }}">{{ $transaction->user->status == 'active' ? 'Aktif' : 'Nonaktif' }}</span></td></tr>
                                <tr><td class="font-semibold">Saldo Deposit</td><td>Rp {{ number_format($transaction->user->saldo_deposit, 0, ',', '.') }}</td></tr>
                                <tr><td class="font-semibold">Poin Reward</td><td>{{ $transaction->user->poin_reward }}</td></tr>
                                <tr><td class="font-semibold">Total Transaksi</td><td>{{ $transaction->user->transaksis ? $transaction->user->transaksis->count() : 0 }}</td></tr>
                            </tbody>
                        </table>
                        <a href="{{ route('admin.users.show', $transaction->user->id) }}" class="btn btn-outline w-full mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Lihat Profil Customer
                        </a>
                    @else
                        <div class="text-center py-4"><p class="text-base-content/60">Customer tidak terdaftar</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="shippingModal" class="modal">
    <div class="modal-box">
        <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
        <h3 class="text-lg font-bold">Kelola Pengiriman</h3>
        <form action="{{ route('admin.transactions.update-shipping', $transaction->id) }}" method="POST">
            @csrf
            <div class="py-4">
                <div><label class="label"><span class="label-text">Kurir</span></label><input type="text" class="input input-bordered w-full" name="kurir" value="{{ $transaction->pengiriman->kurir ?? '' }}" placeholder="Contoh: JNE, Gojek, Grab"></div>
                <div class="mt-4"><label class="label"><span class="label-text">Nomor Resi</span></label><input type="text" class="input input-bordered w-full" name="no_resi" value="{{ $transaction->pengiriman->no_resi ?? '' }}" placeholder="Masukkan nomor resi"></div>
                <div class="mt-4">
                    <label class="label"><span class="label-text">Status Pengiriman</span></label>
                    <select class="select select-bordered w-full" name="status" required>
                        <option value="pending" {{ ($transaction->pengiriman->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="picked_up" {{ ($transaction->pengiriman->status ?? '') == 'picked_up' ? 'selected' : '' }}>Diambil</option>
                        <option value="in_transit" {{ ($transaction->pengiriman->status ?? '') == 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan</option>
                        <option value="delivered" {{ ($transaction->pengiriman->status ?? '') == 'delivered' ? 'selected' : '' }}>Terkirim</option>
                        <option value="returned" {{ ($transaction->pengiriman->status ?? '') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="cancelled" {{ ($transaction->pengiriman->status ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="mt-4"><label class="label"><span class="label-text">Estimasi Tiba</span></label><input type="datetime-local" class="input input-bordered w-full" name="estimated_delivery" value="{{ $transaction->pengiriman->estimated_delivery ?? '' }}"></div>
                <div class="mt-4"><label class="label"><span class="label-text">Catatan</span></label><textarea class="textarea textarea-bordered w-full" name="catatan" rows="3">{{ $transaction->pengiriman->catatan ?? '' }}</textarea></div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('shippingModal').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
