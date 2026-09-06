@extends('layouts.admin')

@section('title', 'Detail Ulasan - Stekpro Multimedia & Broadcast')
@section('page-title', 'Detail Ulasan')

@section('content')
<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Detail Ulasan</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn-dark-apple-outline-apple">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#f0faf1] border border-[#d1f5d5] text-[#248a3d] text-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#fef2f2] border border-[#fecaca] text-[#d70015] text-sm mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            @if($review->produk && $review->produk->gambar_utama)
                                <img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" 
                                     alt="{{ $review->produk->nama_produk }}" 
                                     class="rounded w-16 h-16 object-cover">
                            @else
                                <div class="bg-[#f5f5f7] rounded flex items-center justify-center w-16 h-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h5 class="font-bold">{{ $review->produk->nama_produk ?? 'Produk dihapus' }}</h5>
                                <small class="text-[#6e6e73]">
                                    Transaksi: {{ $review->transaksi->kode_transaksi ?? '-' }}
                                </small>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($review->status == 'pending')
                                <span class="badge-warning text-sm">Menunggu</span>
                            @elseif($review->status == 'approved')
                                <span class="badge-success text-sm">Disetujui</span>
                            @elseif($review->status == 'rejected')
                                <span class="badge-error text-sm">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" class="text-[#ff9500]" disabled {{ $i <= $review->rating ? 'checked' : '' }} />
                            @endfor
                        </div>
                        <span class="text-xl font-bold text-[#ff9500]">{{ $review->rating }}/5</span>
                    </div>

                    @if($review->judul)
                        <h4 class="font-bold text-lg mb-2">{{ $review->judul }}</h4>
                    @endif

                    <div class="mb-4">
                        <p class="text-base">{{ $review->komentar }}</p>
                    </div>

                    @if($review->foto_ulasan && is_array($review->foto_ulasan) && count($review->foto_ulasan) > 0)
                        <div class="mb-4">
                            <h6 class="text-[#6e6e73] mb-3 text-sm font-semibold">Foto Pendukung:</h6>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($review->foto_ulasan as $photo)
                                    <div>
                                        <a href="{{ asset('storage/' . $photo) }}" data-lightbox="admin-review-photos">
                                            <img src="{{ asset('storage/' . $photo) }}" 
                                                 alt="Foto Review" 
                                                 class="rounded w-full h-32 object-cover hover:scale-[1.02] transition-transform">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="text-[#6e6e73] text-sm">
                        <small>
                            Ditulis oleh <strong>{{ $review->user->nama ?? 'Anonim' }}</strong> 
                            pada {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>

            @if($review->balasan)
                <div class="bg-white rounded-2xl border border-[#34c759]/30 mb-4">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h5 class="font-semibold mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                Balasan Admin
                            </h5>
                            @if($review->balasan_at)
                                <small class="text-[#6e6e73]">
                                    {{ \Carbon\Carbon::parse($review->balasan_at)->translatedFormat('d M Y H:i') }}
                                </small>
                            @endif
                        </div>
                        <p class="mb-0">{{ $review->balasan }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h6 class="font-semibold mb-3">Informasi Transaksi</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <table class="w-full text-sm">
                            <tr>
                                <td class="w-2/5">No. Transaksi:</td>
                                <td><strong>{{ $review->transaksi->kode_transaksi ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal Sewa:</td>
                                <td>
                                    {{ $review->transaksi && $review->transaksi->tanggal_pengambilan ? \Carbon\Carbon::parse($review->transaksi->tanggal_pengambilan)->translatedFormat('d M Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Durasi:</td>
                                <td>{{ $review->transaksi->lama_sewa ?? '-' }} hari</td>
                            </tr>
                        </table>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="w-2/5">Total:</td>
                                <td class="text-[#34c759]">
                                    <strong>Rp {{ $review->transaksi ? number_format($review->transaksi->grand_total, 0, ',', '.') : '-' }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Metode Bayar:</td>
                                <td>{{ $review->transaksi->paymentMethod->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Customer:</td>
                                <td>{{ $review->transaksi->nama_customer ?? $review->user->nama ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            @if($review->status == 'pending')
                <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                    <div class="p-5">
                        <h5 class="font-semibold mb-4">Aksi</h5>
                        <div class="flex flex-col gap-3">
                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#34c759] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#2db84d] transition-colors w-full" onclick="return confirm('Setujui ulasan ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Setujui Ulasan
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#d70015] text-white rounded-xl px-4 py-2 text-sm font-medium hover:bg-[#bf0013] transition-colors w-full" onclick="return confirm('Tolak ulasan ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tolak Ulasan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
                <div class="p-5">
                    <h5 class="font-semibold mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Balas Ulasan
                    </h5>
                    <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="balasan" rows="4" class="input-apple resize-none w-full" 
                                      placeholder="Tulis balasan untuk ulasan ini...">{{ old('balasan', $review->balasan) }}</textarea>
                            @error('balasan')
                                <small class="text-[#d70015]">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn-dark-apple w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            {{ $review->balasan ? 'Perbarui Balasan' : 'Kirim Balasan' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#f0f0f2]">
                <div class="p-5">
                    <h5 class="font-semibold mb-4">Info User</h5>
                    <div class="flex items-center gap-3 mb-3">
                        @if($review->user && $review->user->foto_profile)
                            <img src="{{ asset('storage/' . $review->user->foto_profile) }}" 
                                 alt="{{ $review->user->nama }}" 
                                 class="rounded-full w-12 h-12 object-cover">
                        @else
                            <div class="bg-[#f5f5f7] rounded-full flex items-center justify-center w-12 h-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6e6e73]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $review->user->nama ?? 'Anonim' }}</strong>
                            <br>
                            <small class="text-[#6e6e73]">{{ $review->user->email ?? '-' }}</small>
                        </div>
                    </div>
                    @if($review->user)
                        <a href="{{ route('admin.users.show', $review->user->id) }}" class="btn-dark-apple-outline-apple !text-sm !px-3 !py-1.5 w-full">
                            Lihat Profile User
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
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