@extends('layouts.admin')

@section('title', 'Manajemen Voucher')
@section('page-title', 'Manajemen Voucher')
@section('page-subtitle', 'Kode, kuota & periode • Ikon selaras')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Voucher</h1>
            <p class="text-xs text-[#86868b] mt-1">Kelola diskon persentase & nominal</p>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="inline-flex items-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition shrink-0"><x-admin.icon name="plus" :size="14" /> Tambah Voucher</a>
    </div>

    <x-admin.card>
        <form method="GET" action="{{ route('admin.vouchers.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Cari Voucher</label>
                <div class="flex items-center gap-2 bg-[#f5f5f7] border border-transparent rounded-full px-3 py-2 focus-within:bg-white focus-within:border-[#1d1d1f] transition">
                    <x-admin.icon name="search" :size="14" color="text-[#86868b]" />
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau nama..." class="bg-transparent border-0 p-0 text-xs w-full focus:ring-0 outline-none placeholder:text-[#86868b]">
                </div>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Tipe</label>
                <select name="type" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua Tipe</option>
                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Persentase</option>
                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Nominal</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] font-semibold tracking-wide uppercase text-[#86868b] mb-1.5 block">Status</label>
                <select name="status_filter" class="select-apple-sm w-full !rounded-full">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="expired" {{ request('status_filter') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                    <option value="upcoming" {{ request('status_filter') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2.5 hover:bg-black transition"><x-admin.icon name="filter" :size="14" /> Filter</button>
                <a href="{{ route('admin.vouchers.index') }}" class="bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73] rounded-full p-2.5 hover:bg-[#e8e8ed] transition"><x-admin.icon name="x" :size="16" /></a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-4 py-3 font-medium">#</th>
                        <th class="px-4 py-3 font-medium">Kode</th>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Tipe</th>
                        <th class="px-4 py-3 font-medium">Nilai</th>
                        <th class="px-4 py-3 font-medium text-center">Kuota</th>
                        <th class="px-4 py-3 font-medium">Periode</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($vouchers as $voucher)
                        <tr class="hover:bg-[#f5f5f7]/50 transition">
                            <td class="px-4 py-3 text-xs text-[#86868b]">{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}</td>
                            <td class="px-4 py-3 font-mono text-xs font-bold text-[#1d1d1f]">{{ $voucher->kode_voucher }}</td>
                            <td class="px-4 py-3 text-sm text-[#1d1d1f] truncate max-w-[160px]">{{ $voucher->nama_voucher }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $voucher->type === 'percentage' ? 'bg-violet-50 text-violet-700 border-violet-100' : 'bg-blue-50 text-blue-700 border-blue-100' }}">{{ $voucher->type_label }}</span></td>
                            <td class="px-4 py-3 text-xs font-semibold text-[#1d1d1f]">{{ $voucher->value_formatted }}</td>
                            <td class="px-4 py-3 text-center text-xs"><span class="font-medium text-[#1d1d1f]">{{ $voucher->kuota ?? '∞' }}</span><span class="text-[#86868b]"> / {{ $voucher->kuota_terpakai }} pakai</span></td>
                            <td class="px-4 py-3 text-xs text-[#6e6e73] whitespace-nowrap">{{ Carbon\Carbon::parse($voucher->start_date)->format('d/m/y') }} — {{ Carbon\Carbon::parse($voucher->end_date)->format('d/m/y') }}</td>
                            <td class="px-4 py-3">
                                @php $isExpired = now()->gt($voucher->end_date); $isUpcoming = now()->lt($voucher->start_date); @endphp
                                @if(!$voucher->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#f5f5f7] border border-[#e5e5e7] text-[#6e6e73]">Nonaktif</span>
                                @elseif($isExpired)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-[#d70015] border border-red-100">Kedaluwarsa</span>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">Akan Datang</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <label class="relative inline-flex items-center cursor-pointer" title="Aktif/Nonaktif">
                                        <input type="checkbox" class="sr-only peer toggle-status" data-url="{{ route('admin.vouchers.toggle-active', $voucher->id) }}" {{ $voucher->is_active ? 'checked' : '' }}>
                                        <span class="w-9 h-5 bg-[#e5e5e7] peer-focus:outline-none rounded-full peer peer-checked:bg-[#1d1d1f] transition relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></span>
                                    </label>
                                    <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="w-8 h-8 rounded-full bg-white border border-[#e5e5e7] flex items-center justify-center text-[#6e6e73] hover:bg-[#f5f5f7] transition"><x-admin.icon name="eye" :size="14" /></a>
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="w-8 h-8 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700 hover:bg-amber-100 transition"><x-admin.icon name="edit" :size="14" /></a>
                                    <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher->id) }}" onsubmit="return confirm('Hapus voucher {{ $voucher->kode_voucher }}?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-[#d70015] hover:bg-red-100 transition"><x-admin.icon name="trash" :size="14" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada voucher" subtitle="Buat voucher pertama untuk promo." icon="voucher" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden divide-y divide-[#f5f5f7]">
            @forelse($vouchers as $voucher)
                <div class="p-4 flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-bold text-[#1d1d1f]">{{ $voucher->kode_voucher }}</span>
                        @php $isExpired = now()->gt($voucher->end_date); $isUpcoming = now()->lt($voucher->start_date); @endphp
                        @if(!$voucher->is_active)<span class="text-[11px] px-2 py-0.5 rounded-full bg-[#f5f5f7] border border-[#e5e5e7]">Nonaktif</span>
                        @elseif($isExpired)<span class="text-[11px] px-2 py-0.5 rounded-full bg-red-50 text-[#d70015] border border-red-100">Kedaluwarsa</span>
                        @elseif($isUpcoming)<span class="text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border-amber-100">Akan Datang</span>
                        @else<span class="text-[11px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>@endif
                    </div>
                    <div class="text-sm font-medium text-[#1d1d1f]">{{ $voucher->nama_voucher }}</div>
                    <div class="text-xs text-[#6e6e73]">{{ $voucher->value_formatted }} • {{ $voucher->type_label }} • {{ $voucher->kuota_terpakai }}/{{ $voucher->kuota ?? '∞' }}</div>
                    <div class="text-xs text-[#86868b]">{{ Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y') }} — {{ Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') }}</div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada voucher" icon="voucher" /></div>
            @endforelse
        </div>

        @if($vouchers->hasPages())
            <div class="px-5 py-4 border-t border-[#f0f0f2]">{{ $vouchers->links() }}</div>
        @endif
    </x-admin.card>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-status').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const url = this.dataset.url;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Content-Type': 'application/json' } })
            .then(r => r.json()).then(d => { if (!d.success) checkbox.checked = !checkbox.checked; })
            .catch(() => { checkbox.checked = !checkbox.checked; });
        });
    });
});
</script>
@endpush
@endsection
