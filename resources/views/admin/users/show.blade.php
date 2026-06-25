@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Detail Pengguna</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body text-center">
                    <h2 class="card-title justify-center">Informasi Profil</h2>
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" class="rounded-full mx-auto mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-full bg-secondary text-secondary-content inline-flex items-center justify-center mx-auto mb-3 font-bold text-4xl" style="width: 150px; height: 150px;">{{ substr($user->nama, 0, 1) }}</div>
                    @endif
                    <h4 class="font-bold text-xl">{{ $user->nama }}</h4>
                    <p class="text-base-content/60">{{ $user->email }}</p>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="border border-base-300 rounded p-2">
                            <small class="text-base-content/60">Role</small>
                            <div class="font-bold">
                                @php $roleLabels = ['admin' => 'Administrator', 'superadmin' => 'Super Admin', 'customer' => 'Customer', 'driver' => 'Driver']; @endphp
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </div>
                        </div>
                        <div class="border border-base-300 rounded p-2">
                            <small class="text-base-content/60">Status</small>
                            <div class="font-bold">
                                @php
                                    $statusLabels = ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'suspended' => 'Ditangguhkan', 'pending_verification' => 'Menunggu Verifikasi'];
                                    $statusColors = ['active' => 'text-success', 'inactive' => 'text-base-content/60', 'suspended' => 'text-error', 'pending_verification' => 'text-warning'];
                                @endphp
                                <span class="{{ $statusColors[$user->status] ?? 'text-base-content/60' }}">{{ $statusLabels[$user->status] ?? $user->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-base-200 p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        @if($user->status == 'active')
                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-error w-full" onclick="return confirm('Apakah Anda yakin ingin menangguhkan pengguna ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    Suspend
                                </button>
                            </form>
                        @elseif($user->status == 'suspended')
                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aktifkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h2 class="card-title">Saldo & Poin</h2>
                    <div class="mb-3">
                        <small class="text-base-content/60">Saldo Deposit</small>
                        <h3 class="text-2xl font-bold text-success">Rp {{ number_format($user->saldo_deposit, 0, ',', '.') }}</h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-base-content/60">Poin Reward</small>
                        <h4 class="text-xl font-bold text-info">{{ $user->poin_reward }} Poin</h4>
                    </div>
                    <form action="{{ route('admin.users.updateDeposit', $user->id) }}" method="POST">
                        @csrf
                        <div>
                            <label for="amount" class="label"><span class="label-text">Update Saldo</span></label>
                            <input type="number" name="amount" id="amount" class="input input-bordered w-full" placeholder="Jumlah" min="0" step="1000" required>
                        </div>
                        <div class="flex gap-4 mt-2">
                            <label class="label cursor-pointer gap-2">
                                <input type="radio" name="type" value="add" class="radio radio-primary" checked>
                                <span class="label-text">Tambah</span>
                            </label>
                            <label class="label cursor-pointer gap-2">
                                <input type="radio" name="type" value="subtract" class="radio radio-primary">
                                <span class="label-text">Kurangi</span>
                            </label>
                        </div>
                        <div class="mt-4">
                            <label for="notes" class="label"><span class="label-text">Catatan</span></label>
                            <textarea name="notes" id="notes" class="textarea textarea-bordered w-full" rows="2" placeholder="Keterangan penyesuaian saldo"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Update Saldo
                        </button>
                    </form>
                </div>
            </div>

            @if($user->ktp_image)
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="card-title">Verifikasi KTP</h2>
                        @if($user->ktp_verified_at)
                            <span class="badge badge-success">Terverifikasi</span>
                        @else
                            <form action="{{ route('admin.users.verifyKtp', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Verifikasi
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $user->ktp_image) }}" class="rounded mb-2 max-h-48 mx-auto">
                        <p class="text-xs text-base-content/60">
                            @if($user->ktp_verified_at)
                                Diverifikasi pada: {{ \Carbon\Carbon::parse($user->ktp_verified_at)->format('d/m/Y H:i') }}
                            @else
                                Menunggu verifikasi
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h2 class="card-title">Informasi Kontak</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><strong>Telepon:</strong><p>{{ $user->telepon ?? '-' }}</p></div>
                        <div><strong>Jenis Kelamin:</strong><p>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p></div>
                        <div><strong>Tanggal Lahir:</strong><p>{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d/m/Y') : '-' }}</p></div>
                        <div><strong>Terakhir Login:</strong><p>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Belum pernah login' }}</p></div>
                    </div>
                    <div class="mt-4"><strong>Alamat:</strong><p>{{ $user->alamat ?? '-' }}</p></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div><strong>Kota:</strong><p>{{ $user->kota ?? '-' }}</p></div>
                        <div><strong>Provinsi:</strong><p>{{ $user->provinsi ?? '-' }}</p></div>
                        <div><strong>Kode Pos:</strong><p>{{ $user->kode_pos ?? '-' }}</p></div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="card-title">Transaksi Terakhir</h2>
                        <a href="{{ route('admin.transactions.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    @if($user->transaksis && $user->transaksis->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->transaksis as $transaction)
                                <tr>
                                    <td>{{ $transaction->kode_transaksi }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $sc = ['draft' => 'badge-ghost', 'menunggu_pembayaran' => 'badge-warning', 'diproses' => 'badge-info', 'dikonfirmasi' => 'badge-primary', 'dikemas' => 'badge-info', 'dikirim' => 'badge-info', 'dalam_perjalanan' => 'badge-info', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                                        @endphp
                                        <span class="badge {{ $sc[$transaction->status_transaksi] ?? 'badge-ghost' }}">{{ str_replace('_', ' ', $transaction->status_transaksi) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-ghost">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-base-content/60">Belum ada transaksi</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card bg-base-100 shadow-md border-l-4 border-l-primary">
                    <div class="card-body">
                        <div class="text-xs font-bold text-primary uppercase mb-1">Total Transaksi</div>
                        <div class="text-lg font-bold text-base-content">{{ optional($user->transaksis)->count() ?? 0 }}</div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md border-l-4 border-l-success">
                    <div class="card-body">
                        <div class="text-xs font-bold text-success uppercase mb-1">Total Pengeluaran</div>
                        <div class="text-lg font-bold text-base-content">Rp {{ number_format(optional($user->transaksis)->sum('grand_total') ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-md border-l-4 border-l-info">
                    <div class="card-body">
                        <div class="text-xs font-bold text-info uppercase mb-1">Ulasan Diberikan</div>
                        <div class="text-lg font-bold text-base-content">{{ optional($user->ulasan)->count() ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
