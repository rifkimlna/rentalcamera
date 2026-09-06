@extends('layouts.admin')

@section('title', 'Equipment')
@section('page-title', 'Equipment')

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <form method="GET" class="flex flex-wrap gap-2">
                    <input type="text" name="search" class="input-apple input-sm" placeholder="Cari equipment..." value="{{ request('search') }}">
                    <select name="kategori_id" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('kategori_id') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="select-apple !text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Cari</button>
                    @if(request('search') || request('kategori_id') || request('status'))
                        <a href="{{ route('admin.products.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-sm !px-3 !py-1.5 transition-colors">Reset</a>
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Tambah Equipment</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Kategori</th>
                        <th>Harga/Hari</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                @if($product->gambar_utama)
                                    <img src="{{ asset('storage/' . $product->gambar_utama) }}" class="w-10 h-10 object-cover rounded" alt="{{ $product->nama_produk }}">
                                @else
                                    <div class="w-10 h-10 bg-[#f5f5f7] rounded flex items-center justify-center text-xs">No img</div>
                                @endif
                                <div>
                                    <span class="font-medium">{{ $product->nama_produk }}</span>
                                    <span class="text-xs text-[#86868b] block">{{ $product->kode_produk }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</td>
                        <td>
                            <div class="flex flex-col text-xs">
                                <span class="{{ $product->stok_tersedia > 0 ? 'text-[#34c759]' : 'text-[#d70015]' }}">{{ $product->stok_tersedia }} tersedia</span>
                                <span class="text-[#86868b]">{{ $product->stok_total }} total</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'available' => 'badge-success',
                                    'unavailable' => 'badge-apple',
                                ];
                                $statusTexts = [
                                    'available' => 'Tersedia',
                                    'unavailable' => 'Tidak Tersedia',
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$product->status] ?? 'badge-apple' }}">{{ $statusTexts[$product->status] ?? $product->status }}</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Detail</a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#ff9500]">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Hapus equipment ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-[#6e6e73] py-4">Belum ada equipment</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</div>
@endsection
