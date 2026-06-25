@extends('layouts.admin')

@section('title', 'Manajemen Ulasan - Sewa Kamera Pro')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Manajemen Ulasan</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="card bg-base-100 shadow-md mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="label"><span class="label-text">Cari</span></label>
                    <input type="text" class="input input-bordered w-full" id="search" name="search" value="{{ request('search') }}" placeholder="Produk, user, atau komentar...">
                </div>
                <div>
                    <label for="status" class="label"><span class="label-text">Status</span></label>
                    <select class="select select-bordered w-full" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label for="rating" class="label"><span class="label-text">Rating</span></label>
                    <select class="select select-bordered w-full" id="rating" name="rating">
                        <option value="">Semua Rating</option>
                        @foreach($ratings as $r)
                            <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Bintang</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from" class="label"><span class="label-text">Dari Tanggal</span></label>
                    <input type="date" class="input input-bordered w-full" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div>
                    <label for="date_to" class="label"><span class="label-text">Sampai Tanggal</span></label>
                    <input type="date" class="input input-bordered w-full" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-ghost">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="text-base-content/60">Total: {{ $reviews->total() }} ulasan</span>
                </div>
            </div>

            @if($reviews->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th class="w-20">ID</th>
                                <th>Produk</th>
                                <th>User</th>
                                <th class="w-20">Rating</th>
                                <th>Komentar</th>
                                <th class="w-28">Status</th>
                                <th class="w-24">Tanggal</th>
                                <th class="w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @if($review->produk && $review->produk->gambar_utama)
                                                <img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" 
                                                     alt="{{ $review->produk->nama_produk }}" 
                                                     class="rounded w-10 h-10 object-cover">
                                            @endif
                                            <span class="font-medium">
                                                {{ $review->produk->nama_produk ?? 'Produk dihapus' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $review->user->nama ?? 'Anonim' }}</td>
                                    <td>
                                        <div class="rating rating-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" class="mask mask-star-2 bg-orange-400" disabled {{ $i <= $review->rating ? 'checked' : '' }} />
                                            @endfor
                                            <br>
                                            <small class="text-base-content/60">{{ $review->rating }}/5</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-xs truncate">
                                            {{ $review->komentar }}
                                        </div>
                                        @if($review->hasPhotos())
                                            <br>
                                            <small class="text-base-content/60">{{ $review->getPhotoCount() }} foto</small>
                                        @endif
                                        @if($review->hasReply())
                                            <br>
                                            <small class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                Dibalas
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($review->status == 'pending')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($review->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($review->status == 'rejected')
                                            <span class="badge badge-error">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reviews.show', $review->id) }}" 
                                           class="btn btn-outline btn-primary btn-sm" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h4>Belum Ada Ulasan</h4>
                    <p class="text-base-content/60">Belum ada ulasan yang masuk.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection