@extends('layouts.admin')

@section('title', 'Manajemen Ulasan')
@section('page-title', 'Manajemen Ulasan')
@section('page-subtitle', ($reviews->total() ?? 0) . ' ulasan • Moderasi rating')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Ulasan</h1>
            <p class="text-xs text-[#86868b] mt-1">Moderasi rating & komentar — bintang konsisten</p>
        </div>
        <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-100 rounded-full px-3 py-1.5 text-xs font-medium text-amber-700"><x-admin.icon name="reviews" :size="14" /> {{ $reviews->total() }} ulasan</span>
    </div>

    <x-admin.card>
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Cari</label>
                <div class="flex items-center gap-2 bg-[#f5f5f7] border border-transparent rounded-full px-3 py-2 focus-within:bg-white focus-within:border-[#1d1d1f] transition">
                    <x-admin.icon name="search" :size="14" color="text-[#86868b]" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Produk, user..." class="bg-transparent border-0 p-0 text-xs w-full focus:ring-0 outline-none placeholder:text-[#86868b]">
                </div>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status</label>
                <select name="status" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Rating</label>
                <select name="rating" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua Rating</option>
                    @foreach($ratings as $r)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Bintang</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-apple-sm !rounded-full">
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-apple-sm !rounded-full">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2.5 hover:bg-black transition">Filter</button>
                <a href="{{ route('admin.reviews.index') }}" class="bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73] rounded-full p-2.5 hover:bg-[#e8e8ed] transition"><x-admin.icon name="x" :size="16" /></a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card padding="p-0 overflow-hidden">
        @if($reviews->count() > 0)
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                            <th class="px-4 py-3 font-medium">ID</th>
                            <th class="px-4 py-3 font-medium">Produk</th>
                            <th class="px-4 py-3 font-medium">User</th>
                            <th class="px-4 py-3 font-medium">Rating</th>
                            <th class="px-4 py-3 font-medium">Komentar</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Tanggal</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f5f5f7]">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-[#f5f5f7]/50 transition">
                                <td class="px-4 py-3 text-xs font-mono text-[#6e6e73]">{{ $review->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-10 h-10 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center overflow-hidden shrink-0">
                                            @if($review->produk && $review->produk->gambar_utama)
                                                <img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <x-admin.icon name="package" :size="16" color="text-[#86868b]" />
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-[#1d1d1f] truncate max-w-[140px]">{{ $review->produk->nama_produk ?? 'Produk dihapus' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-[#1d1d1f]">{{ $review->user->nama ?? 'Anonim' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $review->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}"><x-admin.icon name="reviews" :size="12" /></span>
                                        @endfor
                                        <span class="text-[11px] text-[#86868b] ml-1">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-[#1d1d1f] truncate max-w-[180px]">{{ $review->komentar }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($review->hasPhotos())<span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">{{ $review->getPhotoCount() }} foto</span>@endif
                                        @if($review->hasReply())<span class="text-[11px] inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100"><x-admin.icon name="check" :size="12" /> Dibalas</span>@endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($review->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">Menunggu</span>
                                    @elseif($review->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Disetujui</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-[#d70015] border border-red-100">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-[#6e6e73] whitespace-nowrap">{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-[#e5e5e7] text-[#6e6e73] hover:bg-[#f5f5f7] transition"><x-admin.icon name="eye" :size="14" /></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="lg:hidden divide-y divide-[#f5f5f7]">
                @foreach($reviews as $review)
                    <div class="p-4">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-[12px] bg-[#f5f5f7] border border-[#e5e5e7] flex items-center justify-center overflow-hidden shrink-0">
                                @if($review->produk && $review->produk->gambar_utama)<img src="{{ asset('storage/' . $review->produk->gambar_utama) }}" class="w-full h-full object-cover">@else<x-admin.icon name="package" :size="16" color="text-[#86868b]" />@endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $review->produk->nama_produk ?? 'Produk dihapus' }}</div>
                                <div class="text-xs text-[#86868b] truncate">{{ $review->user->nama ?? 'Anonim' }} • {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y') }}</div>
                                <div class="flex items-center gap-0.5 mt-1">
                                    @for($i=1;$i<=5;$i++)<span class="{{ $i <= $review->rating ? 'text-[#ff9500]' : 'text-[#e5e5e7]' }}"><x-admin.icon name="reviews" :size="12" /></span>@endfor
                                    <span class="text-[11px] text-[#86868b] ml-1">{{ $review->rating }}/5</span>
                                    @if($review->status=='pending')<span class="ml-2 text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-100">Menunggu</span>
                                    @elseif($review->status=='approved')<span class="ml-2 text-[11px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Disetujui</span>
                                    @else<span class="ml-2 text-[11px] px-2 py-0.5 rounded-full bg-red-50 text-[#d70015] border border-red-100">Ditolak</span>@endif
                                </div>
                                <div class="text-sm text-[#1d1d1f] mt-1 line-clamp-2">{{ $review->komentar }}</div>
                            </div>
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="w-8 h-8 rounded-full bg-white border border-[#e5e5e7] flex items-center justify-center shrink-0"><x-admin.icon name="eye" :size="14" color="text-[#6e6e73]" /></a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-5 py-4 border-t border-[#f0f0f2] flex justify-center">{{ $reviews->links() }}</div>
        @else
            <div class="p-10 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 mb-3"><x-admin.icon name="reviews" :size="20" /></div>
                <div class="text-sm font-medium text-[#1d1d1f]">Belum Ada Ulasan</div>
                <div class="text-xs text-[#86868b] mt-1">Belum ada ulasan yang masuk.</div>
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
