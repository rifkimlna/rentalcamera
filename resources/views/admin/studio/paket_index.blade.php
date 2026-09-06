@extends('layouts.admin')

@section('title', 'Paket Studio - ' . $studio->nama_studio)
@section('page-title', 'Paket: ' . $studio->nama_studio)

@section('content')
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('admin.studio.show', $studio->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Kembali ke Detail</a>
            <a href="{{ route('admin.studio.paket.create', $studio->id) }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Paket</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pakets as $paket)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                @if($paket->gambar)
                                    <img src="{{ asset('storage/' . $paket->gambar) }}" alt="Paket {{ $paket->nama_paket }}" class="w-10 h-10 object-cover rounded">
                                @endif
                                <span class="font-medium">{{ $paket->nama_paket }}</span>
                            </div>
                        </td>
                        <td>{{ $paket->durasi_jam }} jam</td>
                        <td>{{ $paket->harga_formatted }}</td>
                        <td>
                            <span class="badge {{ $paket->status == 'active' ? 'badge-success' : 'badge-apple' }}">{{ $paket->status }}</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.studio.paket.edit', [$studio->id, $paket->id]) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.studio.paket.destroy', [$studio->id, $paket->id]) }}" onsubmit="return confirm('Hapus paket?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada paket</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pakets->links() }}</div>
    </div>
</div>
@endsection

