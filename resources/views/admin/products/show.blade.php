@extends('layouts.admin')

@section('title', $product->nama_produk)
@section('page-title', $product->nama_produk)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                @if($product->gambar_utama)
                    <img src="{{ asset('storage/' . $product->gambar_utama) }}" alt="{{ $product->nama_produk }}" class="w-full rounded mb-3">
                @endif
                @php
                    $additionalImages = is_array($product->gambar_tambahan) ? $product->gambar_tambahan : (json_decode($product->gambar_tambahan, true) ?? []);
                @endphp
                @if(!empty($additionalImages))
                    <div class="grid grid-cols-3 gap-1 mb-3">
                        @foreach($additionalImages as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Gambar tambahan {{ $product->nama_produk }}" class="rounded w-full h-14 object-cover">
                        @endforeach
                    </div>
                @endif
                <h5 class="font-medium">{{ $product->nama_produk }}</h5>
                <p class="text-sm text-[#6e6e73]">{{ $product->kode_produk }}</p>
                <p class="text-lg font-bold text-[#0071e3] mt-1">{{ $product->harga_per_hari_formatted }} / hari</p>

                <div class="flex flex-wrap gap-1 mt-2">
                    <span class="badge {{ $product->status == 'available' ? 'badge-success' : 'badge-apple' }}">
                        {{ $product->status == 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                    @if($product->is_featured) <span class="badge-brand">Featured</span> @endif
                    @if($product->is_recommended) <span class="badge-brand">Recommended</span> @endif
                </div>

                <div class="grid grid-cols-2 gap-2 mt-3 text-sm">
                    <div class="bg-[#f5f5f7] rounded p-2 text-center">
                        <span class="text-lg font-bold">{{ $product->stok_tersedia }}</span>
                        <small class="block text-[#6e6e73]">Stok</small>
                    </div>
                    <div class="bg-[#f5f5f7] rounded p-2 text-center">
                        <span class="text-lg font-bold">{{ $product->jumlah_dipesan }}</span>
                        <small class="block text-[#6e6e73]">Disewa</small>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-3">
                    <form action="{{ route('admin.products.update-status', $product->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="flex w-full">
                            <select class="select-apple !text-sm  flex-1" name="status">
                                <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="unavailable" {{ $product->status == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                            <button type="submit" class="btn-dark-apple-outline-apple !text-sm !px-3 !py-1.5 ">Update</button>
                        </div>
                    </form>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-dark-apple !text-sm !px-3 !py-1.5 flex-1">Edit</a>
                        <button type="button" class="btn-dark-apple-outline-apple !text-sm !px-3 !py-1.5 flex-1" onclick="document.getElementById('stockModal').showModal()">Stok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-[#f0f0f2]">
            <div class="p-5">
                <h5 class="font-medium mb-2">Deskripsi</h5>
                <p class="text-sm">{{ $product->deskripsi_lengkap ?? $product->deskripsi_singkat ?? 'Tidak ada deskripsi.' }}</p>

                @php $specifications = is_array($product->spesifikasi) ? $product->spesifikasi : (json_decode($product->spesifikasi, true) ?? []); @endphp
                @if(!empty($specifications))
                    <h5 class="font-medium mt-4 mb-2">Spesifikasi</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1 text-sm">
                        @foreach($specifications as $key => $value)
                            <div><span class="text-[#6e6e73]">{{ $key }}:</span> {{ $value }}</div>
                        @endforeach
                    </div>
                @endif

                @if($product->fitur)
                    <h5 class="font-medium mt-4 mb-2">Fitur</h5>
                    <div class="flex flex-wrap gap-1">
                        @php $features = is_array($product->fitur) ? $product->fitur : explode(',', $product->fitur); @endphp
                        @foreach($features as $feature)
                            @if(trim($feature))
                                <span class="badge-apple border border-[#e5e5e7]">{{ trim($feature) }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] mt-4">
            <div class="p-5">
                <h5 class="font-medium mb-2">Informasi Tambahan</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <div><span class="text-[#6e6e73]">Kategori:</span> {{ $product->kategori->nama_kategori ?? '-' }}</div>
                    <div><span class="text-[#6e6e73]">Brand:</span> {{ $product->brand->nama_brand ?? '-' }}</div>
                    <div><span class="text-[#6e6e73]">Berat:</span> {{ $product->berat ? $product->berat . ' gram' : '-' }}</div>
                    <div><span class="text-[#6e6e73]">Dimensi:</span> {{ $product->dimensi ?? '-' }}</div>
                    <div><span class="text-[#6e6e73]">Serial Number:</span> {{ $product->serial_number ?? '-' }}</div>
                    <div><span class="text-[#6e6e73]">Tahun:</span> {{ $product->tahun_pembuatan ?? '-' }}</div>
                    <div>
                        <span class="text-[#6e6e73]">Kondisi:</span>
                        @php
                            $conditionTexts = ['baru' => 'Baru', 'bekas_excellent' => 'Bekas (Excellent)', 'bekas_good' => 'Bekas (Good)', 'bekas_fair' => 'Bekas (Fair)'];
                        @endphp
                        {{ $conditionTexts[$product->kondisi] ?? $product->kondisi }}
                    </div>
                    <div><span class="text-[#6e6e73]">Min/Max Sewa:</span> {{ $product->minimum_sewa }} - {{ $product->maximum_sewa }} hari</div>
                    <div><span class="text-[#6e6e73]">Rating:</span> {{ number_format($product->rating, 1) }} ({{ $product->jumlah_ulasan }} ulasan)</div>
                    <div><span class="text-[#6e6e73]">Disewa:</span> {{ $product->jumlah_dipesan }} kali</div>
                    <div><span class="text-[#6e6e73]">Stok Dipinjam:</span> {{ $product->stok_dipinjam }}</div>
                    <div><span class="text-[#6e6e73]">Stok Rusak:</span> {{ $product->stok_rusak }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f0f0f2] mt-4">
            <div class="p-5">
                <h5 class="font-medium mb-2">Harga Sewa</h5>
                <div class="grid grid-cols-1 gap-3 text-center text-sm">
                    <div class="bg-[#f5f5f7] rounded p-3">
                        <span class="text-lg font-bold">{{ $product->harga_per_hari_formatted }}</span>
                        <small class="block text-[#6e6e73]">/hari</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<dialog id="stockModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 z-50 flex items-center justify-center-box">
        <form method="dialog">
            <button class="btn-dark-apple !text-sm !px-3 !py-1.5 w-9 h-9 flex items-center justify-center text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl transition-colors absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-lg font-bold">Kelola Stok</h3>
        <form action="{{ route('admin.products.update-stock', $product->id) }}" method="POST">
            @csrf
            <div class="py-4">
                <div>
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Aksi</span></label>
                    <select class="select-apple w-full" name="type" required>
                        <option value="add">Tambah Stok</option>
                        <option value="subtract">Kurangi Stok</option>
                        <option value="damage">Catat Rusak</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Jumlah</span></label>
                    <input type="number" class="input-apple w-full" name="quantity" min="1" required>
                </div>
                <div class="mt-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Catatan</span></label>
                    <textarea class="input-apple resize-none w-full" name="notes" rows="2"></textarea>
                </div>
            </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center-action">
                <button type="button" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors" onclick="document.getElementById('stockModal').close()">Batal</button>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</dialog>
@endsection


