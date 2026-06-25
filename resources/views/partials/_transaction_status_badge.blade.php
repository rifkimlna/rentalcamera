@php
    $statusColors = [
        'pending' => 'warning',
        'settlement' => 'success',
        'capture' => 'success',
        'deny' => 'error',
        'cancel' => 'error',
        'expire' => 'neutral',
        'failure' => 'error',
        'refund' => 'info',
        'partial_refund' => 'info',
        'chargeback' => 'neutral'
    ];
    
    $statusLabels = [
        'pending' => 'Menunggu Pembayaran',
        'settlement' => 'Lunas',
        'capture' => 'Terkonfirmasi',
        'deny' => 'Ditolak',
        'cancel' => 'Dibatalkan',
        'expire' => 'Kadaluarsa',
        'failure' => 'Gagal',
        'refund' => 'Dikembalikan',
        'partial_refund' => 'Pengembalian Sebagian',
        'chargeback' => 'Chargeback'
    ];
@endphp

<span class="badge badge-{{ $statusColors[$status] ?? 'neutral' }}">
    {{ $statusLabels[$status] ?? $status }}
</span>
