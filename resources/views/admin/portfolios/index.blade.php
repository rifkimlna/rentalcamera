@extends('layouts.admin')

@section('title', 'Portfolio')
@section('page-title', 'Portfolio')
@section('page-subtitle', 'Kelola foto & video showcase')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Portfolio</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola foto dan video portfolio — grid & tabel selaras</p>
        </div>
        <a href="{{ route('admin.portfolios.create') }}" class="inline-flex items-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition shrink-0"><x-admin.icon name="plus" :size="14" /> Tambah</a>
    </div>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium w-10">#</th>
                        <th class="px-5 py-3 font-medium">Gambar</th>
                        <th class="px-5 py-3 font-medium">Judul</th>
                        <th class="px-5 py-3 font-medium">Tipe</th>
                        <th class="px-5 py-3 font-medium">Platform</th>
                        <th class="px-5 py-3 font-medium text-center">Urutan</th>
                        <th class="px-5 py-3 font-medium text-center">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($portfolios as $item)
                    <tr class="hover:bg-[#f5f5f7]/50 transition">
                        <td class="px-5 py-3 text-xs text-[#86868b]">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3">
                            @if($item->tipe === 'foto' && $item->gambar_url)
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-16 h-12 object-cover rounded-[12px] border border-[#e5e5e7]">
                            @elseif($item->tipe === 'video')
                                <div class="w-16 h-12 bg-violet-50 border border-violet-100 rounded-[12px] flex items-center justify-center text-violet-600">
                                    <x-admin.icon name="portfolio" :size="20" />
                                </div>
                            @else
                                <div class="w-16 h-12 bg-[#f5f5f7] border border-[#e5e5e7] rounded-[12px]"></div>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-sm font-medium text-[#1d1d1f]">{{ $item->judul }}</div>
                            @if($item->deskripsi)<div class="text-xs text-[#86868b] truncate max-w-[200px]">{{ $item->deskripsi }}</div>@endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $item->tipe === 'foto' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-violet-50 text-violet-700 border-violet-100' }}">{{ $item->tipe_label }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @if($item->platform)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">{{ $item->platform_label }}</span>
                            @else
                                <span class="text-[#86868b] text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center text-xs font-medium text-[#1d1d1f]">{{ $item->sort_order }}</td>
                        <td class="px-5 py-3 text-center">
                            <form method="POST" action="{{ route('admin.portfolios.toggle-active', $item->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border transition {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-[#f5f5f7] border-[#e5e5e7] text-[#6e6e73]' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.portfolios.edit', $item->id) }}" class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 rounded-full px-2.5 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100 transition"><x-admin.icon name="edit" :size="12" /> Edit</a>
                                <form method="POST" action="{{ route('admin.portfolios.destroy', $item->id) }}" onsubmit="return confirm('Hapus portfolio ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-red-50 border border-red-100 rounded-full px-2.5 py-1 text-xs font-medium text-[#d70015] hover:bg-red-100 transition"><x-admin.icon name="trash" :size="12" /> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada portfolio" icon="portfolio" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($portfolios as $item)
                <div class="p-4 flex gap-3">
                    <div class="w-16 h-12 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] overflow-hidden shrink-0 flex items-center justify-center">
                        @if($item->tipe === 'foto' && $item->gambar_url)<img src="{{ $item->gambar_url }}" class="w-full h-full object-cover">@else<x-admin.icon name="portfolio" :size="20" color="text-[#86868b]" />@endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $item->judul }}</div>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="text-[11px] px-2 py-0.5 rounded-full border {{ $item->tipe==='foto'?'bg-blue-50 text-blue-700 border-blue-100':'bg-violet-50 text-violet-700 border-violet-100' }}">{{ $item->tipe_label }}</span>
                            <span class="text-xs text-[#86868b]">#{{ $item->sort_order }}</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full border {{ $item->is_active?'bg-emerald-50 text-emerald-700 border-emerald-100':'bg-[#f5f5f7] text-[#6e6e73] border-[#e5e5e7]' }}">{{ $item->is_active?'Aktif':'Nonaktif' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada portfolio" icon="portfolio" /></div>
            @endforelse
        </div>

        @if($portfolios->hasPages())
        <div class="px-5 py-4 border-t border-[#f0f0f2]">{{ $portfolios->links() }}</div>
        @endif
    </x-admin.card>
</div>
@endsection
