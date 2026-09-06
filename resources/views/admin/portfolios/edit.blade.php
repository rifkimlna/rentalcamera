@extends('layouts.admin')

@section('title', 'Edit Portfolio')
@section('page-title', 'Edit Portfolio')

@section('content')
<x-flash-messages />
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-6">
            <form method="POST" action="{{ route('admin.portfolios.update', $portfolio->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Judul <span class="text-[#d70015]">*</span></span></label>
                    <input type="text" name="judul" class="input-apple w-full @error('judul') input-error @enderror" value="{{ old('judul', $portfolio->judul) }}" required>
                    @error('judul') <span class="text-[#d70015] text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                    <textarea name="deskripsi" class="input-apple resize-none w-full @error('deskripsi') textarea-error @enderror" rows="3">{{ old('deskripsi', $portfolio->deskripsi) }}</textarea>
                    @error('deskripsi') <span class="text-[#d70015] text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tipe <span class="text-[#d70015]">*</span></span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="foto" class="w-4 h-4 text-[#0071e3] bg-white border-[#e5e5e7]" {{ $portfolio->tipe === 'foto' ? 'checked' : '' }} onchange="toggleTipe()">
                            <span class="text-sm">Foto</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="video" class="w-4 h-4 text-[#0071e3] bg-white border-[#e5e5e7]" {{ $portfolio->tipe === 'video' ? 'checked' : '' }} onchange="toggleTipe()">
                            <span class="text-sm">Video</span>
                        </label>
                    </div>
                    @error('tipe') <span class="text-[#d70015] text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4" id="url_input">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">URL <span class="text-xs text-[#86868b]">(Instagram atau YouTube)</span></span></label>
                    <input type="url" name="url" class="input-apple w-full @error('url') input-error @enderror" value="{{ old('url', $portfolio->url) }}" placeholder="https://www.instagram.com/p/...">
                    @error('url') <span class="text-[#d70015] text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-[#86868b] mt-1">Untuk foto dari Instagram atau video dari YouTube.</p>
                </div>

                <div class="mb-4" id="gambar_input">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Upload Gambar</span></label>
                    @if($portfolio->gambar_url)
                    <div class="mb-2">
                        <img src="{{ $portfolio->gambar_url }}" class="w-32 h-24 object-cover rounded" alt="{{ $portfolio->judul }}">
                    </div>
                    @endif
                    <input type="file" name="gambar" class="input-apple w-full @error('gambar') input-apple-error @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                    @error('gambar') <span class="text-[#d70015] text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-[#86868b] mt-1">Format: JPEG, PNG, WebP. Maks 2MB. Kosongkan jika tidak ingin mengubah.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Urutan</span></label>
                    <input type="number" name="sort_order" class="input-apple w-full @error('sort_order') input-error @enderror" value="{{ old('sort_order', $portfolio->sort_order) }}" min="0">
                    @error('sort_order') <span class="text-[#d70015] text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" class="w-9 h-5 bg-[#e5e5e7] rounded-full relative cursor-pointer transition-colors checked:bg-[#0071e3] after:content-[""] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all" value="1" {{ $portfolio->is_active ? 'checked' : '' }}>
                        <span class="text-sm">Aktif</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn-dark-apple !text-sm !px-3 !py-1.5">Simpan</button>
                    <a href="{{ route('admin.portfolios.index') }}" class="btn-dark-apple !text-sm !px-3 !py-1.5">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleTipe() {
        const tipe = document.querySelector('input[name="tipe"]:checked').value;
        document.getElementById('url_input').style.display = 'block';
    }

    function detectTipeFromUrl() {
        const url = document.querySelector('input[name="url"]').value.trim();

        if (/youtube\.com|youtu\.be/.test(url) || /instagram\.com\/reel/.test(url)) {
            document.querySelector('input[name="tipe"][value="video"]').checked = true;
        } else if (/instagram\.com\/p/.test(url)) {
            document.querySelector('input[name="tipe"][value="foto"]').checked = true;
        }
    }

    document.querySelector('input[name="url"]').addEventListener('input', detectTipeFromUrl);
    toggleTipe();
    detectTipeFromUrl();
</script>
@endpush
@endsection
