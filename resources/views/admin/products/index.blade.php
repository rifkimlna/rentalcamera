@extends('layouts.admin')

@section('title', 'Manajemen Produk - Sewa Kamera Pro')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Manajemen Produk</h1>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk
            </a>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="label"><span class="label-text">Cari Produk</span></label>
                    <input type="text" class="input input-bordered w-full" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Nama atau kode produk...">
                </div>
                <div>
                    <label for="kategori_id" class="label"><span class="label-text">Kategori</span></label>
                    <select class="select select-bordered w-full" id="kategori_id" name="kategori_id">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('kategori_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="brand_id" class="label"><span class="label-text">Brand</span></label>
                    <select class="select select-bordered w-full" id="brand_id" name="brand_id">
                        <option value="">Semua Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->nama_brand }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="label"><span class="label-text">Status</span></label>
                    <select class="select select-bordered w-full" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary grow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Brand</th>
                            <th>Harga/Hari</th>
                            <th>Stok</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($product->gambar_utama)
                                            <img src="{{ asset('storage/' . $product->gambar_utama) }}"
                                                 class="rounded" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="bg-base-200 rounded flex items-center justify-center" style="width: 50px; height: 50px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold">{{ $product->nama_produk }}</div>
                                            <div class="text-xs text-base-content/60">{{ $product->kode_produk }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $product->brand->nama_brand ?? '-' }}</td>
                                <td>Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</td>
                                <td>
                                    <div class="flex flex-col">
                                        <small>Tersedia: {{ $product->stok_tersedia }}</small>
                                        <small>Total: {{ $product->stok_total }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span>{{ number_format($product->rating, 1) }}</span>
                                    <small class="text-base-content/60">({{ $product->rating }})</small>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'available' => 'badge-success',
                                            'unavailable' => 'badge-ghost',
                                            'maintenance' => 'badge-warning'
                                        ];
                                        $statusTexts = [
                                            'available' => 'Tersedia',
                                            'unavailable' => 'Tidak Tersedia',
                                            'maintenance' => 'Maintenance'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$product->status] ?? 'badge-ghost' }}">
                                        {{ $statusTexts[$product->status] ?? $product->status }}
                                    </span>
                                    @if($product->is_featured)
                                        <span class="badge badge-info mt-1">Featured</span>
                                    @endif
                                    @if($product->is_recommended)
                                        <span class="badge badge-primary mt-1">Recommended</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.products.show', $product->id) }}"
                                           class="btn btn-sm btn-ghost" title="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                           class="btn btn-sm btn-ghost text-warning" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                                              method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost text-error" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-base-content/60">Tidak ada produk ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-4">
                <div>
                    <p class="text-sm text-base-content/60">
                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                    </p>
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
