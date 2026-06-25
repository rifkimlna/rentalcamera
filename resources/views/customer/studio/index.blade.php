@extends('layouts.customer')

@section('title', 'Sewa Studio')
@section('page-title', 'Sewa Studio')

@section('content')
<div class="mb-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" class="input input-bordered input-sm w-full max-w-xs" placeholder="Cari studio..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-sm">Cari</button>
    </form>
</div>

@forelse($studios as $studio)
<div class="card bg-base-100 border border-base-300 mb-4">
    <div class="card-body p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-48 h-32 flex-shrink-0">
                @if($studio->gambar_utama)
                    <img src="{{ asset('storage/' . $studio->gambar_utama) }}" class="w-full h-full object-cover rounded" alt="{{ $studio->nama_studio }}">
                @else
                    <div class="w-full h-full bg-base-200 rounded flex items-center justify-center text-base-content/40 text-sm">No Image</div>
                @endif
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium">{{ $studio->nama_studio }}</h4>
                <p class="text-xs text-base-content/60 mt-1">{{ Str::limit($studio->deskripsi, 150) }}</p>
                @if($studio->fasilitas)
                    @php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : array_map('trim', explode(',', $studio->fasilitas)); @endphp
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach(array_slice($fasilitasList, 0, 4) as $f)
                            <span class="badge badge-sm badge-outline">{{ $f }}</span>
                        @endforeach
                        @if(count($fasilitasList) > 4)
                            <span class="badge badge-sm">+{{ count($fasilitasList) - 4 }}</span>
                        @endif
                    </div>
                @endif
                <div class="flex items-center justify-between mt-3">
                    <div>
                        <span class="text-sm font-bold">{{ $studio->harga_per_jam_formatted }}</span>
                        <span class="text-xs text-base-content/60">/ jam</span>
                        @if($studio->paket_active_count > 0)
                            <span class="text-xs text-base-content/40 ml-2">{{ $studio->paket_active_count }} paket tersedia</span>
                        @endif
                    </div>
                    <a href="{{ route('customer.studio.show', $studio->slug) }}" class="btn btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card bg-base-100 border border-base-300">
    <div class="card-body text-center py-8">
        <p class="text-base-content/60">Belum ada studio tersedia</p>
    </div>
</div>
@endforelse

<div class="mt-4">{{ $studios->links() }}</div>
@endsection
