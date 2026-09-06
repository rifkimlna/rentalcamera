@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Detail Pengguna</h1>
        <a href="{{ route('admin.users.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5 text-center">
                    <h2 class="text-lg font-semibold text-[#1d1d1f] justify-center">Informasi Profil</h2>
                    @if($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto profil {{ $user->nama }}" class="rounded-full mx-auto mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-full bg-[#6e6e73] text-[#6e6e73]-content inline-flex items-center justify-center mx-auto mb-3 font-bold text-4xl" style="width: 150px; height: 150px;">{{ substr($user->nama, 0, 1) }}</div>
                    @endif
                    <h4 class="font-bold text-xl">{{ $user->nama }}</h4>
                    <p class="text-[#6e6e73]">{{ $user->email }}</p>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="border border-[#e5e5e7] rounded p-2">
                            <small class="text-[#6e6e73]">Role</small>
                            <div class="font-bold">
                                @php $roleLabels = ['admin' => 'Administrator', 'superadmin' => 'Super Admin', 'customer' => 'Customer']; @endphp
                                {{ $roleLabels[$user->role] ?? $user->role }}
                            </div>
                        </div>
                        <div class="border border-[#e5e5e7] rounded p-2">
                            <small class="text-[#6e6e73]">Status</small>
                            <div class="font-bold">
                                @php
                                    $statusLabels = ['active' => 'Aktif', 'inactive' => 'Nonaktif', 'suspended' => 'Ditangguhkan', 'pending_verification' => 'Menunggu Verifikasi'];
                                    $statusColors = ['active' => 'text-[#34c759]', 'inactive' => 'text-[#6e6e73]', 'suspended' => 'text-[#d70015]', 'pending_verification' => 'text-[#ff9500]'];
                                @endphp
                                <span class="{{ $statusColors[$user->status] ?? 'text-[#6e6e73]' }}">{{ $statusLabels[$user->status] ?? $user->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-[#f0f0f2] p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-[#ff9500] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#e68600] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        @if($user->status == 'active')
                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors w-full" onclick="return confirm('Apakah Anda yakin ingin menangguhkan pengguna ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    Suspend
                                </button>
                            </form>
                        @elseif($user->status == 'suspended')
                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#34c759] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#2db84d] transition-colors w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aktifkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Saldo & Poin</h2>
                    <div class="mb-3">
                        <small class="text-[#6e6e73]">Poin Reward</small>
                        <h4 class="text-xl font-bold text-[#0071e3]">{{ $user->poin_reward }} Poin</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Kontak</h2>
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

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-[#1d1d1f]">Transaksi Terakhir</h2>
                        <a href="{{ route('admin.transactions.index', ['user_id' => $user->id]) }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Lihat Semua</a>
                    </div>
                    @if($user->transaksis && $user->transaksis->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
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
                                            $sc = ['draft' => 'badge-apple', 'menunggu_pembayaran' => 'badge-warning', 'diproses' => 'badge-brand', 'dikonfirmasi' => 'badge-brand', 'siap_diambil' => 'badge-success', 'selesai' => 'badge-success', 'dibatalkan' => 'badge-error', 'ditolak' => 'badge-error'];
                                        @endphp
                                        <span class="badge {{ $sc[$transaction->status_transaksi] ?? 'badge-apple' }}">{{ str_replace('_', ' ', $transaction->status_transaksi) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-[#6e6e73]">Belum ada transaksi</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-[#f0f0f2] border-l-4 border-l-[#0071e3]">
                    <div class="p-5">
                        <div class="text-xs font-bold text-[#0071e3] uppercase mb-1">Total Transaksi</div>
                        <div class="text-lg font-bold text-[#1d1d1f]">{{ optional($user->transaksis)->count() ?? 0 }}</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-[#f0f0f2] border-l-4 border-l-[#34c759]">
                    <div class="p-5">
                        <div class="text-xs font-bold text-[#34c759] uppercase mb-1">Total Pengeluaran</div>
                        <div class="text-lg font-bold text-[#1d1d1f]">Rp {{ number_format(optional($user->transaksis)->sum('grand_total') ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-[#f0f0f2] border-l-4 border-l-[#0071e3]">
                    <div class="p-5">
                        <div class="text-xs font-bold text-[#0071e3] uppercase mb-1">Ulasan Diberikan</div>
                        <div class="text-lg font-bold text-[#1d1d1f]">{{ optional($user->ulasans)->count() ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

