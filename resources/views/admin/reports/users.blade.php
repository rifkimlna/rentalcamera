@extends('layouts.admin')

@section('title', 'Laporan Customer')
@section('page-title', 'Laporan Customer')
@section('page-subtitle', $startDate->format('d M Y') . ' — ' . $endDate->format('d M Y'))

@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-1 p-1 bg-[#f5f5f7] rounded-full w-fit border border-[#e5e5e7]/60 overflow-x-auto no-scrollbar max-w-full">
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Ringkasan</a>
        <a href="{{ route('admin.reports.transactions') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Transaksi</a>
        <a href="{{ route('admin.reports.products') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Produk</a>
        <a href="{{ route('admin.reports.users') }}" class="px-4 py-1.5 text-xs font-medium rounded-full bg-[#1d1d1f] text-white shadow-sm whitespace-nowrap">Customer</a>
    </div>

    <form action="{{ route('admin.reports.users') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white border border-[#e5e5e7] rounded-full px-2 py-2 shadow-sm w-fit max-w-full">
        <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
            <x-admin.icon name="calendar" :size="14" color="text-[#86868b]" />
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
        </div>
        <span class="text-xs text-[#86868b] hidden sm:inline">—</span>
        <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
        </div>
        <button type="submit" class="bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition inline-flex items-center gap-1.5"><x-admin.icon name="filter" :size="14"/> Tampilkan</button>
        <a href="{{ route('admin.reports.users') }}" class="bg-[#f5f5f7] text-[#6e6e73] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#e8e8ed] transition">Reset</a>
        <a href="{{ route('admin.reports.export', ['type' => 'customers']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="inline-flex items-center gap-1.5 bg-white border border-[#e5e5e7] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#f5f5f7] transition"><x-admin.icon name="download" :size="14"/> Export</a>
    </form>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Kontak</th>
                        <th class="px-5 py-3 font-medium">Kota</th>
                        <th class="px-5 py-3 font-medium text-right">Trx Periode</th>
                        <th class="px-5 py-3 font-medium text-right">Poin</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($users as $u)
                    <tr class="hover:bg-[#fbfbfd] transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <x-avatar :user="$u" :size="32" />
                                <div>
                                    <div class="text-sm font-medium text-[#1d1d1f]">{{ $u->nama }}</div>
                                    <div class="text-xs text-[#86868b] truncate max-w-[160px]">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs text-[#6e6e73]">{{ $u->telepon ?? '-' }}</td>
                        <td class="px-5 py-3 text-xs text-[#6e6e73]">{{ $u->kota ?? '-' }}</td>
                        <td class="px-5 py-3 text-right"><span class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-full text-xs font-semibold bg-[#1d1d1f] text-white">{{ $u->transaksis_count }}</span></td>
                        <td class="px-5 py-3 text-right text-xs font-medium text-[#1d1d1f]">{{ $u->poin_reward }}</td>
                        <td class="px-5 py-3">
                            @if($u->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                            @elseif($u->status === 'suspended')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-[#d70015] border border-red-100">Ditangguhkan</span>
                            @elseif($u->status === 'pending_verification')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">Verifikasi</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">{{ $u->status }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-[#86868b] whitespace-nowrap">{{ $u->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center"><x-admin.empty title="Tidak ada customer" subtitle="Tidak ada user customer pada periode ini." icon="users" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($users as $u)
                <div class="p-4 flex items-center gap-3">
                    <x-avatar :user="$u" :size="40" />
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $u->nama }}</div>
                        <div class="text-xs text-[#86868b] truncate">{{ $u->email }} • {{ $u->kota ?? '-' }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-[#1d1d1f] text-white font-medium">{{ $u->transaksis_count }} trx</span>
                            <span class="text-xs text-[#6e6e73]">{{ $u->poin_reward }} poin</span>
                        </div>
                    </div>
                    <span class="text-[11px] px-2 py-1 rounded-full border shrink-0 {{ $u->status==='active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($u->status==='suspended' ? 'bg-red-50 text-[#d70015] border-red-100' : 'bg-amber-50 text-amber-700 border-amber-100') }}">{{ $u->status }}</span>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Tidak ada customer" icon="users" /></div>
            @endforelse
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-[#f0f0f2] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-xs text-[#86868b]">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }}</span>
                <div>{{ $users->links() }}</div>
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
