@extends('layouts.customer')

@section('title', 'Detail Transaksi - Sewa Kamera Pro')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs text-base-content/60 font-mono">{{ $transaction->kode_transaksi }}</p>
        </div>
        <a href="{{ route('customer.transactions.index') }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h3 class="text-sm tracking-tight font-light mb-4">Ringkasan Pesanan</h3>
                    <div class="overflow-x-auto">
                        <table class="table text-sm">
                            <thead>
                                <tr class="text-xs text-base-content/60 uppercase tracking-wider">
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Lama Sewa</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->detailTransaksis as $detail)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="shrink-0">
                                                    @if($detail->produk && $detail->produk->gambar_utama)
                                                        <img src="{{ asset('storage/' . $detail->produk->gambar_utama) }}" alt="{{ $detail->nama_produk }}" class="rounded w-10 h-10 object-cover">
                                                    @else
                                                        <div class="bg-base-200 rounded w-10 h-10 flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm">{{ $detail->nama_produk }}</p>
                                                    <p class="text-xs text-base-content/60">Rp {{ number_format($detail->harga_per_hari, 0, ',', '.') }}/hari</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center text-xs">{{ $detail->jumlah }}</td>
                                        <td class="text-center text-xs">{{ $detail->lama_sewa }} hari</td>
                                        <td class="text-right text-xs">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h3 class="text-sm tracking-tight font-light mb-4">Status Transaksi</h3>
                    <ul class="steps steps-vertical text-xs">
                        @php
                            $statusOrder = ['menunggu_pembayaran', 'dikonfirmasi', 'dikemas', 'dikirim'];
                            $currentIdx = array_search($transaction->status_transaksi, $statusOrder);
                            if ($currentIdx === false) {
                                $currentIdx = $transaction->status_transaksi === 'selesai' ? 4 : -1;
                            }
                            $steps = [
                                'menunggu_pembayaran' => 'Pesanan Dibuat',
                                'dikonfirmasi' => 'Dikonfirmasi',
                                'dikemas' => 'Dikemas',
                                'dikirim' => 'Dikirim / Siap Ambil',
                                'selesai' => 'Selesai',
                            ];
                            $stepKeys = array_keys($steps);
                        @endphp
                        @foreach($steps as $key => $label)
                            @php
                                $stepIdx = array_search($key, $stepKeys);
                                $isCompleted = $stepIdx <= $currentIdx;
                                $isCurrent = $key === $transaction->status_transaksi;
                            @endphp
                            <li class="step {{ $isCompleted ? 'step-neutral' : '' }} {{ $isCurrent ? 'font-medium' : 'text-base-content/40' }}">
                                {{ $label }}
                                @if($isCurrent && in_array($transaction->status_transaksi, ['dikonfirmasi', 'dikemas', 'dikirim']))
                                    <br><span class="text-xs text-base-content/60">Sedang diproses</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body space-y-3">
                    <h3 class="text-sm tracking-tight font-light mb-2">Informasi Transaksi</h3>
                    <div class="flex justify-between text-xs py-1 border-b border-base-300">
                        <span class="text-base-content/60">Status</span>
                        <span class="badge badge-outline badge-sm">{{ $transaction->status_transaksi_label }}</span>
                    </div>
                    <div class="flex justify-between text-xs py-1 border-b border-base-300">
                        <span class="text-base-content/60">Pembayaran</span>
                        <span class="badge badge-outline badge-sm">{{ $transaction->status_pembayaran_label }}</span>
                    </div>
                    <div class="flex justify-between text-xs py-1 border-b border-base-300">
                        <span class="text-base-content/60">Tanggal Sewa</span>
                        <span>{{ \Carbon\Carbon::parse($transaction->tanggal_pengambilan)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs py-1 border-b border-base-300">
                        <span class="text-base-content/60">Tanggal Kembali</span>
                        <span>{{ \Carbon\Carbon::parse($transaction->tanggal_pengembalian)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs py-1 border-b border-base-300">
                        <span class="text-base-content/60">Lama Sewa</span>
                        <span>{{ $transaction->lama_sewa }} hari</span>
                    </div>
                    <div class="flex justify-between text-xs py-1 border-b border-base-300">
                        <span class="text-base-content/60">Pengambilan</span>
                        <span>{{ $transaction->metode_pengambilan_label }}</span>
                    </div>
                    <div class="flex justify-between text-xs py-1">
                        <span class="text-base-content/60">Pengembalian</span>
                        <span>{{ $transaction->metode_pengembalian_label }}</span>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body space-y-2">
                    <h3 class="text-sm tracking-tight font-light mb-2">Rincian Biaya</h3>
                    <div class="flex justify-between text-xs py-1">
                        <span class="text-base-content/60">Subtotal</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->diskon > 0)
                        <div class="flex justify-between text-xs py-1">
                            <span class="text-base-content/60">Diskon</span>
                            <span>-Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->biaya_pengiriman > 0)
                        <div class="flex justify-between text-xs py-1">
                            <span class="text-base-content/60">Biaya Kirim</span>
                            <span>Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->biaya_asuransi > 0)
                        <div class="flex justify-between text-xs py-1">
                            <span class="text-base-content/60">Asuransi</span>
                            <span>Rp {{ number_format($transaction->biaya_asuransi, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->admin_fee > 0)
                        <div class="flex justify-between text-xs py-1">
                            <span class="text-base-content/60">Biaya Admin</span>
                            <span>Rp {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-xs py-2 border-t border-base-300 font-medium">
                        <span>Total (Termasuk Deposit)</span>
                        <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($transaction->catatan)
                <div class="card bg-base-100 border border-base-300">
                    <div class="card-body">
                        <h3 class="text-xs tracking-tight font-light mb-2">Catatan</h3>
                        <p class="text-xs text-base-content/60 leading-relaxed">{{ $transaction->catatan }}</p>
                    </div>
                </div>
            @endif

            <div class="flex flex-col gap-2">
                @if(in_array($transaction->status_pembayaran, ['pending']) && in_array($transaction->status_transaksi, ['menunggu_pembayaran']))
                    <a href="{{ route('customer.checkout.payment', $transaction->id) }}" class="btn btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Lanjutkan Pembayaran
                    </a>
                @endif
                @if($transaction->canBeCancelled())
                    <form method="POST" action="{{ route('customer.transactions.cancel', $transaction->id) }}" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batalkan
                        </button>
                    </form>
                @endif
                <a href="{{ route('contact') }}" class="btn btn-ghost btn-sm w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Hubungi Admin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
