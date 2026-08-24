@extends('layouts.admin')

@section('title', 'Detail Transaksi - Stekpro Multimedia & Broadcast')
@section('page-title', 'Detail Transaksi')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Detail Transaksi</h1>
        <div>
            <a href="{{ route('admin.transactions.print', $transaction->id) }}" target="_blank" class="bg-[#34c759] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#2db84d] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Invoice
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-[#1d1d1f]">Detail Transaksi</h2>
                        @php
                            $statusColors = ['draft' => 'badge-apple', 'menunggu_pembayaran' => 'badge-warning', 'diproses' => 'badge-brand', 'dikonfirmasi' => 'badge-brand', 'siap_diambil' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                            $statusTexts = ['draft' => 'Draft', 'menunggu_pembayaran' => 'Menunggu Pembayaran', 'diproses' => 'Diproses', 'dikonfirmasi' => 'Dikonfirmasi', 'siap_diambil' => 'Siap Diambil', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', 'ditolak' => 'Ditolak'];
                        @endphp
                        <span class="badge {{ $statusColors[$transaction->status_transaksi] ?? 'badge-apple' }} text-sm">
                            {{ $statusTexts[$transaction->status_transaksi] ?? $transaction->status_transaksi }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h6 class="font-bold mb-3">Informasi Transaksi</h6>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr><td class="font-semibold">Kode Transaksi</td><td>{{ $transaction->kode_transaksi }}</td></tr>
                                    <tr><td class="font-semibold">Tanggal Transaksi</td><td>{{ $transaction->created_at->format('d F Y H:i') }}</td></tr>
                                    <tr><td class="font-semibold">Tanggal Sewa</td><td>{{ \Carbon\Carbon::parse($transaction->tanggal_pengambilan)->format('d F Y') }} - {{ \Carbon\Carbon::parse($transaction->tanggal_pengembalian)->format('d F Y') }} ({{ $transaction->lama_sewa }} hari)</td></tr>
                                    <tr><td class="font-semibold">Metode Pengambilan</td><td>Ambil di Tempat</td></tr>
                                    <tr><td class="font-semibold">Metode Pengembalian</td><td>Kembali ke Toko</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h6 class="font-bold mb-3">Informasi Customer</h6>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr><td class="font-semibold">Nama</td><td>{{ $transaction->nama_customer }}</td></tr>
                                    <tr><td class="font-semibold">Email</td><td>{{ $transaction->email_customer }}</td></tr>
                                    <tr><td class="font-semibold">Telepon</td><td>{{ $transaction->telepon_customer }}</td></tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <h6 class="font-bold mb-3">Produk yang Disewa</h6>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Lama Sewa</th>
                                    <th class="text-end">Harga/Hari</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($transaction->detailTransaksis && $transaction->detailTransaksis->count() > 0)
                                    @foreach($transaction->detailTransaksis as $detail)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-2">
                                                    @if($detail->produk && $detail->produk->gambar_utama)
                                                        <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" alt="{{ $detail->nama_produk }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-[#f5f5f7] rounded flex items-center justify-center" style="width: 40px; height: 40px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div>{{ $detail->nama_produk }}</div>
                                                        <small class="text-[#6e6e73]">{{ $detail->kode_produk }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $detail->jumlah }}</td>
                                            <td class="text-center">{{ $detail->lama_sewa }} hari</td>
                                            <td class="text-end">Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-[#6e6e73]">Tidak ada detail transaksi</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-4">
                        <div class="w-full md:w-1/2">
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr><td class="font-semibold">Subtotal Sewa</td><td class="text-end">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
                                    @if($transaction->diskon > 0)
                                        <tr><td class="font-semibold">Diskon</td><td class="text-end text-[#d70015]">- Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</td></tr>
                                    @endif

                                    <tr><td class="font-semibold">Biaya Asuransi</td><td class="text-end">Rp {{ number_format($transaction->biaya_asuransi, 0, ',', '.') }}</td></tr>
                                    <tr><td class="font-semibold">Biaya Lainnya</td><td class="text-end">Rp {{ number_format($transaction->biaya_lainnya, 0, ',', '.') }}</td></tr>
                                    <tr><td class="font-semibold">Admin Fee</td><td class="text-end">Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</td></tr>
                                    <tr class="bg-[#f5f5f7]">
                                        <td class="font-bold">Grand Total</td>
                                        <td class="text-end text-lg font-bold text-[#0071e3]">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($transaction->kode_voucher)
                                <div role="alert" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0faf1] border border-[#d1f5d5] text-[#0071e3] text-sm mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <strong>Voucher Terpakai:</strong> {{ $transaction->kode_voucher }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Pembayaran</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr>
                                        <td class="font-semibold">Status Pembayaran</td>
                                        <td>@php $pc = ['pending' => 'badge-warning', 'capture' => 'badge-brand', 'settlement' => 'badge-success', 'deny' => 'badge-error', 'cancel' => 'badge-apple', 'expire' => 'badge-apple', 'failure' => 'badge-error', 'refund' => 'badge-brand', 'partial_refund' => 'badge-brand', 'chargeback' => 'badge-error']; @endphp
                                            <span class="badge {{ $pc[$transaction->status_pembayaran] ?? 'badge-apple' }}">{{ ucfirst($transaction->status_pembayaran) }}</span>
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
                            <table class="w-full text-sm">
                                <tbody>
                                    @if($transaction->paid_at)<tr><td class="font-semibold">Tanggal Bayar</td><td>{{ $transaction->paid_at->format('d F Y H:i') }}</td></tr>@endif
                                    @if($transaction->confirmed_at)<tr><td class="font-semibold">Tanggal Konfirmasi</td><td>{{ $transaction->confirmed_at->format('d F Y H:i') }}</td></tr>@endif
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
                                <table class="w-full text-sm">
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
                <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                    <div class="p-5">
                        <h2 class="text-lg font-semibold text-[#1d1d1f]">Catatan</h2>
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
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Aksi</h2>
                    <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Ubah Status Transaksi</span></label>
                            <select class="select-apple w-full" name="status_transaksi" required>
                                <option value="draft" {{ $transaction->status_transaksi == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="space-y-1nggu_pembayaran" {{ $transaction->status_transaksi == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="diproses" {{ $transaction->status_transaksi == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikonfirmasi" {{ $transaction->status_transaksi == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="siap_diambil" {{ $transaction->status_transaksi == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                <option value="selesai" {{ $transaction->status_transaksi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $transaction->status_transaksi == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="ditolak" {{ $transaction->status_transaksi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Catatan Admin (Opsional)</span></label>
                            <textarea class="input-apple resize-none w-full" name="catatan_admin" rows="3" placeholder="Masukkan catatan jika diperlukan...">{{ $transaction->catatan_admin }}</textarea>
                        </div>
                        <button type="submit" class="btn-dark-apple w-full mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Update Status
                        </button>
                    </form>

                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Customer</h2>
                    @if($transaction->user)
                        <div class="text-center mb-3">
                            @if($transaction->user->foto_profil)
                                                            <img src="{{ asset('storage/' . $transaction->user->foto_profil) }}" alt="Foto profil {{ $transaction->user->nama }}" class="rounded-full mb-3" width="80" height="80" style="object-fit: cover;">
                            @else
                                <div class="rounded-full bg-[#0071e3] text-[#0071e3]-content flex items-center justify-center mx-auto mb-3 font-bold text-2xl" style="width: 80px; height: 80px;">{{ strtoupper(substr($transaction->user->nama, 0, 1)) }}</div>
                            @endif
                            <h5 class="font-bold mb-1">{{ $transaction->user->nama }}</h5>
                            <p class="text-[#6e6e73] mb-3">{{ $transaction->user->email }}</p>
                        </div>
                        <table class="w-full text-sm">
                            <tbody>
                                <tr><td class="font-semibold">Status Akun</td><td><span class="badge {{ $transaction->user->status == 'active' ? 'badge-success' : 'badge-warning' }}">{{ $transaction->user->status == 'active' ? 'Aktif' : 'Nonaktif' }}</span></td></tr>
                                <tr><td class="font-semibold">Poin Reward</td><td>{{ $transaction->user->poin_reward }}</td></tr>
                                <tr><td class="font-semibold">Total Transaksi</td><td>{{ $transaction->user->transaksis ? $transaction->user->transaksis->count() : 0 }}</td></tr>
                            </tbody>
                        </table>
                        <a href="{{ route('admin.users.show', $transaction->user->id) }}" class="btn-dark-apple-outline-apple w-full mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Lihat Profil Customer
                        </a>
                    @else
                        <div class="text-center py-4"><p class="text-[#6e6e73]">Customer tidak terdaftar</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


