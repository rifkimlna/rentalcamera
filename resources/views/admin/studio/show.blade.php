@extends('layouts.admin')

@section('title', $studio->nama_studio)
@section('page-title', $studio->nama_studio)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                @if($studio->gambar_utama)
                    <img src="{{ asset('storage/' . $studio->gambar_utama) }}" class="w-full rounded mb-3">
                @endif
                <h5 class="font-medium">{{ $studio->nama_studio }}</h5>
                <p class="text-sm text-base-content/60">{{ $studio->harga_per_jam_formatted }} / jam</p>
                <span class="badge {{ $studio->status == 'active' ? 'badge-success' : 'badge-ghost' }}">{{ $studio->status }}</span>
                <p class="text-sm mt-2">Total Booking: {{ $studio->bookings_count }}</p>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h5 class="font-medium mb-2">Deskripsi</h5>
                <p class="text-sm">{{ $studio->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                <h5 class="font-medium mt-4 mb-2">Fasilitas</h5>
                @if($studio->fasilitas)
                    @php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : explode(',', $studio->fasilitas); @endphp
                    <div class="flex flex-wrap gap-1">
                        @foreach($fasilitasList as $f)
                            <span class="badge badge-outline">{{ trim($f) }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-base-content/60">Tidak ada fasilitas.</p>
                @endif
            </div>
        </div>

        <div class="card bg-base-100 shadow-md mt-4">
            <div class="card-body">
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-medium">Paket Studio</h5>
                    <a href="{{ route('admin.studio.paket.create', $studio->id) }}" class="btn btn-primary btn-sm">Tambah Paket</a>
                </div>
                @forelse($studio->paketActive as $paket)
                    <div class="border border-base-300 rounded p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="font-medium text-sm">{{ $paket->nama_paket }}</h6>
                                <p class="text-xs text-base-content/60">{{ $paket->durasi_jam }} jam - {{ $paket->harga_formatted }}</p>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.studio.paket.edit', [$studio->id, $paket->id]) }}" class="btn btn-ghost btn-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.studio.paket.destroy', [$studio->id, $paket->id]) }}" onsubmit="return confirm('Hapus paket?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs text-error">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-base-content/60">Belum ada paket.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
