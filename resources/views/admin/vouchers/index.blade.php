@extends('layouts.admin')

@section('title', 'Manajemen Voucher - Stekpro Multimedia & Broadcast')
@section('page-title', 'Manajemen Voucher')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#1d1d1f]">Manajemen Voucher</h1>
        <div>
            <a href="{{ route('admin.vouchers.create') }}" class="btn-dark-apple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Voucher
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2] mb-4">
        <div class="p-5">
            <form method="GET" action="{{ route('admin.vouchers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Cari Voucher</span></label>
                    <input type="text" class="input-apple w-full" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Kode atau nama voucher...">
                </div>
                <div>
                    <label for="type" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Tipe</span></label>
                    <select class="select-apple w-full" id="type" name="type">
                        <option value="">Semua Tipe</option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Persentase</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Nominal</option>

                    </select>
                </div>
                <div>
                    <label for="status_filter" class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                    <select class="select-apple w-full" id="status_filter" name="status_filter">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="expired" {{ request('status_filter') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
                        <option value="upcoming" {{ request('status_filter') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-dark-apple grow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl px-3 py-2 text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#f0f0f2]">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Kuota</th>
                            <th>Terpakai</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td>{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}</td>
                                <td>
                                    <span class="font-mono font-bold">{{ $voucher->kode_voucher }}</span>
                                </td>
                                <td>{{ $voucher->nama_voucher }}</td>
                                <td>{{ $voucher->type_label }}</td>
                                <td>{{ $voucher->value_formatted }}</td>
                                <td>{{ $voucher->kuota ?? '&infin;' }}</td>
                                <td>{{ $voucher->kuota_terpakai }}</td>
                                <td class="text-xs">
                                    <div>{{ Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y') }}</div>
                                    <div class="text-[#86868b]">s.d.</div>
                                    <div>{{ Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    @php
                                        $isExpired = now()->gt($voucher->end_date);
                                        $isUpcoming = now()->lt($voucher->start_date);
                                    @endphp
                                    @if(!$voucher->is_active)
                                        <span class="badge-apple">Nonaktif</span>
                                    @elseif($isExpired)
                                        <span class="badge-error">Kedaluwarsa</span>
                                    @elseif($isUpcoming)
                                        <span class="badge-warning">Akan Datang</span>
                                    @else
                                        <span class="badge-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <label class="relative cursor-pointer" title="Aktif/Nonaktifkan">
                                            <input type="checkbox"
                                                   class="toggle-status"
                                                   data-url="{{ route('admin.vouchers.toggle-active', $voucher->id) }}"
                                                   {{ $voucher->is_active ? 'checked' : '' }} />
                                        </label>
                                        <a href="{{ route('admin.vouchers.show', $voucher->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher->id) }}"
                                              onsubmit="return confirm('Hapus voucher {{ $voucher->kode_voucher }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-8 text-[#6e6e73]">
                                    Belum ada voucher. <a href="{{ route('admin.vouchers.create') }}" class="link link-primary">Buat voucher baru</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($vouchers->hasPages())
                <div class="mt-4">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-status').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const url = this.dataset.url;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Content-Type': 'application/json',
                },
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data.success) {
                    checkbox.checked = !checkbox.checked;
                }
            })
            .catch(function() {
                checkbox.checked = !checkbox.checked;
            });
        });
    });
});
</script>
@endpush
@endsection
