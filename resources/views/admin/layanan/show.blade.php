@extends('layouts.admin')

@section('title', $layanan->nama_layanan)
@section('page-title', $layanan->nama_layanan)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                @if($layanan->gambar_utama)
                    <img src="{{ asset('storage/' . $layanan->gambar_utama) }}" alt="{{ $layanan->nama_layanan }}" class="w-full rounded mb-3">
                @endif
                <h5 class="font-medium">{{ $layanan->nama_layanan }}</h5>
                @if($layanan->kategori)
                    <p class="text-sm text-[#6e6e73]">Kategori: {{ $layanan->kategori }}</p>
                @endif
                <p class="text-sm text-[#6e6e73]">{{ $layanan->harga_mulai_formatted }} / mulai</p>
                <span class="badge {{ $layanan->status == 'active' ? 'badge-success' : 'badge-apple' }}">{{ $layanan->status }}</span>
                <p class="text-sm mt-2">Total Booking: {{ $layanan->bookings_count }}</p>
            </div>
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <h5 class="font-medium mb-2">Deskripsi</h5>
                <p class="text-sm">{{ $layanan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] mt-4">
            <div class="p-5">
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-medium">Paket Layanan</h5>
                    <a href="{{ route('admin.layanan.paket.create', $layanan->id) }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Paket</a>
                </div>
                @forelse($layanan->paketActive as $paket)
                    <div class="border border-[#e5e5e7] rounded p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h6 class="font-medium text-sm">{{ $paket->nama_paket }}</h6>
                                <p class="text-xs text-[#6e6e73]">{{ $paket->durasi_jam }} jam - {{ $paket->harga_formatted }}</p>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.layanan.paket.edit', [$layanan->id, $paket->id]) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.layanan.paket.destroy', [$layanan->id, $paket->id]) }}" onsubmit="return confirm('Hapus paket?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#6e6e73]">Belum ada paket.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

