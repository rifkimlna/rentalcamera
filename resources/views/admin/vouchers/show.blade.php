@extends('layouts.admin')

@section('title', 'Detail Voucher - Stekpro Multimedia & Broadcast')
@section('page-title', 'Detail Voucher')

@section('content')
<x-flash-messages />
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Detail Voucher</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="bg-[#ff9500] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#e68600] transition-colors">
                Edit Voucher
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-6">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Voucher</h2>
                    <table class="w-full text-sm">
                        <tr>
                            <th class="w-40">Kode Voucher</th>
                            <td><span class="font-mono font-bold text-lg">{{ $voucher->kode_voucher }}</span></td>
                        </tr>
                        <tr>
                            <th>Nama Voucher</th>
                            <td>{{ $voucher->nama_voucher }}</td>
                        </tr>
                        <tr>
                            <th>Tipe</th>
                            <td><span class="badge-apple">{{ $voucher->type_label }}</span></td>
                        </tr>
                        <tr>
                            <th>Nilai</th>
                            <td class="font-semibold">{{ $voucher->value_formatted }}</td>
                        </tr>
                        <tr>
                            <th>Min. Pembelian</th>
                            <td>{{ $voucher->min_purchase_formatted }}</td>
                        </tr>
                        <tr>
                            <th>Maks. Diskon</th>
                            <td>{{ $voucher->max_discount_formatted ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kuota</th>
                            <td>{{ $voucher->kuota ?? 'Tidak terbatas' }}</td>
                        </tr>
                        <tr>
                            <th>Terpakai</th>
                            <td>{{ $voucher->kuota_terpakai }}</td>
                        </tr>
                        <tr>
                            <th>Sisa Kuota</th>
                            <td>
                                @if($voucher->kuota !== null)
                                    {{ $voucher->remaining_quota }}
                                @else
                                    &infin;
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>
                                {{ \Carbon\Carbon::parse($voucher->start_date)->format('d F Y H:i') }}
                                &mdash;
                                {{ \Carbon\Carbon::parse($voucher->end_date)->format('d F Y H:i') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $isExpired = now()->gt($voucher->end_date);
                                    $isUpcoming = now()->lt($voucher->start_date);
                                @endphp
                                @if(!$voucher->is_active)
                                    <span class="badge-apple">Nonaktif</span>
                                @elseif($isExpired)
                                    <span class="badge-error">Kedaluwarsa</span>
                                @elseif($isUpcoming)
                                    <span class="badge-warning">Akan Datang ({{ $voucher->days_remaining }} hari lagi)</span>
                                @else
                                    <span class="badge-success">Aktif (tersisa {{ $voucher->days_remaining }} hari)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>{{ $voucher->created_at->format('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diupdate</th>
                            <td>{{ $voucher->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Riwayat Penggunaan</h2>
                    @if($voucher->usages->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pengguna</th>
                                        <th>Diskon</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($voucher->usages as $usage)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $usage->user->nama ?? '-' }}</td>
                                            <td>{{ $usage->discount_amount_formatted }}</td>
                                            <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-[#6e6e73] text-center py-4">Belum ada penggunaan voucher ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Batasan</h2>
                    <table class="w-full text-sm">
                        <tr>
                            <th>Pengguna</th>
                            <td>
                                @if($voucher->user)
                                    {{ $voucher->user->nama }}
                                @else
                                    <span class="text-[#6e6e73]">Semua pengguna</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @if($voucher->kategori)
                                    {{ $voucher->kategori->nama_kategori }}
                                @else
                                    <span class="text-[#6e6e73]">Semua kategori</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Produk</th>
                            <td>
                                @if($voucher->produk)
                                    {{ $voucher->produk->nama_produk }}
                                @else
                                    <span class="text-[#6e6e73]">Semua produk</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
