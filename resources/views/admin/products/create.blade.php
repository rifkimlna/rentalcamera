@extends('layouts.admin')

@section('title', 'Tambah Produk - Sewa Kamera Pro')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Tambah Produk Baru</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Informasi Dasar</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nama_produk" class="label"><span class="label-text">Nama Produk *</span></label>
                                        <input type="text" class="input input-bordered w-full @error('nama_produk') input-error @enderror"
                                               id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                                        @error('nama_produk')
                                            <span class="text-error text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="kode_produk" class="label"><span class="label-text">Kode Produk *</span></label>
                                        <input type="text" class="input input-bordered w-full @error('kode_produk') input-error @enderror"
                                               id="kode_produk" name="kode_produk" value="{{ old('kode_produk') }}" required>
                                        @error('kode_produk')
                                            <span class="text-error text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="kategori_id" class="label"><span class="label-text">Kategori *</span></label>
                                        <select class="select select-bordered w-full @error('kategori_id') select-error @enderror"
                                                id="kategori_id" name="kategori_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <span class="text-error text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="brand_id" class="label"><span class="label-text">Brand *</span></label>
                                        <select class="select select-bordered w-full @error('brand_id') select-error @enderror"
                                                id="brand_id" name="brand_id" required>
                                            <option value="">Pilih Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->nama_brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <span class="text-error text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label for="deskripsi_singkat" class="label"><span class="label-text">Deskripsi Singkat</span></label>
                                    <textarea class="textarea textarea-bordered w-full @error('deskripsi_singkat') textarea-error @enderror"
                                              id="deskripsi_singkat" name="deskripsi_singkat" rows="2">{{ old('deskripsi_singkat') }}</textarea>
                                    @error('deskripsi_singkat')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="deskripsi_lengkap" class="label"><span class="label-text">Deskripsi Lengkap</span></label>
                                    <textarea class="textarea textarea-bordered w-full @error('deskripsi_lengkap') textarea-error @enderror"
                                              id="deskripsi_lengkap" name="deskripsi_lengkap" rows="4">{{ old('deskripsi_lengkap') }}</textarea>
                                    @error('deskripsi_lengkap')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Spesifikasi</h2>
                                <div id="specifications-container">
                                    <div class="specification-row grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end">
                                        <div class="md:col-span-2">
                                            <input type="text" class="input input-bordered w-full" name="spesifikasi[0][key]" placeholder="Nama Spesifikasi">
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" class="input input-bordered w-full" name="spesifikasi[0][value]" placeholder="Nilai">
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-error remove-spec w-full" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-spec" class="btn btn-outline btn-sm mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Spesifikasi
                                </button>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Fitur</h2>
                                <div>
                                    <label for="fitur" class="label"><span class="label-text">Fitur Produk (pisahkan dengan koma)</span></label>
                                    <textarea class="textarea textarea-bordered w-full @error('fitur') textarea-error @enderror"
                                              id="fitur" name="fitur" rows="3" placeholder="Contoh: WiFi, GPS, Weather Sealing, Touchscreen">{{ old('fitur') }}</textarea>
                                    @error('fitur')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Harga Sewa</h2>
                                <div>
                                    <label for="harga_per_hari" class="label"><span class="label-text">Harga per Hari *</span></label>
                                    <div class="join w-full">
                                        <span class="join-item bg-base-200 px-3 flex items-center text-sm">Rp</span>
                                        <input type="number" class="input input-bordered join-item w-full @error('harga_per_hari') input-error @enderror"
                                               id="harga_per_hari" name="harga_per_hari" value="{{ old('harga_per_hari') }}" required>
                                    </div>
                                    @error('harga_per_hari')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="harga_per_minggu" class="label"><span class="label-text">Harga per Minggu</span></label>
                                    <div class="join w-full">
                                        <span class="join-item bg-base-200 px-3 flex items-center text-sm">Rp</span>
                                        <input type="number" class="input input-bordered join-item w-full @error('harga_per_minggu') input-error @enderror"
                                               id="harga_per_minggu" name="harga_per_minggu" value="{{ old('harga_per_minggu') }}">
                                    </div>
                                    @error('harga_per_minggu')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="harga_per_bulan" class="label"><span class="label-text">Harga per Bulan</span></label>
                                    <div class="join w-full">
                                        <span class="join-item bg-base-200 px-3 flex items-center text-sm">Rp</span>
                                        <input type="number" class="input input-bordered join-item w-full @error('harga_per_bulan') input-error @enderror"
                                               id="harga_per_bulan" name="harga_per_bulan" value="{{ old('harga_per_bulan') }}">
                                    </div>
                                    @error('harga_per_bulan')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="minimum_sewa" class="label"><span class="label-text">Min. Sewa (hari) *</span></label>
                                        <input type="number" class="input input-bordered w-full @error('minimum_sewa') input-error @enderror"
                                               id="minimum_sewa" name="minimum_sewa" value="{{ old('minimum_sewa', 1) }}" required>
                                        @error('minimum_sewa')
                                            <span class="text-error text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="maximum_sewa" class="label"><span class="label-text">Max. Sewa (hari) *</span></label>
                                        <input type="number" class="input input-bordered w-full @error('maximum_sewa') input-error @enderror"
                                               id="maximum_sewa" name="maximum_sewa" value="{{ old('maximum_sewa', 30) }}" required>
                                        @error('maximum_sewa')
                                            <span class="text-error text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Stok & Status</h2>
                                <div>
                                    <label for="stok_total" class="label"><span class="label-text">Stok Total *</span></label>
                                    <input type="number" class="input input-bordered w-full @error('stok_total') input-error @enderror"
                                           id="stok_total" name="stok_total" value="{{ old('stok_total', 1) }}" required>
                                    @error('stok_total')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="status" class="label"><span class="label-text">Status *</span></label>
                                    <select class="select select-bordered w-full @error('status') select-error @enderror"
                                            id="status" name="status" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('status')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="kondisi" class="label"><span class="label-text">Kondisi *</span></label>
                                    <select class="select select-bordered w-full @error('kondisi') select-error @enderror"
                                            id="kondisi" name="kondisi" required>
                                        <option value="baru" {{ old('kondisi') == 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="bekas_excellent" {{ old('kondisi') == 'bekas_excellent' ? 'selected' : '' }}>Bekas (Excellent)</option>
                                        <option value="bekas_good" {{ old('kondisi') == 'bekas_good' ? 'selected' : '' }}>Bekas (Good)</option>
                                        <option value="bekas_fair" {{ old('kondisi') == 'bekas_fair' ? 'selected' : '' }}>Bekas (Fair)</option>
                                    </select>
                                    @error('kondisi')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Tampilkan sebagai Featured</span>
                                        <input type="checkbox" class="toggle toggle-primary" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="label cursor-pointer">
                                        <span class="label-text">Tampilkan sebagai Recommended</span>
                                        <input type="checkbox" class="toggle toggle-primary" id="is_recommended" name="is_recommended" value="1" {{ old('is_recommended') ? 'checked' : '' }}>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Gambar Produk</h2>
                                <div>
                                    <label for="gambar_utama" class="label"><span class="label-text">Gambar Utama</span></label>
                                    <input type="file" class="file-input file-input-bordered w-full @error('gambar_utama') file-input-error @enderror"
                                           id="gambar_utama" name="gambar_utama" accept="image/*">
                                    <small class="text-base-content/60 text-xs">Format: JPG, PNG, GIF | Maks: 2MB</small>
                                    @error('gambar_utama')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="gambar_tambahan" class="label"><span class="label-text">Gambar Tambahan</span></label>
                                    <input type="file" class="file-input file-input-bordered w-full @error('gambar_tambahan.*') file-input-error @enderror"
                                           id="gambar_tambahan" name="gambar_tambahan[]" accept="image/*" multiple>
                                    <small class="text-base-content/60 text-xs">Pilih beberapa gambar</small>
                                    @error('gambar_tambahan.*')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-md mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Informasi Tambahan</h2>
                                <div>
                                    <label for="berat" class="label"><span class="label-text">Berat (gram)</span></label>
                                    <input type="number" class="input input-bordered w-full @error('berat') input-error @enderror"
                                           id="berat" name="berat" value="{{ old('berat') }}">
                                    @error('berat')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="dimensi" class="label"><span class="label-text">Dimensi (P x L x T cm)</span></label>
                                    <input type="text" class="input input-bordered w-full @error('dimensi') input-error @enderror"
                                           id="dimensi" name="dimensi" value="{{ old('dimensi') }}" placeholder="Contoh: 15 x 10 x 5">
                                    @error('dimensi')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="serial_number" class="label"><span class="label-text">Serial Number</span></label>
                                    <input type="text" class="input input-bordered w-full @error('serial_number') input-error @enderror"
                                           id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                                    @error('serial_number')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="tahun_pembuatan" class="label"><span class="label-text">Tahun Pembuatan</span></label>
                                    <input type="text" class="input input-bordered w-full @error('tahun_pembuatan') input-error @enderror"
                                           id="tahun_pembuatan" name="tahun_pembuatan" value="{{ old('tahun_pembuatan') }}" maxlength="4">
                                    @error('tahun_pembuatan')
                                        <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let specCounter = 1;

    $('#add-spec').click(function() {
        const row = `
            <div class="specification-row grid grid-cols-1 md:grid-cols-5 gap-2 mb-2 items-end">
                <div class="md:col-span-2">
                    <input type="text" class="input input-bordered w-full" name="spesifikasi[${specCounter}][key]" placeholder="Nama Spesifikasi">
                </div>
                <div class="md:col-span-2">
                    <input type="text" class="input input-bordered w-full" name="spesifikasi[${specCounter}][value]" placeholder="Nilai">
                </div>
                <div>
                    <button type="button" class="btn btn-error remove-spec w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        `;
        $('#specifications-container').append(row);
        specCounter++;
        $('.remove-spec').prop('disabled', false);
    });

    $(document).on('click', '.remove-spec', function() {
        $(this).closest('.specification-row').remove();
        if ($('.specification-row').length === 1) {
            $('.remove-spec').prop('disabled', true);
        }
    });

    $('#harga_per_hari').on('change', function() {
        const dailyPrice = parseFloat($(this).val()) || 0;
        const weeklyPrice = dailyPrice * 7;
        const monthlyPrice = dailyPrice * 30;

        if ($('#harga_per_minggu').val() === '') {
            $('#harga_per_minggu').val(Math.round(weeklyPrice));
        }

        if ($('#harga_per_bulan').val() === '') {
            $('#harga_per_bulan').val(Math.round(monthlyPrice));
        }
    });
</script>
@endpush
@endsection
