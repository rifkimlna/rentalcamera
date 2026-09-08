@extends('layouts.admin')

@section('title', 'Kelola Layanan')
@section('page-title', 'Kelola Layanan')
@section('page-subtitle', ($layanans->total() ?? 0) . ' layanan • Paket & harga mulai')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Layanan</h1>
            <p class="text-xs text-[#86868b] mt-1">Paket layanan & harga — auto layout card di HP</p>
        </div>
        <a href="{{ route('admin.layanan.create') }}" class="inline-flex items-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition shrink-0"><x-admin.icon name="plus" :size="14" /> Tambah Layanan</a>
    </div>

    <x-admin.card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Cari</label>
                <div class="flex items-center gap-2 bg-[#f5f5f7] border border-transparent rounded-full px-3 py-2 focus-within:bg-white focus-within:border-[#1d1d1f] transition">
                    <x-admin.icon name="search" :size="14" color="text-[#86868b]" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="bg-transparent border-0 p-0 text-xs w-full focus:ring-0 outline-none placeholder:text-[#86868b]">
                </div>
            </div>
            <div class="min-w-[150px]">
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status</label>
                <select name="status" class="select-apple-sm !rounded-full" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2.5 hover:bg-black transition">Cari</button>
        </form>
    </x-admin.card>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">Layanan</th>
                        <th class="px-5 py-3 font-medium">Harga Mulai</th>
                        <th class="px-5 py-3 font-medium text-center">Paket</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($layanans as $layanan)
                    <tr class="hover:bg-[#f5f5f7]/50 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center overflow-hidden shrink-0">
                                    @if($layanan->gambar_utama)
                                        <img src="{{ asset('storage/' . $layanan->gambar_utama) }}" class="w-full h-full object-cover" alt="{{ $layanan->nama_layanan }}">
                                    @else
                                        <x-admin.icon name="layanan" :size="16" color="text-[#86868b]" />
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-[#1d1d1f]">{{ $layanan->nama_layanan }}</div>
                                    @if($layanan->kategori)<div class="text-xs text-[#86868b]">{{ $layanan->kategori }}</div>@endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs font-semibold text-[#1d1d1f]">{{ $layanan->harga_mulai_formatted }}</td>
                        <td class="px-5 py-3 text-center"><span class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">{{ $layanan->pakets_count }}</span></td>
                        <td class="px-5 py-3">
                            @if($layanan->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1 flex-wrap">
                                <a href="{{ route('admin.layanan.show', $layanan->id) }}" class="inline-flex items-center gap-1 bg-white border border-[#e5e5e7] rounded-full px-2.5 py-1 text-xs font-medium text-[#6e6e73] hover:bg-[#f5f5f7] transition"><x-admin.icon name="eye" :size="12" /> Detail</a>
                                <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 rounded-full px-2.5 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100 transition"><x-admin.icon name="edit" :size="12" /> Edit</a>
                                <a href="{{ route('admin.layanan.paket.index', $layanan->id) }}" class="inline-flex items-center gap-1 bg-violet-50 border border-violet-100 rounded-full px-2.5 py-1 text-xs font-medium text-violet-700 hover:bg-violet-100 transition">Paket</a>
                                <form method="POST" action="{{ route('admin.layanan.destroy', $layanan->id) }}" onsubmit="return confirm('Hapus layanan ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-red-50 border border-red-100 rounded-full px-2.5 py-1 text-xs font-medium text-[#d70015] hover:bg-red-100 transition"><x-admin.icon name="trash" :size="12" /> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada layanan" icon="layanan" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($layanans as $layanan)
                <div class="p-4 flex gap-3">
                    <div class="w-12 h-12 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center overflow-hidden shrink-0">
                        @if($layanan->gambar_utama)<img src="{{ asset('storage/' . $layanan->gambar_utama) }}" class="w-full h-full object-cover">@else<x-admin.icon name="layanan" :size="16" color="text-[#86868b]" />@endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $layanan->nama_layanan }}</div>
                        <div class="text-xs font-semibold text-[#1d1d1f] mt-0.5">{{ $layanan->harga_mulai_formatted }} • <span class="text-[#86868b] font-normal">{{ $layanan->pakets_count }} paket</span></div>
                        <div class="mt-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $layanan->status=='active'?'bg-emerald-50 text-emerald-700 border-emerald-100':'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]' }}">{{ $layanan->status }}</div>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada layanan" icon="layanan" /></div>
            @endforelse
        </div>
        <div class="px-5 py-4 border-t border-[#f0f0f2]">{{ $layanans->links() }}</div>
    </x-admin.card>
</div>
@endsection
