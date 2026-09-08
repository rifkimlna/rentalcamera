@extends('layouts.admin')

@section('title', 'Equipment')
@section('page-title', 'Equipment')
@section('page-subtitle', ($products->total() ?? 0) . ' equipment • Filter & kelola stok')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Equipment</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola katalog & stok — auto layout ke card di HP</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition shrink-0"><x-admin.icon name="plus" :size="14" /> Tambah Equipment</a>
    </div>

    <x-admin.card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Cari</label>
                <div class="flex items-center gap-2 bg-[#f5f5f7] border border-transparent rounded-full px-3 py-2 focus-within:bg-white focus-within:border-[#1d1d1f] transition">
                    <x-admin.icon name="search" :size="14" color="text-[#86868b]" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari equipment..." class="bg-transparent border-0 p-0 text-xs w-full focus:ring-0 outline-none placeholder:text-[#86868b]">
                </div>
            </div>
            <div class="min-w-[150px]">
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Kategori</label>
                <select name="kategori_id" class="select-apple-sm !rounded-full" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('kategori_id') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status</label>
                <select name="status" class="select-apple-sm !rounded-full" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition">Cari</button>
                @if(request('search') || request('kategori_id') || request('status'))
                    <a href="{{ route('admin.products.index') }}" class="bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73] rounded-full px-3 py-2 text-xs font-medium hover:bg-[#e8e8ed] transition">Reset</a>
                @endif
            </div>
        </form>
    </x-admin.card>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">Equipment</th>
                        <th class="px-5 py-3 font-medium">Kategori</th>
                        <th class="px-5 py-3 font-medium text-right">Harga/Hari</th>
                        <th class="px-5 py-3 font-medium">Stok</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($products as $product)
                    <tr class="hover:bg-[#f5f5f7]/50 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center overflow-hidden shrink-0">
                                    @if($product->gambar_utama)
                                        <img src="{{ asset('storage/' . $product->gambar_utama) }}" class="w-full h-full object-cover" alt="{{ $product->nama_produk }}">
                                    @else
                                        <x-admin.icon name="package" :size="16" color="text-[#86868b]" />
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-[#1d1d1f] truncate max-w-[180px]">{{ $product->nama_produk }}</div>
                                    <div class="text-xs font-mono text-[#86868b]">{{ $product->kode_produk }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-50 text-violet-700 border border-violet-100">{{ $product->kategori->nama_kategori ?? '-' }}</span></td>
                        <td class="px-5 py-3 text-right font-semibold text-[#1d1d1f] text-xs">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex flex-col text-xs">
                                <span class="{{ $product->stok_tersedia > 0 ? 'text-emerald-600 font-medium' : 'text-[#d70015] font-medium' }}">{{ $product->stok_tersedia }} tersedia</span>
                                <span class="text-[#86868b]">{{ $product->stok_total }} total</span>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            @if($product->status === 'available')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Tersedia</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="inline-flex items-center gap-1 bg-white border border-[#e5e5e7] rounded-full px-2.5 py-1 text-xs font-medium text-[#6e6e73] hover:bg-[#f5f5f7] transition"><x-admin.icon name="eye" :size="12" /> Detail</a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 rounded-full px-2.5 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100 transition"><x-admin.icon name="edit" :size="12" /> Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Hapus equipment ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 bg-red-50 border border-red-100 rounded-full px-2.5 py-1 text-xs font-medium text-[#d70015] hover:bg-red-100 transition"><x-admin.icon name="trash" :size="12" /> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada equipment" subtitle="Tambahkan equipment pertama untuk mulai menyewakan." icon="package" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($products as $product)
                <div class="p-4 flex gap-3">
                    <div class="w-12 h-12 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center overflow-hidden shrink-0">
                        @if($product->gambar_utama)<img src="{{ asset('storage/' . $product->gambar_utama) }}" class="w-full h-full object-cover">@else<x-admin.icon name="package" :size="16" color="text-[#86868b]" />@endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $product->nama_produk }}</div>
                        <div class="text-xs font-mono text-[#86868b]">{{ $product->kode_produk }} • {{ $product->kategori->nama_kategori ?? '-' }}</div>
                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                            <span class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}/hari</span>
                            <span class="text-xs px-2 py-0.5 rounded-full border {{ $product->stok_tersedia > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-[#d70015] border-red-100' }}">{{ $product->stok_tersedia }}/{{ $product->stok_total }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full border {{ $product->status==='available' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-[#f5f5f7] text-[#6e6e73] border-[#e5e5e7]' }}">{{ $product->status==='available' ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-2">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="text-xs bg-white border border-[#e5e5e7] rounded-full px-2.5 py-1">Detail</a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-xs bg-amber-50 border border-amber-100 rounded-full px-2.5 py-1 text-amber-700">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada equipment" icon="package" /></div>
            @endforelse
        </div>

        <div class="px-5 py-4 border-t border-[#f0f0f2]">{{ $products->links() }}</div>
    </x-admin.card>
</div>
@endsection
