@extends('layouts.admin')

@section('title', 'Detail Produk - Sewa Kamera Pro')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Detail Produk</h1>
        <div>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($product->gambar_utama)
                                <img src="{{ asset('storage/' . $product->gambar_utama) }}"
                                     class="rounded w-full mb-3" alt="{{ $product->nama_produk }}" style="max-height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-base-200 rounded flex items-center justify-center" style="height: 300px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                                </div>
                            @endif

                            @php
                                $additionalImages = json_decode($product->gambar_tambahan, true) ?? [];
                            @endphp

                            @if(!empty($additionalImages))
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($additionalImages as $image)
                                        <div>
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 class="rounded w-full" style="height: 80px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold mb-3">{{ $product->nama_produk }}</h2>

                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->rating))
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @elseif($i - 0.5 <= $product->rating)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                        @endif
                                    @endfor
                                    <span class="ms-2 text-sm text-base-content/60">({{ number_format($product->rating, 1) }})</span>
                                </span>
                                <small class="text-base-content/60">{{ $product->jumlah_ulasan }} ulasan</small>
                            </div>

                            <div class="mb-4">
                                <h3 class="text-2xl font-bold text-primary">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</h3>
                                <small class="text-base-content/60">per hari</small>
                                @if($product->harga_per_minggu > 0)
                                    <div class="text-sm text-base-content/60">Rp {{ number_format($product->harga_per_minggu, 0, ',', '.') }} / minggu</div>
                                @endif
                                @if($product->harga_per_bulan > 0)
                                    <div class="text-sm text-base-content/60">Rp {{ number_format($product->harga_per_bulan, 0, ',', '.') }} / bulan</div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <p class="font-semibold">Kode Produk:</p>
                                    <p class="text-base-content/60">{{ $product->kode_produk }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Status:</p>
                                    @php
                                        $statusColors = [
                                            'available' => 'badge-success',
                                            'unavailable' => 'badge-ghost',
                                            'maintenance' => 'badge-warning'
                                        ];
                                        $statusTexts = [
                                            'available' => 'Tersedia',
                                            'unavailable' => 'Tidak Tersedia',
                                            'maintenance' => 'Maintenance'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$product->status] ?? 'badge-ghost' }}">
                                        {{ $statusTexts[$product->status] ?? $product->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <p class="font-semibold">Kategori:</p>
                                    <p class="text-base-content/60">{{ $product->kategori->nama_kategori ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Brand:</p>
                                    <p class="text-base-content/60">{{ $product->brand->nama_brand ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <p class="font-semibold">Stok Tersedia:</p>
                                    <p class="text-base-content/60">{{ $product->stok_tersedia }} dari {{ $product->stok_total }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Total Disewa:</p>
                                    <p class="text-base-content/60">{{ $product->jumlah_dipesan }} kali</p>
                                </div>
                            </div>

                            <div class="flex gap-2 mb-4">
                                @if($product->is_featured)
                                    <span class="badge badge-info">Featured</span>
                                @endif
                                @if($product->is_recommended)
                                    <span class="badge badge-primary">Recommended</span>
                                @endif
                            </div>

                            <div class="flex flex-col gap-2">
                                <form action="{{ route('admin.products.update-status', $product->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="join w-full">
                                        <select class="select select-bordered join-item flex-1" name="status">
                                            <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="unavailable" {{ $product->status == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                            <option value="maintenance" {{ $product->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                        <button type="submit" class="btn btn-outline join-item">Update</button>
                                    </div>
                                </form>
                                <button type="button" class="btn btn-outline" onclick="document.getElementById('stockModal').showModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    Kelola Stok
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-base-300">

                    <div class="mb-4">
                        <h3 class="text-lg font-bold mb-3">Deskripsi Produk</h3>
                        <p>{{ $product->deskripsi_lengkap ?? $product->deskripsi_singkat ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    @php
                        $specifications = json_decode($product->spesifikasi, true) ?? [];
                    @endphp

                    @if(!empty($specifications))
                        <div class="mb-4">
                            <h3 class="text-lg font-bold mb-3">Spesifikasi Teknis</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($specifications as $key => $value)
                                    <div><strong>{{ $key }}:</strong> {{ $value }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($product->fitur)
                        <div class="mb-4">
                            <h3 class="text-lg font-bold mb-3">Fitur Utama</h3>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $features = is_array($product->fitur) ? $product->fitur : explode(',', $product->fitur);
                                @endphp
                                @foreach($features as $feature)
                                    @if(trim($feature))
                                        <span class="badge badge-ghost border border-base-300">{{ trim($feature) }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-bold mb-3">Informasi Tambahan</h3>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="font-semibold">Berat</td>
                                        <td>{{ $product->berat ? $product->berat . ' gram' : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Dimensi</td>
                                        <td>{{ $product->dimensi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Serial Number</td>
                                        <td>{{ $product->serial_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Tahun Pembuatan</td>
                                        <td>{{ $product->tahun_pembuatan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Kondisi</td>
                                        <td>
                                            @php
                                                $conditionTexts = [
                                                    'baru' => 'Baru',
                                                    'bekas_excellent' => 'Bekas (Excellent)',
                                                    'bekas_good' => 'Bekas (Good)',
                                                    'bekas_fair' => 'Bekas (Fair)'
                                                ];
                                            @endphp
                                            {{ $conditionTexts[$product->kondisi] ?? $product->kondisi }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Min. Sewa</td>
                                        <td>{{ $product->minimum_sewa }} hari</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Max. Sewa</td>
                                        <td>{{ $product->maximum_sewa }} hari</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold mb-3">Statistik</h3>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="font-semibold">Rating</td>
                                        <td>{{ number_format($product->rating, 1) }} / 5.00</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Total Ulasan</td>
                                        <td>{{ $product->jumlah_ulasan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Total Disewa</td>
                                        <td>{{ $product->jumlah_dipesan }} kali</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Stok Total</td>
                                        <td>{{ $product->stok_total }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Stok Tersedia</td>
                                        <td>{{ $product->stok_tersedia }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Stok Dipinjam</td>
                                        <td>{{ $product->stok_dipinjam }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold">Stok Rusak</td>
                                        <td>{{ $product->stok_rusak }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($product->maintenance) && $product->maintenance->count() > 0)
                <div class="card bg-base-100 shadow-md mb-4">
                    <div class="card-body">
                        <h2 class="card-title">Riwayat Maintenance</h2>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra table-sm">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Deskripsi</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->maintenance as $maintenance)
                                        <tr>
                                            <td>{{ $maintenance->tanggal_mulai->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $jenisTexts = [
                                                        'routine' => 'Rutin',
                                                        'repair' => 'Perbaikan',
                                                        'cleaning' => 'Pembersihan',
                                                        'calibration' => 'Kalibrasi'
                                                    ];
                                                @endphp
                                                {{ $jenisTexts[$maintenance->jenis_maintenance] ?? $maintenance->jenis_maintenance }}
                                            </td>
                                            <td>{{ Str::limit($maintenance->deskripsi, 50) }}</td>
                                            <td>Rp {{ number_format($maintenance->biaya, 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'badge-warning',
                                                        'in_progress' => 'badge-info',
                                                        'completed' => 'badge-success',
                                                        'cancelled' => 'badge-ghost'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $statusColors[$maintenance->status] ?? 'badge-ghost' }}">
                                                    {{ ucfirst($maintenance->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($product->detailTransaksis && $product->detailTransaksis->count() > 0)
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body">
                        <h2 class="card-title">Riwayat Transaksi</h2>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra table-sm">
                                <thead>
                                    <tr>
                                        <th>No. Invoice</th>
                                        <th>Customer</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Durasi</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->detailTransaksis as $detail)
                                        @if($detail->transaksi)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.transactions.show', $detail->transaksi_id) }}" class="link link-primary">
                                                        {{ $detail->transaksi->invoice_number ?? '-' }}
                                                    </a>
                                                </td>
                                                <td>{{ optional($detail->transaksi)->user?->nama ?? '-' }}</td>
                                                <td>{{ $detail->tanggal_sewa ? \Carbon\Carbon::parse($detail->tanggal_sewa)->format('d/m/Y') : '-' }}</td>
                                                <td>{{ $detail->lama_sewa }} hari</td>
                                                <td>{{ $detail->jumlah }}</td>
                                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'badge-warning',
                                                            'paid' => 'badge-info',
                                                            'confirmed' => 'badge-primary',
                                                            'shipped' => 'badge-ghost',
                                                            'completed' => 'badge-success',
                                                            'cancelled' => 'badge-error'
                                                        ];
                                                    @endphp
                                                    <span class="badge {{ $statusColors[$detail->transaksi->status] ?? 'badge-ghost' }}">
                                                        {{ ucfirst($detail->transaksi->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div>
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="card-title">Ulasan Produk</h2>
                        <span class="badge badge-primary">{{ $product->ulasan ? $product->ulasan->count() : 0 }} ulasan</span>
                    </div>
                    @if($product->ulasan && $product->ulasan->count() > 0)
                        <div class="max-h-96 overflow-y-auto">
                            @foreach($product->ulasan as $review)
                                <div class="border-b border-base-200 pb-3 mb-3">
                                    <div class="flex justify-between mb-2">
                                        <div>
                                            <strong>{{ optional($review->user)->nama ?? 'Anonim' }}</strong>
                                            <div class="text-warning text-sm">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-base-content/60">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($review->judul)
                                        <h6 class="font-semibold mb-2">{{ $review->judul }}</h6>
                                    @endif
                                    <p class="text-sm mb-2">{{ $review->komentar }}</p>
                                    @php
                                        $reviewImages = json_decode($review->foto_ulasan, true) ?? [];
                                    @endphp
                                    @if(!empty($reviewImages))
                                        <div class="grid grid-cols-3 gap-1">
                                            @foreach($reviewImages as $image)
                                                <div>
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                         class="rounded w-full" style="height: 60px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        @php
                                            $statusBadge = [
                                                'approved' => ['class' => 'badge-success', 'text' => 'Disetujui'],
                                                'rejected' => ['class' => 'badge-error', 'text' => 'Ditolak'],
                                                'pending' => ['class' => 'badge-warning', 'text' => 'Menunggu']
                                            ];
                                            $status = $statusBadge[$review->status] ?? ['class' => 'badge-ghost', 'text' => $review->status];
                                        @endphp
                                        <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-base-content/60">Belum ada ulasan</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title">Kelola Stok</h2>
                    <div class="grid grid-cols-2 gap-4 text-center mb-4">
                        <div class="bg-base-200 rounded p-3">
                            <h3 class="text-2xl font-bold">{{ $product->stok_tersedia }}</h3>
                            <small class="text-base-content/60">Tersedia</small>
                        </div>
                        <div class="bg-base-200 rounded p-3">
                            <h3 class="text-2xl font-bold">{{ $product->stok_total }}</h3>
                            <small class="text-base-content/60">Total</small>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <button type="button" class="btn btn-outline" onclick="document.getElementById('stockModal').showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Stok
                        </button>
                        <button type="button" class="btn btn-outline text-warning" onclick="document.getElementById('damageModal').showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            Catat Kerusakan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="stockModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-lg font-bold">Kelola Stok Produk</h3>
        <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST">
            @csrf
            <div class="py-4">
                <div>
                    <label class="label"><span class="label-text">Pilih Aksi</span></label>
                    <select class="select select-bordered w-full" name="type" required>
                        <option value="add">Tambah Stok</option>
                        <option value="subtract">Kurangi Stok</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label class="label"><span class="label-text">Jumlah</span></label>
                    <input type="number" class="input input-bordered w-full" name="quantity" min="1" required>
                </div>
                <div class="mt-4">
                    <label class="label"><span class="label-text">Catatan (Opsional)</span></label>
                    <textarea class="textarea textarea-bordered w-full" name="notes" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('stockModal').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="damageModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-lg font-bold">Catat Stok Rusak</h3>
        <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="damage">
            <div class="py-4">
                <div>
                    <label class="label"><span class="label-text">Jumlah Stok Rusak</span></label>
                    <input type="number" class="input input-bordered w-full" name="quantity" min="1" max="{{ $product->stok_tersedia }}" required>
                    <small class="text-base-content/60 text-xs">Maksimal: {{ $product->stok_tersedia }}</small>
                </div>
                <div class="mt-4">
                    <label class="label"><span class="label-text">Deskripsi Kerusakan</span></label>
                    <textarea class="textarea textarea-bordered w-full" name="notes" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('damageModal').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
