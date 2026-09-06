@extends('layouts.admin')

@section('title', 'Portfolio')
@section('page-title', 'Portfolio')

@section('content')
<x-flash-messages />
<div class="flex justify-between items-center mb-4">
    <div>
        <p class="text-sm text-[#6e6e73]">Kelola foto dan video portfolio</p>
    </div>
    <a href="{{ route('admin.portfolios.create') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">+ Tambah</a>
</div>

<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs uppercase text-[#86868b]">
                        <th class="w-10">#</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Platform</th>
                        <th class="text-center">Urutan</th>
                        <th class="text-center">Status</th>
                        <th class="w-[160px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($portfolios as $item)
                    <tr class="hover:bg-[#f5f5f7]/50">
                        <td class="text-[#86868b]">{{ $loop->iteration }}</td>
                        <td>
                            @if($item->tipe === 'foto' && $item->gambar_url)
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="w-16 h-12 object-cover rounded">
                            @elseif($item->tipe === 'video')
                                <div class="w-16 h-12 bg-[#f5f5f7] rounded flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#86868b]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-16 h-12 bg-[#f5f5f7] rounded"></div>
                            @endif
                        </td>
                        <td>
                            <div class="font-medium text-sm">{{ $item->judul }}</div>
                            @if($item->deskripsi)
                                <div class="text-xs text-[#86868b] truncate max-w-[200px]">{{ $item->deskripsi }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 {{ $item->tipe === 'foto' ? 'badge-brand' : 'badge-apple' }}">
                                {{ $item->tipe_label }}
                            </span>
                        </td>
                        <td>
                            @if($item->platform)
                                <span class="badge-apple !text-[10px] !px-2 !py-0.5">{{ $item->platform_label }}</span>
                            @else
                                <span class="text-[#86868b] text-xs">-</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->sort_order }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.portfolios.toggle-active', $item->id) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors {{ $item->is_active ? 'btn-success' : 'btn-ghost' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.portfolios.edit', $item->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.portfolios.destroy', $item->id) }}"
                                      onsubmit="return confirm('Hapus portfolio ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-[#86868b] py-8">Belum ada portfolio.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($portfolios->hasPages())
        <div class="p-4 border-t border-[#f0f0f2]">{{ $portfolios->links() }}</div>
        @endif
    </div>
</div>
@endsection
