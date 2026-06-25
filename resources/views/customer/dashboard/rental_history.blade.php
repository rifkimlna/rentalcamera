@extends('layouts.customer')

@section('title', 'Riwayat Sewa - Sewa Kamera Pro')

@section('page-title', 'Riwayat Sewa')

@section('content')
<div class="space-y-6">
    @if($totalSpending > 0)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 text-center">
                <p class="text-xs text-base-content/60">Total Transaksi</p>
                <p class="text-xl font-medium">{{ $rentalHistory->total() }}</p>
            </div>
        </div>
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 text-center">
                <p class="text-xs text-base-content/60">Total Pengeluaran</p>
                <p class="text-xl font-medium">Rp {{ number_format($totalSpending, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4 text-center">
                <p class="text-xs text-base-content/60">Produk Terpopuler</p>
                <p class="text-xl font-medium">{{ $mostRented->first()->nama_produk ?? '-' }}</p>
            </div>
        </div>
    </div>
    @endif

    @forelse($rentalHistory as $rental)
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body p-4">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div>
                    <p class="text-sm font-medium">{{ $rental->kode_transaksi }}</p>
                    <p class="text-xs text-base-content/40">{{ $rental->completed_at ? $rental->completed_at->format('d M Y') : $rental->created_at->format('d M Y') }}</p>
                </div>
                <span class="badge badge-outline text-[10px]">Selesai</span>
            </div>

            <div class="space-y-2">
                @foreach($rental->detailTransaksis as $detail)
                <div class="flex items-center justify-between py-2 border-b border-base-200 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-base-200 rounded flex items-center justify-center text-xs text-base-content/40">
                            @if($detail->produk && $detail->produk->gambar)
                            <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->nama_produk }}" class="w-full h-full object-cover rounded">
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm">{{ $detail->nama_produk }}</p>
                            <p class="text-xs text-base-content/40">{{ $detail->jumlah }} unit x {{ $detail->lama_sewa ?? $rental->lama_sewa }} hari</p>
                        </div>
                    </div>
                    <p class="text-sm font-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-base-300">
                <div class="text-xs text-base-content/60">
                    <span>{{ $rental->tanggal_pengambilan ? $rental->tanggal_pengambilan->format('d M Y') : '-' }}</span>
                    <span class="mx-1">→</span>
                    <span>{{ $rental->tanggal_pengembalian ? $rental->tanggal_pengembalian->format('d M Y') : '-' }}</span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-base-content/40">Total</p>
                    <p class="text-sm font-semibold">Rp {{ number_format($rental->grand_total, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($rental->ulasan)
            <div class="mt-3 pt-3 border-t border-base-200">
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 {{ $i <= $rental->ulasan->rating ? 'text-base-content' : 'text-base-content/20' }}" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm text-base-content/60">Belum ada riwayat sewa</p>
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline btn-sm mt-3">Sewa Sekarang</a>
        </div>
    </div>
    @endforelse

    <div class="flex justify-center">
        {{ $rentalHistory->links() }}
    </div>
</div>
@endsection
