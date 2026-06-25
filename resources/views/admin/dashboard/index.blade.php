@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Dashboard</h1>
        <div class="join">
            <button type="button" class="btn btn-outline join-item" onclick="refreshDashboard()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh
            </button>
            <div class="dropdown dropdown-end">
                <button class="btn btn-outline join-item" type="button" id="periodDropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bulan Ini
                </button>
                <ul class="dropdown-content menu bg-base-100 rounded-box z-50 shadow-md p-2">
                    <li><a href="#" onclick="setPeriod('day')">Hari Ini</a></li>
                    <li><a href="#" onclick="setPeriod('week')">Minggu Ini</a></li>
                    <li><a href="#" onclick="setPeriod('month')">Bulan Ini</a></li>
                    <li><a href="#" onclick="setPeriod('year')">Tahun Ini</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="card bg-base-100 shadow-md border-l-4 border-l-primary">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-primary uppercase mb-1">Total Pendapatan</div>
                        <div class="text-lg font-bold text-base-content">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                        <div class="mt-2">
                            <small class="text-success">Hari ini: Rp {{ number_format($summary['today_revenue'], 0, ',', '.') }}</small>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-l-4 border-l-success">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-success uppercase mb-1">Total Transaksi</div>
                        <div class="text-lg font-bold text-base-content">{{ number_format($summary['total_orders'], 0, ',', '.') }}</div>
                        <div class="mt-2">
                            <small class="text-info">Hari ini: {{ $summary['today_orders'] }}</small>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-l-4 border-l-info">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-info uppercase mb-1">Total Pelanggan</div>
                        <div class="text-lg font-bold text-base-content">{{ number_format($summary['total_customers'], 0, ',', '.') }}</div>
                        <div class="mt-2">
                            <small class="text-base-content/60">{{ $recentCustomers->count() }} baru</small>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border-l-4 border-l-warning">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-warning uppercase mb-1">Total Produk</div>
                        <div class="text-lg font-bold text-base-content">{{ number_format($summary['total_products'], 0, ',', '.') }}</div>
                        <div class="mt-2">
                            <progress class="progress progress-warning w-full" value="{{ $summary['total_products'] > 0 ? ($summary['available_products'] / $summary['total_products']) * 100 : 0 }}" max="100"></progress>
                            <small class="text-success">{{ $summary['available_products'] }} tersedia</small>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title text-primary">Pendapatan 7 Hari Terakhir</h2>
                    <div class="w-full" style="position: relative; height:300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title text-primary">Metode Pembayaran</h2>
                    <div class="w-full" style="position: relative; height:250px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                    <div class="mt-3 text-sm space-y-1">
                        @foreach($paymentMethods as $method)
                        <div class="flex justify-between">
                            <span>{{ $method->name }}</span>
                            <span>{{ $method->total }} transaksi</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="card-title text-primary">Transaksi Terbaru</h2>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->kode_transaksi }}</strong></td>
                                    <td>
                                        <div>{{ $transaction->nama_customer }}</div>
                                        <div class="text-xs text-base-content/60">{{ $transaction->email_customer }}</div>
                                    </td>
                                    <td><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($transaction->status_pembayaran == 'settlement')
                                            <span class="badge badge-success">Sukses</span>
                                        @elseif($transaction->status_pembayaran == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($transaction->status_pembayaran == 'expire')
                                            <span class="badge badge-ghost">Expired</span>
                                        @else
                                            <span class="badge badge-error">Gagal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-base-content/60">{{ $transaction->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-base-content/60">Belum ada transaksi</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card bg-base-100 shadow-md mb-4">
                <div class="card-body">
                    <h2 class="card-title text-primary">Produk Terlaris</h2>
                    @forelse($topProducts as $product)
                    <div class="flex items-center gap-3 mb-3">
                        <div class="shrink-0">
                            @if($product->gambar_utama)
                                <img src="{{ asset('storage/' . $product->gambar_utama) }}" alt="{{ $product->nama_produk }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="bg-base-200 rounded flex items-center justify-center" style="width: 50px; height: 50px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="grow">
                            <div class="font-bold truncate">{{ $product->nama_produk }}</div>
                            <div class="flex justify-between items-center">
                                <span class="text-primary">Rp {{ number_format($product->harga_per_hari, 0, ',', '.') }}/hari</span>
                                <span class="badge badge-info">{{ $product->jumlah_dipesan }}x</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-base-content/60">Belum ada data produk</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title text-primary">Pelanggan Baru</h2>
                    @forelse($recentCustomers as $customer)
                    <div class="flex items-center gap-3 mb-3">
                        <div class="shrink-0">
                            <div class="bg-primary rounded-full flex items-center justify-center text-primary-content font-bold" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($customer->nama, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $customer->nama }}</div>
                            <div class="text-xs text-base-content/60">{{ $customer->email }}</div>
                            <div class="text-xs text-base-content/60">Bergabung: {{ $customer->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <p class="text-base-content/60">Belum ada pelanggan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title text-primary">Ringkasan Statistik</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-primary">{{ $summary['pending_orders'] }}</div>
                    <div class="text-sm text-base-content/60">Pesanan Pending</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-success">
                        {{ $summary['total_products'] > 0 ? round(($summary['available_products'] / $summary['total_products']) * 100) : 0 }}%
                    </div>
                    <div class="text-sm text-base-content/60">Produk Tersedia</div>
                </div>
                <div>
                    @php
                        $avgOrder = $summary['total_orders'] > 0 ? $summary['total_revenue'] / $summary['total_orders'] : 0;
                    @endphp
                    <div class="text-2xl font-bold text-info">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div>
                    <div class="text-sm text-base-content/60">Rata-rata Transaksi</div>
                </div>
                <div>
                    @php
                        $completionRate = $summary['total_orders'] > 0 
                            ? (($summary['total_orders'] - $summary['pending_orders']) / $summary['total_orders']) * 100 
                            : 0;
                    @endphp
                    <div class="text-2xl font-bold text-warning">{{ round($completionRate) }}%</div>
                    <div class="text-sm text-base-content/60">Tingkat Penyelesaian</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentPeriod = 'month';

document.addEventListener('DOMContentLoaded', function() {
    loadCharts();
    loadSummary();
});

function loadCharts() {
    createRevenueChart();
    createPaymentChart();
}

function createRevenueChart() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = JSON.parse('{!! json_encode($revenueData) !!}');

    const labels = revenueData.map(function(item) {
        return item.date;
    });

    const revenue = revenueData.map(function(item) {
        return item.revenue;
    });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenue,
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderColor: '#4e73df',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + formatNumber(value);
                        }
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + formatNumber(context.parsed.y);
                        }
                    }
                }
            }
        }
    });
}

function createPaymentChart() {
    const ctx = document.getElementById('paymentChart').getContext('2d');
    const paymentData = JSON.parse('{!! json_encode($paymentMethods) !!}');

    const labels = paymentData.map(function(item) {
        return item.name;
    });

    const data = paymentData.map(function(item) {
        return item.total;
    });

    const colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
        '#e74a3b', '#6f42c1', '#fd7e14', '#20c9a6'
    ];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
}

function loadSummary() {
    fetch('/admin/dashboard/summary?period=' + currentPeriod)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Summary loaded:', data.data);
            }
        })
        .catch(error => console.error('Error loading summary:', error));
}

function setPeriod(period) {
    currentPeriod = period;
    const button = document.querySelector('#periodDropdown');
    const periodText = {
        'day': 'Hari Ini',
        'week': 'Minggu Ini',
        'month': 'Bulan Ini',
        'year': 'Tahun Ini'
    };
    button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> ' + periodText[period];
    loadSummary();
}

function refreshDashboard() {
    location.reload();
}

function formatNumber(num) {
    if (!num) return '0';
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

setInterval(loadSummary, 60000);
</script>
@endpush
