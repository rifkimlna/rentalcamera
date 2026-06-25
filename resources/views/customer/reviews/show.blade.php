@extends('layouts.customer')

@section('title', 'Detail Ulasan - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <div class="flex justify-center">
        <div class="w-full max-w-3xl">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Detail Ulasan
                        </h5>
                        <div>
                            <a href="{{ route('customer.reviews.index') }}" class="btn btn-outline btn-secondary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <!-- Review Header -->
                    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                        <div>
                            <div class="flex items-center gap-3">
                                @if($review->produk->gambar_utama)
                                    <img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" 
                                         alt="{{ $review->produk->nama_produk }}" 
                                         class="rounded w-20 h-20 object-cover">
                                @else
                                    <div class="bg-base-200 rounded flex items-center justify-center w-20 h-20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-xl font-bold mb-1">{{ $review->produk->nama_produk }}</h4>
                                    <p class="text-base-content/60 mb-1 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ optional($review->produk)->brand?->nama_brand ?? '' }}
                                    </p>
                                    <p class="text-base-content/60 mb-0 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Transaksi: {{ $review->transaksi->kode_transaksi }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="md:text-right">
                            <div class="mb-2">
                                <span class="badge badge-{{ $review->status == 'approved' ? 'success' : ($review->status == 'rejected' ? 'error' : 'warning') }}">
                                    @if($review->status == 'pending')
                                        Menunggu Review
                                    @elseif($review->status == 'approved')
                                        Disetujui
                                    @elseif($review->status == 'rejected')
                                        Ditolak
                                    @endif
                                </span>
                            </div>
                            <div class="text-base-content/60 text-sm">
                                <small>
                                    Ditulis: {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="text-center mb-6">
                        <div class="text-5xl text-warning font-bold">
                            {{ $review->rating }}<small class="text-base-content/60 text-2xl">/5</small>
                        </div>
                        <div class="rating rating-md mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="show-rating-detail" class="mask mask-star-2 bg-orange-400" disabled {{ $i <= $review->rating ? 'checked' : '' }} />
                            @endfor
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="card bg-base-100 shadow-sm border border-base-200 mb-4">
                        <div class="card-body">
                            @if($review->judul)
                                <h5 class="font-bold mb-3">{{ $review->judul }}</h5>
                            @endif

                            <div class="mb-4">
                                <h6 class="text-base-content/60 mb-2 text-sm">Komentar:</h6>
                                <p class="text-lg">{{ $review->komentar }}</p>
                            </div>

                            <!-- Photos -->
                            @if($review->foto_ulasan && count($review->foto_ulasan) > 0)
                                <div class="mb-4">
                                    <h6 class="text-base-content/60 mb-3 text-sm">Foto Pendukung:</h6>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        @foreach($review->foto_ulasan as $photo)
                                            <div>
                                                <a href="{{ asset('storage/' . $photo) }}" data-lightbox="review-photos">
                                                    <img src="{{ asset('storage/' . $photo) }}" 
                                                         alt="Foto Review" 
                                                         class="rounded w-full h-48 object-cover hover:scale-[1.02] transition-transform">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Reply -->
                    @if($review->balasan)
                        <div class="card border border-success mb-4">
                            <div class="card-body">
                                <div class="flex items-center justify-between mb-2">
                                    <h6 class="font-semibold mb-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Balasan Admin
                                    </h6>
                                    @if($review->balasan_at)
                                        <small>
                                            {{ \Carbon\Carbon::parse($review->balasan_at)->translatedFormat('d M Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                                <p class="mb-0">{{ $review->balasan }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Transaction Info -->
                    <div class="card bg-base-100 shadow-sm border border-base-200 mt-4">
                        <div class="card-body">
                            <h6 class="font-semibold mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Transaksi
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="w-2/5">No. Transaksi:</td>
                                        <td><strong>{{ $review->transaksi->kode_transaksi }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Sewa:</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($review->transaksi->tanggal_pengambilan)->translatedFormat('d M Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Durasi:</td>
                                        <td>{{ $review->transaksi->lama_sewa }} hari</td>
                                    </tr>
                                </table>
                                <table class="table table-sm">
                                    <tr>
                                        <td class="w-2/5">Status:</td>
                                        <td>
                                            @if($review->transaksi->status_transaksi == 'selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Total:</td>
                                        <td class="text-success">
                                            <strong>Rp {{ number_format($review->transaksi->grand_total, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Metode Bayar:</td>
                                        <td>{{ $review->transaksi->paymentMethod->name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex gap-2">
                            @if($review->status == 'pending')
                                <a href="{{ route('customer.reviews.edit', $review->id) }}" 
                                   class="btn btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Ulasan
                                </a>

                                <form action="{{ route('customer.reviews.destroy', $review->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus Ulasan
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('customer.products.show', $review->produk->slug) }}" 
                               class="btn btn-outline btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Lightbox CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': "Gambar %1 dari %2"
});
</script>
@endpush
@endsection
