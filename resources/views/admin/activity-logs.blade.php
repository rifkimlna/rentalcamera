@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs uppercase text-[#86868b]">
                        <th class="w-10">#</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Tipe</th>
                        <th>IP Address</th>
                        <th class="w-32">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="hover:bg-[#f5f5f7]/50">
                        <td class="text-[#86868b]">{{ $loop->iteration }}</td>
                        <td>
                            <div class="font-medium text-sm">{{ $log->user->nama ?? 'User #' . $log->user_id }}</div>
                            <div class="text-xs text-[#86868b]">{{ $log->user->email ?? '' }}</div>
                        </td>
                        <td>
                            <span class="text-sm">{{ $log->description }}</span>
                            @if($log->data)
                                <button type="button" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors ms-1" onclick="lihatData({{ $log->id }})">Lihat Data</button>
                                <pre id="data-{{ $log->id }}" class="hidden text-xs bg-[#f5f5f7] p-2 rounded mt-1 max-w-md overflow-x-auto">{{ json_encode($log->data, JSON_PRETTY_PRINT) }}</pre>
                            @endif
                        </td>
                        <td>
                            <span class="badge-apple !text-[10px] !px-2 !py-0.5 border border-[#e5e5e7]">{{ $log->type_label ?? $log->type }}</span>
                        </td>
                        <td class="text-xs text-[#86868b]">{{ $log->ip_address ?? '-' }}</td>
                        <td class="text-xs text-[#86868b]">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-[#86868b] py-8">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-4 border-t border-[#f0f0f2]">{{ $logs->links() }}</div>
        @endif
    </div>
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
