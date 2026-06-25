@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Manajemen Pengguna</h1>
            <p class="text-sm text-base-content/60">Kelola data customer, admin, dan driver</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="bg-primary/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold">{{ App\Models\User::count() }}</h5>
                        <small class="text-base-content/60">Total Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="bg-success/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold">{{ App\Models\User::where('role', 'customer')->where('status', 'active')->count() }}</h5>
                        <small class="text-base-content/60">Customer Aktif</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="bg-info/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold">Rp {{ number_format(App\Models\User::sum('saldo_deposit'), 0, ',', '.') }}</h5>
                        <small class="text-base-content/60">Total Deposit</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <div class="flex items-center gap-3">
                    <div class="bg-warning/10 p-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold">{{ App\Models\User::where('status', 'pending_verification')->count() }}</h5>
                        <small class="text-base-content/60">Pending Verifikasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="label"><span class="label-text">Role</span></label>
                    <select name="role" class="select select-bordered w-full">
                        <option value="">Semua Role</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text">Status</span></label>
                    <select name="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text">Pencarian</span></label>
                    <div class="join w-full">
                        <input type="text" name="search" class="input input-bordered join-item flex-1" value="{{ request('search') }}" placeholder="Nama, email, atau telepon...">
                        <button type="submit" class="btn btn-primary join-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Cari
                        </button>
                    </div>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengguna</th>
                        <th>Kontak</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Saldo</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-base-content/60 text-sm">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="bg-base-200 rounded-full flex items-center justify-center w-8 h-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <div class="font-medium">{{ $user->nama }}</div>
                                    @if($user->ktp_verified_at)
                                        <span class="badge badge-success badge-sm">KTP Terverifikasi</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                <div class="truncate max-w-[200px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $user->email }}
                                </div>
                                <div class="text-base-content/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $user->telepon ?? '-' }}
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $roleConfig = ['superadmin' => 'badge-error', 'admin' => 'badge-primary', 'customer' => 'badge-info', 'driver' => 'badge-ghost'];
                            @endphp
                            <span class="badge {{ $roleConfig[$user->role] ?? 'badge-ghost' }}">
                                {{ $roles[$user->role] ?? $user->role }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusConfig = ['active' => 'badge-success', 'inactive' => 'badge-ghost', 'suspended' => 'badge-error', 'pending_verification' => 'badge-warning'];
                            @endphp
                            <span class="badge {{ $statusConfig[$user->status] ?? 'badge-ghost' }}">
                                {{ $statuses[$user->status] ?? $user->status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="font-medium">Rp {{ number_format($user->saldo_deposit, 0, ',', '.') }}</div>
                            @if($user->poin_reward > 0)
                                <small class="text-base-content/60">{{ $user->poin_reward }} poin</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-ghost" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-ghost text-warning" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($user->role !== 'superadmin')
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-ghost text-error" title="Hapus" onclick="return confirm('Hapus pengguna ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-base-content/60">Tidak ada data pengguna</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="border-t border-base-200 p-4">
            <div class="flex justify-between items-center">
                <div class="text-sm text-base-content/60">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }}
                </div>
                <div>{{ $users->links('vendor.pagination.simple-bootstrap') }}</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(el) {
            el.style.transition = 'opacity 0.3s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 300);
        });
    }, 4000);
});
</script>
@endpush
