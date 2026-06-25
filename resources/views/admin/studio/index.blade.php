@extends('layouts.admin')

@section('title', 'Kelola Studio')
@section('page-title', 'Kelola Studio')

@section('content')
<div class="card bg-base-100 shadow-md">
    <div class="card-body">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" class="input input-bordered input-sm" placeholder="Cari studio..." value="{{ request('search') }}">
                    <select name="status" class="select select-bordered select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-sm">Cari</button>
                </form>
            </div>
            <a href="{{ route('admin.studio.create') }}" class="btn btn-primary btn-sm">Tambah Studio</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra text-sm">
                <thead>
                    <tr>
                        <th>Studio</th>
                        <th>Harga/Jam</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studios as $studio)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                @if($studio->gambar_utama)
                                    <img src="{{ asset('storage/' . $studio->gambar_utama) }}" class="w-10 h-10 object-cover rounded" alt="{{ $studio->nama_studio }}">
                                @else
                                    <div class="w-10 h-10 bg-base-200 rounded flex items-center justify-center text-xs">No img</div>
                                @endif
                                <div>
                                    <span class="font-medium">{{ $studio->nama_studio }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $studio->harga_per_jam_formatted }}</td>
                        <td>{{ $studio->pakets_count }}</td>
                        <td>
                            <span class="badge {{ $studio->status == 'active' ? 'badge-success' : 'badge-ghost' }}">{{ $studio->status }}</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.studio.show', $studio->id) }}" class="btn btn-ghost btn-xs">Detail</a>
                                <a href="{{ route('admin.studio.edit', $studio->id) }}" class="btn btn-ghost btn-xs">Edit</a>
                                <a href="{{ route('admin.studio.paket.index', $studio->id) }}" class="btn btn-ghost btn-xs">Paket</a>
                                <form method="POST" action="{{ route('admin.studio.destroy', $studio->id) }}" onsubmit="return confirm('Hapus studio ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs text-error">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-base-content/60 py-4">Belum ada studio</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $studios->links() }}</div>
    </div>
</div>
@endsection
