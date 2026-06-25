@extends('layouts.customer')

@section('title', 'Edit Ulasan - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Ulasan
                    </h5>

                    <div class="text-sm breadcrumbs mb-4">
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
                            <label class="label">
                                <span class="label-text font-bold">Rating *</span>
                            </label>
                            <div class="rating rating-lg flex justify-center">
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="1" {{ $review->rating == 1 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="2" {{ $review->rating == 2 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="3" {{ $review->rating == 3 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="4" {{ $review->rating == 4 ? 'checked' : '' }} />
                                <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400" value="5" {{ $review->rating == 5 ? 'checked' : '' }} />
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-base-content/60">1: Sangat Buruk, 5: Sangat Baik</small>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-bold">Judul Ulasan</span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="judul" 
                                   value="{{ old('judul', $review->judul) }}"
                                   placeholder="Contoh: 'Kamera bagus untuk pemula'" maxlength="200">
                            <small class="text-base-content/60">Maksimal 200 karakter</small>
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-bold">Komentar *</span>
                            </label>
                            <textarea class="textarea textarea-bordered w-full" name="komentar" rows="5" 
                                      placeholder="Bagikan pengalaman Anda menggunakan produk ini..."
                                      required>{{ old('komentar', $review->komentar) }}</textarea>
                            <small class="text-base-content/60">Minimal 10 karakter</small>
                        </div>

                        <!-- Current Photos -->
                        @if($review->foto_ulasan && count($review->foto_ulasan) > 0)
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-bold">Foto Saat Ini</span>
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
                            <label class="label">
                                <span class="label-text font-bold">Tambah/Ganti Foto</span>
                            </label>
                            <input type="file" class="file-input file-input-bordered w-full" name="foto_baru[]" accept="image/*" multiple>
                            <small class="text-base-content/60">Unggah foto baru (Maks 3 foto, Maks 2MB per foto)</small>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('customer.reviews.index') }}" class="btn btn-outline btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
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
