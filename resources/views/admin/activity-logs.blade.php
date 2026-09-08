@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')
@section('page-subtitle', 'Audit trail semua aksi — auto layout timeline di HP')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-[22px] font-semibold tracking-tight text-[#1d1d1f]">Log Aktivitas</h1>
            <p class="text-xs text-[#86868b] mt-1">Jejak aksi pengguna & sistem — {{ $logs->total() ?? 0 }} entri</p>
        </div>
        <span class="inline-flex items-center gap-1.5 bg-white border border-[#e5e5e7] rounded-full px-3 py-1.5 text-xs font-medium text-[#6e6e73]"><x-admin.icon name="activity" :size="14" /> Terbaru di atas</span>
    </div>

    <x-admin.card padding="p-0 overflow-hidden">
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[#86868b] border-b border-[#f0f0f2] text-xs">
                        <th class="px-5 py-3 font-medium w-10">#</th>
                        <th class="px-5 py-3 font-medium">User</th>
                        <th class="px-5 py-3 font-medium">Aktivitas</th>
                        <th class="px-5 py-3 font-medium">Tipe</th>
                        <th class="px-5 py-3 font-medium">IP</th>
                        <th class="px-5 py-3 font-medium whitespace-nowrap">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f5f5f7]">
                    @forelse($logs as $log)
                    <tr class="hover:bg-[#f5f5f7]/50 transition">
                        <td class="px-5 py-3 text-xs text-[#86868b]">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <x-avatar :user="$log->user" :size="32" />
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-[#1d1d1f] truncate max-w-[140px]">{{ $log->user->nama ?? 'User #' . $log->user_id }}</div>
                                    <div class="text-xs text-[#86868b] truncate max-w-[160px]">{{ $log->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-sm text-[#1d1d1f]">{{ $log->description }}</span>
                            @if($log->data)
                                <button type="button" class="ml-1 inline-flex items-center gap-1 bg-white border border-[#e5e5e7] rounded-full px-2 py-0.5 text-[11px] font-medium text-[#6e6e73] hover:bg-[#f5f5f7] transition" onclick="lihatData({{ $log->id }})"><x-admin.icon name="eye" :size="12" /> Data</button>
                                <pre id="data-{{ $log->id }}" class="hidden text-xs bg-[#f5f5f7] border border-[#e5e5e7] rounded-[12px] p-3 mt-2 max-w-[320px] sm:max-w-md overflow-auto">{{ json_encode($log->data, JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        </td>
                        <td class="px-5 py-3"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-violet-50 text-violet-700 border border-violet-100">{{ $log->type_label ?? $log->type }}</span></td>
                        <td class="px-5 py-3 text-xs font-mono text-[#6e6e73]">{{ $log->ip_address ?? '-' }}</td>
                        <td class="px-5 py-3 text-xs text-[#86868b] whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center"><x-admin.empty title="Belum ada aktivitas" icon="activity" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sm:hidden divide-y divide-[#f5f5f7]">
            @forelse($logs as $log)
                <div class="p-4 flex gap-3">
                    <x-avatar :user="$log->user" :size="32" />
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f]">{{ $log->user->nama ?? 'User #' . $log->user_id }}</div>
                        <div class="text-xs text-[#86868b]">{{ $log->created_at->format('d M Y H:i') }} • {{ $log->ip_address ?? '-' }}</div>
                        <div class="text-sm text-[#1d1d1f] mt-1">{{ $log->description }}</div>
                        <span class="inline-flex mt-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-violet-50 text-violet-700 border border-violet-100">{{ $log->type_label ?? $log->type }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6"><x-admin.empty title="Belum ada aktivitas" icon="activity" /></div>
            @endforelse
        </div>

        @if($logs->hasPages())
        <div class="px-5 py-4 border-t border-[#f0f0f2]">{{ $logs->links() }}</div>
        @endif
    </x-admin.card>
</div>

@push('scripts')
<script>
    function lihatData(id) {
        const el = document.getElementById('data-' + id);
        el.classList.toggle('hidden');
    }
</script>
@endpush
@endsection
