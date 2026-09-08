@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Kelola customer & admin • Verifikasi')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Manajemen Pengguna</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola data customer dan admin — auto-layout ke card di HP</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition shrink-0"><x-admin.icon name="plus" :size="14" /> Tambah Pengguna</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <x-admin.kpi label="Total Pengguna" :value="number_format(App\Models\User::count(),0,',','.')" hint="Semua role" icon="users" color="blue" />
        <x-admin.kpi label="Customer Aktif" :value="number_format(App\Models\User::where('role','customer')->where('status','active')->count(),0,',','.')" hint="Terverifikasi" icon="check" color="emerald" />
        <x-admin.kpi label="Pending Verifikasi" :value="number_format(App\Models\User::where('status','pending_verification')->count(),0,',','.')" hint="Perlu tindakan" icon="activity" color="amber" />
    </div>

    <x-admin.card>
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Role</label>
                <select name="role" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua Role</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status</label>
                <select name="status" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Pencarian</label>
                <div class="flex items-center gap-2 bg-[#f5f5f7] border border-transparent rounded-full px-3 py-2 focus-within:bg-white focus-within:border-[#1d1d1f] transition">
                    <x-admin.icon name="search" :size="14" color="text-[#86868b]" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, telepon..." class="bg-transparent border-0 p-0 text-xs w-full focus:ring-0 outline-none placeholder:text-[#86868b]">
                </div>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2.5 hover:bg-black transition">Cari</button>
                <a href="{{ route('admin.users.index') }}" class="bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73] rounded-full px-3 py-2.5 hover:bg-[#e8e8ed] transition"><x-admin.icon name="x" :size="16" /></a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Pengguna</th>
                        <th class="px-5 py-3 font-medium">Kontak</th>
                        <th class="px-5 py-3 font-medium text-center">Role</th>
                        <th class="px-5 py-3 font-medium text-center">Status</th>
                        <th class="px-5 py-3 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($users as $user)
                    <tr class="hover:bg-[#f5f5f7]/50 transition">
                        <td class="px-5 py-3 text-xs text-[#86868b]">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="" class="w-8 h-8 rounded-full object-cover border border-[#e5e5e7]">
                                @else
                                <div class="w-8 h-8 rounded-full bg-violet-50 border border-violet-100 text-violet-700 flex items-center justify-center shrink-0">
                                    <x-admin.icon name="users" :size="14" />
                                </div>
                                @endif
                                <span class="text-sm font-medium text-[#1d1d1f]">{{ $user->nama }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-xs text-[#6e6e73] truncate max-w-[180px]">{{ $user->email }}</div>
                            <div class="text-xs text-[#86868b]">{{ $user->telepon ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php $roleBadge = ['superadmin' => 'bg-red-50 text-[#d70015] border-red-100', 'admin' => 'bg-blue-50 text-blue-700 border-blue-100', 'customer' => 'bg-violet-50 text-violet-700 border-violet-100']; @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $roleBadge[$user->role] ?? 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]' }}">{{ $roles[$user->role] ?? $user->role }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php $stBadge = ['active' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'suspended' => 'bg-red-50 text-[#d70015] border-red-100', 'pending_verification' => 'bg-amber-50 text-amber-700 border-amber-100', 'inactive' => 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]']; @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $stBadge[$user->status] ?? 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]' }}">{{ $statuses[$user->status] ?? $user->status }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="w-8 h-8 rounded-full bg-white border border-[#e5e5e7] flex items-center justify-center text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f] transition"><x-admin.icon name="eye" :size="14" /></a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700 hover:bg-amber-100 transition"><x-admin.icon name="edit" :size="14" /></a>
                                @if($user->role !== 'superadmin')
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-[#d70015] hover:bg-red-100 transition"><x-admin.icon name="trash" :size="14" /></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center"><x-admin.empty title="Tidak ada data pengguna" icon="users" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($users as $user)
                <div class="p-4 flex gap-3">
                    <x-avatar :user="$user" :size="40" />
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $user->nama }}</div>
                        <div class="text-xs text-[#86868b] truncate">{{ $user->email }}</div>
                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                            <span class="text-[11px] px-2 py-0.5 rounded-full border {{ ['superadmin'=>'bg-red-50 text-[#d70015] border-red-100','admin'=>'bg-blue-50 text-blue-700 border-blue-100','customer'=>'bg-violet-50 text-violet-700 border-violet-100'][$user->role] ?? 'bg-[#f5f5f7] border-[#e5e5e7]' }}">{{ $roles[$user->role] }}</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full border {{ ['active'=>'bg-emerald-50 text-emerald-700 border-emerald-100','suspended'=>'bg-red-50 text-[#d70015] border-red-100','pending_verification'=>'bg-amber-50 text-amber-700 border-amber-100'][$user->status] ?? 'bg-[#f5f5f7] border-[#e5e5e7]' }}">{{ $statuses[$user->status] }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="self-center w-8 h-8 rounded-full bg-white border border-[#e5e5e7] flex items-center justify-center"><x-admin.icon name="eye" :size="14" color="text-[#6e6e73]" /></a>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Tidak ada pengguna" icon="users" /></div>
            @endforelse
        </div>

        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-[#f0f0f2] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <span class="text-xs text-[#86868b]">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }}</span>
            <div>{{ $users->links() }}</div>
        </div>
        @endif
    </x-admin.card>
</div>
@endsection
