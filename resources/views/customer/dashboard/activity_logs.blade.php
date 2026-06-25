@extends('layouts.customer')

@section('title', 'Aktivitas - Sewa Kamera Pro')

@section('page-title', 'Aktivitas')

@push('styles')
<style>
.activity-icon { width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border-radius: 9999px; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="space-y-4">
    @php
        $currentDate = null;
    @endphp

    @forelse($activityLogs as $log)
        @php
            $logDate = $log->created_at->format('Y-m-d');
        @endphp

        @if($currentDate !== $logDate)
            @php $currentDate = $logDate; @endphp
            <div class="text-xs font-medium text-base-content/40 uppercase tracking-wider pt-2 pb-1">
                {{ $log->created_at->isToday() ? 'Hari Ini' : ($log->created_at->isYesterday() ? 'Kemarin' : $log->created_at->format('d F Y')) }}
            </div>
        @endif

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4">
                <div class="flex items-start gap-3">
                    @php
                        $typeIcons = [
                            'login' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>',
                            'logout' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>',
                            'transaction' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                            'payment' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
                            'create' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>',
                            'update' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                            'delete' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>',
                            'review' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>',
                        ];
                        $defaultIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    @endphp
                    <div class="activity-icon bg-base-200 text-base-content/70">
                        {!! $typeIcons[$log->type] ?? $defaultIcon !!}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm">{{ $log->description }}</p>
                            <span class="text-xs text-base-content/40 whitespace-nowrap">{{ $log->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="badge badge-outline badge-xs text-[10px]">{{ $log->type_label }}</span>
                            @if($log->ip_address)
                            <span class="text-[10px] text-base-content/30">{{ $log->ip_address }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    <div class="card bg-base-100 border border-base-300">
        <div class="card-body p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-base-content/60">Belum ada aktivitas</p>
        </div>
    </div>
    @endforelse

    <div class="flex justify-center">
        {{ $activityLogs->links() }}
    </div>
</div>
@endsection
