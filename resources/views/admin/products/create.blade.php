@extends('layouts.admin')

@section('title', 'Tambah Equipment')
@section('page-title', 'Tambah Equipment')

@section('content')
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Dasar</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nama_produk" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Produk *</span></label>
                                        <input type="text" class="input-apple w-full @error('nama_produk') input-error @enderror"
                                               id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                                        @error('nama_produk')
                                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="kode_produk" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kode Produk *</span></label>
                                        <input type="text" class="input-apple w-full @error('kode_produk') input-error @enderror"
                                               id="kode_produk" name="kode_produk" value="{{ old('kode_produk') }}" required>
                                        @error('kode_produk')
                                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="kategori_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kategori *</span></label>
                                        <select class="select-apple w-full @error('kategori_id') select-error @enderror"
                                                id="kategori_id" name="kategori_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="brand_id" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Brand *</span></label>
                                        <select class="select-apple w-full @error('brand_id') select-error @enderror"
                                                id="brand_id" name="brand_id" required>
                                            <option value="">Pilih Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->nama_brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="deskripsi_singkat" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi Singkat</span></label>
                                    <textarea class="input-apple resize-none w-full @error('deskripsi_singkat') textarea-error @enderror"
                                              id="deskripsi_singkat" name="deskripsi_singkat" rows="2">{{ old('deskripsi_singkat') }}</textarea>
                                    @error('deskripsi_singkat')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="deskripsi_lengkap" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi Lengkap</span></label>
                                    <textarea class="input-apple resize-none w-full @error('deskripsi_lengkap') textarea-error @enderror"
                                              id="deskripsi_lengkap" name="deskripsi_lengkap" rows="4">{{ old('deskripsi_lengkap') }}</textarea>
                                    @error('deskripsi_lengkap')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Spesifikasi</h2>
                                <div id="specifications-container">
                                    <div class="specification-row grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end">
                                        <div class="md:col-span-2">
                                            <input type="text" class="input-apple w-full" name="spesifikasi[0][key]" placeholder="Nama Spesifikasi">
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" class="input-apple w-full" name="spesifikasi[0][value]" placeholder="Nilai">
                                        </div>
                                        <div>
                                            <button type="button" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors remove-spec w-full" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-spec" class="btn-dark-apple-outline-apple !text-sm !px-3 !py-1.5 mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Spesifikasi
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Fitur</h2>
                                <div>
                                    <label for="fitur" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Fitur Produk (pisahkan dengan koma)</span></label>
                                    <textarea class="input-apple resize-none w-full @error('fitur') textarea-error @enderror"
                                              id="fitur" name="fitur" rows="3" placeholder="Contoh: WiFi, GPS, Weather Sealing, Touchscreen">{{ old('fitur') }}</textarea>
                                    @error('fitur')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Harga Sewa</h2>
                                <div>
                                    <label for="harga_per_hari" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Harga per Hari *</span></label>
                                    <div class="flex w-full">
                                        <span class=" bg-[#f5f5f7] px-3 flex items-center text-sm">Rp</span>
                                        <input type="number" class="input-apple w-full @error('harga_per_hari') input-error @enderror"
                                               id="harga_per_hari" name="harga_per_hari" value="{{ old('harga_per_hari') }}" required>
                                    </div>
                                    @error('harga_per_hari')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="minimum_sewa" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Min. Sewa (hari) *</span></label>
                                        <input type="number" class="input-apple w-full @error('minimum_sewa') input-error @enderror"
                                               id="minimum_sewa" name="minimum_sewa" value="{{ old('minimum_sewa', 1) }}" required>
                                        @error('minimum_sewa')
                                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="maximum_sewa" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Max. Sewa (hari) *</span></label>
                                        <input type="number" class="input-apple w-full @error('maximum_sewa') input-error @enderror"
                                               id="maximum_sewa" name="maximum_sewa" value="{{ old('maximum_sewa', 30) }}" required>
                                        @error('maximum_sewa')
                                            <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Stok & Status</h2>
                                <div>
                                    <label for="stok_total" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Stok Total *</span></label>
                                    <input type="number" class="input-apple w-full @error('stok_total') input-error @enderror"
                                           id="stok_total" name="stok_total" value="{{ old('stok_total', 1) }}" required>
                                    @error('stok_total')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="status" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status *</span></label>
                                    <select class="select-apple w-full @error('status') select-error @enderror"
                                            id="status" name="status" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    </select>
                                    @error('status')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="kondisi" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Kondisi *</span></label>
                                    <select class="select-apple w-full @error('kondisi') select-error @enderror"
                                            id="kondisi" name="kondisi" required>
                                        <option value="baru" {{ old('kondisi') == 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="bekas_excellent" {{ old('kondisi') == 'bekas_excellent' ? 'selected' : '' }}>Bekas (Excellent)</option>
                                        <option value="bekas_good" {{ old('kondisi') == 'bekas_good' ? 'selected' : '' }}>Bekas (Good)</option>
                                        <option value="bekas_fair" {{ old('kondisi') == 'bekas_fair' ? 'selected' : '' }}>Bekas (Fair)</option>
                                    </select>
                                    @error('kondisi')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5 cursor-pointer">
                                        <span class="">Tampilkan sebagai Featured</span>
                                        <input type="checkbox" class="w-11 h-6 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-[#86868b] mb-1.5 cursor-pointer">
                                        <span class="">Tampilkan sebagai Recommended</span>
                                        <input type="checkbox" class="w-11 h-6 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all" id="is_recommended" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Gambar Produk</h2>
                                <div>
                                    <label for="gambar_utama" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar Utama</span></label>
                                    <input type="file" class="input-apple w-full @error('gambar_utama') input-apple-error @enderror"
                                           id="gambar_utama" name="gambar_utama" accept="image/*">
                                    <small class="text-[#6e6e73] text-xs">Format: JPG, PNG, GIF | Maks: 2MB</small>
                                    @error('gambar_utama')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="gambar_tambahan" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Gambar Tambahan</span></label>
                                    <input type="file" class="input-apple w-full @error('gambar_tambahan.*') input-apple-error @enderror"
                                           id="gambar_tambahan" name="gambar_tambahan[]" accept="image/*" multiple>
                                    <small class="text-[#6e6e73] text-xs">Pilih beberapa gambar</small>
                                    @error('gambar_tambahan.*')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-[#1d1d1f]">Informasi Tambahan</h2>
                                <div>
                                    <label for="berat" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Berat (gram)</span></label>
                                    <input type="number" class="input-apple w-full @error('berat') input-error @enderror"
                                           id="berat" name="berat" value="{{ old('berat') }}">
                                    @error('berat')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="dimensi" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Dimensi (P x L x T cm)</span></label>
                                    <input type="text" class="input-apple w-full @error('dimensi') input-error @enderror"
                                           id="dimensi" name="dimensi" value="{{ old('dimensi') }}" placeholder="Contoh: 15 x 10 x 5">
                                    @error('dimensi')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="serial_number" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Serial Number</span></label>
                                    <input type="text" class="input-apple w-full @error('serial_number') input-error @enderror"
                                           id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                                    @error('serial_number')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="tahun_pembuatan" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tahun Pembuatan</span></label>
                                    <input type="text" class="input-apple w-full @error('tahun_pembuatan') input-error @enderror"
                                           id="tahun_pembuatan" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" maxlength="4">
                                    @error('tahun_pembuatan')
                                        <span class="text-[#d70015] text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('admin.products.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn-dark-apple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    let specCounter = 1;

    const specificationsContainer = document.getElementById('specifications-container');

    function refreshRemoveSpecButtons() {
        const rows = document.querySelectorAll('.specification-row');
        const onlyOne = rows.length === 1;
        document.querySelectorAll('.remove-spec').forEach(function (btn) {
            btn.disabled = onlyOne;
        });
    }

    document.getElementById('add-spec').addEventListener('click', function () {
        const row = `
            <div class="specification-row grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end">
                <div class="md:col-span-2">
                    <input type="text" class="input-apple w-full" name="spesifikasi[${specCounter}][key]" placeholder="Nama Spesifikasi">
                </div>
                <div class="md:col-span-2">
                    <input type="text" class="input-apple w-full" name="spesifikasi[${specCounter}][value]" placeholder="Nilai">
                </div>
                <div>
                    <button type="button" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors remove-spec w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `;
        specificationsContainer.insertAdjacentHTML('beforeend', row);
        specCounter++;
        refreshRemoveSpecButtons();
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-spec')) {
            e.target.closest('.specification-row').remove();
            refreshRemoveSpecButtons();
        }
    });
</script>
@endpush
@endsection
