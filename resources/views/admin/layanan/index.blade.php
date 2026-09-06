@extends('layouts.admin')

@section('title', 'Kelola Layanan')
@section('page-title', 'Kelola Layanan')

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" class="input-apple input-sm" placeholder="Cari layanan..." value="{{ request('search') }}">
                    <select name="status" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                </form>
            </div>
            <a href="{{ route('admin.layanan.create') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Layanan</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Harga Mulai</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($layanans as $layanan)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                @if($layanan->gambar_utama)
                                    <img src="{{ asset('storage/' . $layanan->gambar_utama) }}" class="w-10 h-10 object-cover rounded" alt="{{ $layanan->nama_layanan }}">
                                @else
                                    <div class="w-10 h-10 bg-[#f5f5f7] rounded flex items-center justify-center text-xs">No img</div>
                                @endif
                                <div>
                                    <span class="font-medium">{{ $layanan->nama_layanan }}</span>
                                    @if($layanan->kategori)
                                        <span class="text-xs text-[#86868b] block">{{ $layanan->kategori }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $layanan->harga_mulai_formatted }}</td>
                        <td>{{ $layanan->pakets_count }}</td>
                        <td>
                            <span class="badge {{ $layanan->status == 'active' ? 'badge-success' : 'badge-apple' }}">{{ $layanan->status }}</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.layanan.show', $layanan->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <a href="{{ route('admin.layanan.paket.index', $layanan->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Paket</a>
                                <form method="POST" action="{{ route('admin.layanan.destroy', $layanan->id) }}" onsubmit="return confirm('Hapus layanan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada layanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $layanans->links() }}</div>
    </div>
</div>
@endsection
