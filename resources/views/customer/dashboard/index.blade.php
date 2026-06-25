@extends('layouts.customer')

@section('title', 'Dashboard Customer - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <!-- Welcome section -->
    <div class="mb-4">
        <div class="card bg-primary text-primary-content">
            <div class="card-body">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h2 class="card-title text-2xl">Selamat datang, {{ Auth::user()->nama }}! 👋</h2>
                        <p class="mb-0">Sewa kamera dan peralatan fotografi terbaik dengan harga terjangkau.</p>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <div class="text-center">
                            <h3 class="mb-0 text-xl">{{ $user->poin_reward }}</h3>
                            <small>Poin Reward</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-base-content/60 mb-1">Total Transaksi</h5>
                        <h2 class="mb-0 text-2xl">{{ $stats['total_orders'] }}</h2>
                    </div>
                    <div class="text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3">
                    <a href="{{ route('customer.transactions.index') }}" class="text-primary">Lihat semua →</a>
                </p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-base-content/60 mb-1">Selesai</h5>
                        <h2 class="mb-0 text-2xl">{{ $stats['completed_orders'] }}</h2>
                    </div>
                    <div class="text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3">
                    Transaksi berhasil
                </p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-base-content/60 mb-1">Dalam Proses</h5>
                        <h2 class="mb-0 text-2xl">{{ $stats['pending_orders'] }}</h2>
                    </div>
                    <div class="text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3">
                    Menunggu penyelesaian
                </p>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-base-content/60 mb-1">Total Pengeluaran</h5>
                        <h2 class="mb-0 text-2xl">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h2>
                    </div>
                    <div class="text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3">
                    Sejak bergabung
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="card-title">Transaksi Terbaru</h5>
                        <a href="{{ route('customer.transactions.index') }}" class="btn btn-outline btn-primary btn-sm">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <strong>{{ $transaction->kode_transaksi }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                                <div class="text-base-content/60">{{ $transaction->created_at->format('H:i') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @php
                                                    $products = optional($transaction->detailTransaksis)->take(2) ?? collect();
                                                @endphp
                                                @foreach($products as $detail)
                                                    <div>{{ $detail->nama_produk }}</div>
                                                @endforeach
                                                @if(optional($transaction->detailTransaksis)->count() > 2)
                                                    <div class="text-base-content/60">+{{ optional($transaction->detailTransaksis)->count() - 2 }} lainnya</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-primary">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'menunggu_pembayaran' => 'warning',
                                                    'diproses' => 'info',
                                                    'dikonfirmasi' => 'primary',
                                                    'dikemas' => 'primary',
                                                    'dikirim' => 'success',
                                                    'dalam_perjalanan' => 'success',
                                                    'selesai' => 'success',
                                                    'dibatalkan' => 'error',
                                                    'ditolak' => 'error'
                                                ];
                                                $statusTexts = [
                                                    'menunggu_pembayaran' => 'Menunggu Bayar',
                                                    'diproses' => 'Diproses',
                                                    'dikonfirmasi' => 'Dikonfirmasi',
                                                    'dikemas' => 'Dikemas',
                                                    'dikirim' => 'Dikirim',
                                                    'dalam_perjalanan' => 'Dalam Perjalanan',
                                                    'selesai' => 'Selesai',
                                                    'dibatalkan' => 'Dibatalkan',
                                                    'ditolak' => 'Ditolak'
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $statusColors[$transaction->status_transaksi] ?? 'ghost' }}">
                                                {{ $statusTexts[$transaction->status_transaksi] ?? $transaction->status_transaksi }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.transactions.show', $transaction->id) }}" 
                                               class="btn btn-outline btn-primary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-base-content/60">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p>Belum ada transaksi</p>
                                                <a href="{{ route('customer.products.index') }}" class="btn btn-primary mt-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Sewa Sekarang
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recommended Products -->
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h5 class="card-title mb-4">Produk Rekomendasi</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Product 1 -->
                        <div class="card bg-base-100 shadow-md">
                            <figure>
                                <img src="https://via.placeholder.com/300x200/4361ee/ffffff?text=Canon+5D" 
                                     class="w-full h-48 object-cover" alt="Canon EOS 5D">
                            </figure>
                            <div class="card-body">
                                <h6 class="card-title">Canon EOS 5D Mark IV</h6>
                                <p class="text-primary mb-2">Rp 250.000/hari</p>
                                <div class="rating rating-sm mb-3">
                                    <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-1" class="mask mask-star-2 bg-orange-400" />
                                    <span class="ms-1">(4.5)</span>
                                </div>
                                <a href="#" class="btn btn-outline btn-primary btn-sm w-full">Sewa Sekarang</a>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="card bg-base-100 shadow-md">
                            <figure>
                                <img src="https://via.placeholder.com/300x200/3f37c9/ffffff?text=Sony+A7" 
                                     class="w-full h-48 object-cover" alt="Sony A7 III">
                            </figure>
                            <div class="card-body">
                                <h6 class="card-title">Sony A7 III</h6>
                                <p class="text-primary mb-2">Rp 200.000/hari</p>
                                <div class="rating rating-sm mb-3">
                                    <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" />
                                    <span class="ms-1">(4.0)</span>
                                </div>
                                <a href="#" class="btn btn-outline btn-primary btn-sm w-full">Sewa Sekarang</a>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="card bg-base-100 shadow-md">
                            <figure>
                                <img src="https://via.placeholder.com/300x200/4cc9f0/ffffff?text=DJI+Mavic" 
                                     class="w-full h-48 object-cover" alt="DJI Mavic">
                            </figure>
                            <div class="card-body">
                                <h6 class="card-title">DJI Mavic 3 Pro</h6>
                                <p class="text-primary mb-2">Rp 350.000/hari</p>
                                <div class="rating rating-sm mb-3">
                                    <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                    <input type="radio" name="rating-3" class="mask mask-star-2 bg-orange-400" checked />
                                    <span class="ms-1">(5.0)</span>
                                </div>
                                <a href="#" class="btn btn-outline btn-primary btn-sm w-full">Sewa Sekarang</a>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>


            <!-- Account Verification -->
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Verifikasi Akun</h5>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span>Verifikasi Email</span>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">Terverifikasi</span>
                            @else
                                <span class="badge badge-warning">Belum Verifikasi</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span>Verifikasi KTP</span>
                            @if($user->ktp_verified_at)
                                <span class="badge badge-success">Terverifikasi</span>
                            @elseif($user->ktp_image)
                                <span class="badge badge-warning">Menunggu</span>
                            @else
                                <span class="badge badge-error">Belum Upload</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span>Verifikasi Telepon</span>
                            @if($user->telepon_verified_at)
                                <span class="badge badge-success">Terverifikasi</span>
                            @else
                                <span class="badge badge-warning">Belum Verifikasi</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 mt-4">
                        @if(!$user->ktp_image)
                            <button type="button" class="btn btn-outline btn-primary" onclick="ktpModal.showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Upload KTP
                            </button>
                        @endif

                        @if(!$user->email_verified_at)
                            <button type="button" class="btn btn-outline btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Verifikasi Email
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h5 class="card-title mb-4">Aksi Cepat</h5>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('customer.products.index') }}" class="btn btn-outline btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari Kamera
                        </a>
                        <a href="{{ route('customer.cart.index') }}" class="btn btn-outline btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            Keranjang Saya
                        </a>
                        <a href="{{ route('customer.transactions.index') }}" class="btn btn-outline btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Transaksi Saya
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline btn-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- KTP Upload Modal -->
<dialog id="ktpModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Upload Foto KTP</h3>
        <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="alert alert-info mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Upload foto KTP untuk verifikasi akun. Proses verifikasi membutuhkan 1-2 hari kerja.
            </div>

            <div class="mb-3">
                <label class="label">
                    <span class="label-text">Foto KTP</span>
                </label>
                <input type="file" class="file-input file-input-bordered w-full" name="ktp_image" accept="image/*" required>
                <small class="text-base-content/60">Format: JPG, PNG, PDF | Maks: 2MB</small>
            </div>

            <div class="mb-3">
                <label class="label">
                    <span class="label-text">Nomor KTP</span>
                </label>
                <input type="text" class="input input-bordered w-full" name="ktp_number" placeholder="Masukkan nomor KTP" required>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="ktpModal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Upload KTP</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
