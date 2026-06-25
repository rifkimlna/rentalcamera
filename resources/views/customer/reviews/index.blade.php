@extends('layouts.customer')

@section('title', 'Ulasan Saya - Sewa Kamera Pro')

@section('content')
<div class="p-4">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h5 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Ulasan Saya
                </h5>
                <a href="{{ route('customer.reviews.available') }}" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Ulasan Baru
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </form>
                </div>
            @endif

            @if(count($reviews) > 0)
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th class="w-32">Tanggal</th>
                                <th>Produk</th>
                                <th class="w-24">Rating</th>
                                <th class="w-32">Status</th>
                                <th class="w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            @if($review->produk->gambar_utama)
                                                <img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" 
                                                     alt="{{ $review->produk->nama_produk }}" 
                                                     class="rounded w-14 h-14 object-cover">
                                            @else
                                                <div class="bg-base-200 rounded flex items-center justify-center w-14 h-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="font-semibold mb-1">{{ $review->produk->nama_produk }}</h6>
                                                <small class="text-base-content/60">
                                                    Transaksi: {{ $review->transaksi->kode_transaksi }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating rating-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" name="list-rating-{{ $review->id }}" class="mask mask-star-2 bg-orange-400" disabled {{ $i <= $review->rating ? 'checked' : '' }} />
                                            @endfor
                                            <br>
                                            <small class="text-base-content/60">{{ $review->rating }}/5</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($review->status == 'pending')
                                            <span class="badge badge-warning">Menunggu Review</span>
                                        @elseif($review->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($review->status == 'rejected')
                                            <span class="badge badge-error">Ditolak</span>
                                        @endif

                                        @if($review->balasan)
                                            <br>
                                            <small class="text-base-content/60">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 me-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                Ada Balasan
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="join join-vertical sm:join-horizontal">
                                            <a href="{{ route('customer.reviews.show', $review->id) }}" 
                                               class="join-item btn btn-outline btn-primary btn-sm" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if($review->status == 'pending')
                                                <a href="{{ route('customer.reviews.edit', $review->id) }}" 
                                                   class="join-item btn btn-outline btn-warning btn-sm" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button type="button" 
                                                        class="join-item btn btn-outline btn-error btn-sm delete-review-btn" 
                                                        data-id="{{ $review->id }}"
                                                        data-delete-url="{{ route('customer.reviews.destroy', ':id') }}"
                                                        title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
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
                    <p class="text-base-content/60 mb-4">Anda belum memberikan ulasan untuk transaksi yang sudah selesai.</p>
                    <a href="{{ route('customer.reviews.available') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Lihat Transaksi yang Bisa Diulas
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<dialog id="deleteModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Konfirmasi Hapus</h3>
        <p>Apakah Anda yakin ingin menghapus ulasan ini?</p>
        <p class="text-error"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
        <div class="modal-action">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-ghost" onclick="deleteModal.close()">Batal</button>
                <button type="submit" class="btn btn-error">Hapus</button>
            </form>
        </div>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete review confirmation
    $('.delete-review-btn').click(function() {
        const reviewId = $(this).data('id');
        const deleteUrl = $(this).data('delete-url').replace(':id', reviewId);

        $('#deleteForm').attr('action', deleteUrl);
        deleteModal.showModal();
    });
});
</script>
@endpush
