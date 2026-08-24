@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-lg font-semibold">Laporan Transaksi</h1>
        <form action="{{ route('admin.reports.transactions') }}" method="GET" class="flex items-center gap-2">
            <input type="date" class="input-apple input-xs w-36" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <span class="text-xs text-[#86868b]">s/d</span>
            <input type="date" class="input-apple input-xs w-36" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Tampilkan</button>
            <a href="{{ route('admin.reports.transactions') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Reset</a>
            <a href="{{ route('admin.reports.export', ['type' => 'transactions']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors btn-outline">Export</a>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="pb-2 font-medium">Kode</th>
                        <th class="pb-2 font-medium">Tanggal</th>
                        <th class="pb-2 font-medium">Customer</th>
                        <th class="pb-2 font-medium">Metode</th>
                        <th class="pb-2 font-medium text-right">Total</th>
                        <th class="pb-2 font-medium">Status</th>
                        <th class="pb-2 font-medium text-right">Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-medium">{{ $transaction->kode_transaksi }}</td>
                        <td class="py-2">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td class="py-2">
                            <div class="font-medium">{{ $transaction->nama_customer }}</div>
                            <div class="text-gray-400">{{ $transaction->email_customer }}</div>
                        </td>
                        <td class="py-2">{{ $transaction->paymentMethod ? $transaction->paymentMethod->name : '-' }}</td>
                        <td class="py-2 text-right font-medium">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        <td class="py-2">{{ $transaction->status_transaksi }}</td>
                        <td class="py-2 text-right">@if($transaction->paid_at){{ $transaction->paid_at->format('d M Y H:i') }}@else-@endif</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
