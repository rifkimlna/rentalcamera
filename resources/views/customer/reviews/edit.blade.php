@extends('layouts.customer')

@section('title', 'Edit Ulasan - Stekpro Multimedia & Broadcast')

@section('content')
<x-flash-messages />
<div class="p-4">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="card bg-white shadow-md">
                <div class="p-5">
                    <h5 class="font-semibold text-lg mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-lineflex="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Ulasan
                    </h5>

                    <div class="text-sm mb-4">
                        <ul>
                            <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('customer.reviews.index') }}">Ulasan Saya</a></li>
                            <li>Edit Ulasan</li>
                        </ul>
                    </div>

                    <form action="{{ route('customer.reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Rating -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Rating *</span>
                            </label>
                            <div class="flex items-center gap-1 justify-center">
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="1" {{ $review->rating == 1 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="2" {{ $review->rating == 2 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="3" {{ $review->rating == 3 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="4" {{ $review->rating == 4 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="h-4 w-4 text-[#ff9500]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/> value="5" {{ $review->rating == 5 ? 'checked' : '' }} />
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-[#6e6e73]">1: Sangat Buruk, 5: Sangat Baik</small>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Judul Ulasan</span>
                            </label>
                            <input type="text" class="input-apple w-full" name="judul" 
                                   value="{{ old('judul', $review->judul) }}"
                                   placeholder="Contoh: 'Kamera bagus untuk pemula'" maxlength="200">
                            <small class="text-[#6e6e73]">Maksimal 200 karakter</small>
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Komentar *</span>
                            </label>
                            <textarea class="input-apple resize-none w-full" name="komentar" rows="5" 
                                      placeholder="Bagikan pengalaman Anda menggunakan produk ini..."
                                      required>{{ old('komentar', $review->komentar) }}</textarea>
                            <small class="text-[#6e6e73]">Minimal 10 karakter</small>
                        </div>

                        <!-- Current Photos -->
                        @if($review->foto_ulasan && count($review->foto_ulasan) > 0)
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Foto Saat Ini</span>
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($review->foto_ulasan as $photo)
                                    <div class="w-24 h-24">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Review photo" class="w-full h-full object-cover rounded">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- New Photos -->
                        <div class="mb-4">
                            <label>
                                <span class="block text-sm font-medium text-[#1d1d1f]">Tambah/Ganti Foto</span>
                            </label>
                            <input type="file" class="input-apple w-full" name="foto_ulasan[]" accept="image/*" multiple>
                            <small class="text-[#6e6e73]">Unggah foto baru (Maks 3 foto, Maks 2MB per foto)</small>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button type="submit" class="btn-dark-apple">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-lineflex="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('customer.reviews.index') }}" class="btn-outline-apple">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-lineflex="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

