@extends('layouts.customer')

@section('title', 'Deposit - Sewa Kamera Pro')

@section('page-title', 'Deposit')

@section('content')
<div class="space-y-6">
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body p-6 text-center">
            <p class="text-xs text-base-content/60 uppercase tracking-wider">Saldo Deposit</p>
            <p class="text-3xl font-light mt-1">Rp {{ number_format(auth()->user()->saldo_deposit ?? 0, 0, ',', '.') }}</p>
            <div class="mt-4">
                <button type="button" class="btn btn-neutral btn-sm" onclick="topupModal.showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Top Up
                </button>
            </div>
        </div>
    </div>

    <div>
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ request()->fullUrlWithQuery(['type' => 'all', 'status' => request('status'), 'date_from' => request('date_from'), 'date_to' => request('date_to')]) }}" class="btn btn-sm {{ request('type', 'all') === 'all' ? 'btn-neutral' : 'btn-outline' }}">Semua</a>
            @foreach($types as $key => $label)
                @if($key !== 'all')
                <a href="{{ request()->fullUrlWithQuery(['type' => $key, 'status' => request('status'), 'date_from' => request('date_from'), 'date_to' => request('date_to')]) }}" class="btn btn-sm {{ request('type') === $key ? 'btn-neutral' : 'btn-outline' }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>

        @if($deposits->isNotEmpty())
        <div class="space-y-2">
            @foreach($deposits as $deposit)
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">{{ $deposit->type_label }}</span>
                                <span class="badge badge-outline badge-xs text-[10px]">
                                    @php
                                        $statusLabels = ['pending' => 'Pending', 'success' => 'Sukses', 'failed' => 'Gagal', 'cancelled' => 'Dibatalkan'];
                                    @endphp
                                    {{ $statusLabels[$deposit->status] ?? $deposit->status }}
                                </span>
                            </div>
                            @if($deposit->description)
                            <p class="text-xs text-base-content/40 mt-0.5">{{ $deposit->description }}</p>
                            @endif
                            <p class="text-xs text-base-content/40 mt-1">{{ $deposit->created_at->format('d M Y H:i') }}</p>
                            @if($deposit->kode_transaksi)
                            <p class="text-[10px] text-base-content/30 mt-0.5">{{ $deposit->kode_transaksi }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium {{ $deposit->isPositive() ? 'text-base-content' : 'text-base-content/60' }}">
                                {{ $deposit->amount_formatted }}
                            </p>
                            @if($deposit->current_balance)
                            <p class="text-[10px] text-base-content/30">Saldo: Rp {{ number_format($deposit->current_balance, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center mt-4">
            {{ $deposits->links() }}
        </div>
        @else
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <p class="text-sm text-base-content/60">Belum ada transaksi deposit</p>
            </div>
        </div>
        @endif
    </div>
</div>

<dialog id="topupModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-sm font-medium mb-4">Top Up Deposit</h3>
        <form action="{{ route('customer.deposit.topup') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="label"><span class="label-text">Jumlah Top Up</span></label>
                <div class="join w-full">
                    <span class="join-item btn no-animation text-sm">Rp</span>
                    <input type="number" class="input input-bordered w-full join-item text-sm" name="amount" min="10000" max="5000000" value="100000" required>
                </div>
                <p class="text-xs text-base-content/40 mt-1">Min: Rp 10.000, Maks: Rp 5.000.000</p>
            </div>
            <div class="mb-4">
                <label class="label"><span class="label-text">Metode Pembayaran</span></label>
                <select class="select select-bordered w-full text-sm" name="payment_method" required>
                    <option value="gopay">GoPay</option>
                    <option value="shopeepay">ShopeePay</option>
                    <option value="qris">QRIS</option>
                    <option value="bca">BCA Virtual Account</option>
                    <option value="bni">BNI Virtual Account</option>
                    <option value="mandiri">Mandiri Virtual Account</option>
                </select>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-outline btn-sm" onclick="topupModal.close()">Batal</button>
                <button type="submit" class="btn btn-neutral btn-sm">Lanjutkan Pembayaran</button>
            </div>
        </form>
    </div>
</dialog>
@endsection
