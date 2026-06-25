@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-base-content">Laporan</h1>
    </div>

    <div class="card bg-base-100 shadow-md mb-4">
        <div class="card-body">
            <h2 class="card-title">Filter Laporan</h2>
            <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label for="start_date" class="label"><span class="label-text">Tanggal Mulai</span></label>
                    <input type="date" class="input input-bordered w-full" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div>
                    <label for="end_date" class="label"><span class="label-text">Tanggal Akhir</span></label>
                    <input type="date" class="input input-bordered w-full" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </div>
                <div class="flex items-end justify-end">
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" role="button" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Export Laporan
                        </button>
                        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-1 shadow-md p-2">
                            <li><a href="{{ route('admin.reports.export', ['type' => 'revenue']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Laporan Pendapatan</a></li>
                            <li><a href="{{ route('admin.reports.export', ['type' => 'products']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Laporan Produk</a></li>
                            <li><a href="{{ route('admin.reports.export', ['type' => 'customers']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Laporan Customer</a></li>
                            <li><a href="{{ route('admin.reports.export', ['type' => 'transactions']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Laporan Transaksi</a></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
        <div class="card bg-base-100 shadow-md border-l-4 border-l-primary">
            <div class="card-body">
                <div class="text-xs font-bold text-primary uppercase mb-1">Total Pendapatan</div>
                <div class="text-lg font-bold text-base-content">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md border-l-4 border-l-success">
            <div class="card-body">
                <div class="text-xs font-bold text-success uppercase mb-1">Total Transaksi</div>
                <div class="text-lg font-bold text-base-content">{{ number_format($summary['total_orders'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md border-l-4 border-l-info">
            <div class="card-body">
                <div class="text-xs font-bold text-info uppercase mb-1">Rata-rata Transaksi</div>
                <div class="text-lg font-bold text-base-content">Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md border-l-4 border-l-warning">
            <div class="card-body">
                <div class="text-xs font-bold text-warning uppercase mb-1">Total Customer</div>
                <div class="text-lg font-bold text-base-content">{{ number_format($summary['total_customers'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md border-l-4 border-l-error">
            <div class="card-body">
                <div class="text-xs font-bold text-error uppercase mb-1">Produk Disewa</div>
                <div class="text-lg font-bold text-base-content">{{ number_format($summary['total_products_rented'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md border-l-4 border-l-base-content">
            <div class="card-body">
                <div class="text-xs font-bold text-base-content uppercase mb-1">Periode</div>
                <div class="text-sm font-semibold text-base-content">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title">Grafik Pendapatan Harian</h2>
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <h2 class="card-title">Metode Pembayaran</h2>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Metode</th>
                                    <th class="text-end">Transaksi</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentMethods as $method)
                                <tr>
                                    <td>{{ $method->name }}</td>
                                    <td class="text-end">{{ number_format($method->total_orders, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($method->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title">10 Produk Terlaris</h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Disewa</th>
                                <th class="text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>
                                    <div class="font-bold">{{ $product->nama_produk }}</div>
                                    <small class="text-base-content/60">{{ $product->kode_produk }}</small>
                                </td>
                                <td class="text-end">{{ number_format($product->total_rented, 0, ',', '.') }}x</td>
                                <td class="text-end">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title">10 Customer Terbaik</h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-end">Transaksi</th>
                                <th class="text-end">Total Belanja</th>
                                <th class="text-end">Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $customer)
                            <tr>
                                <td>
                                    <div class="font-bold">{{ $customer->nama }}</div>
                                    <small class="text-base-content/60">{{ $customer->email }}</small>
                                </td>
                                <td class="text-end">{{ number_format($customer->total_orders, 0, ',', '.') }}x</td>
                                <td class="text-end">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                                <td class="text-end">@if($customer->last_order_date){{ \Carbon\Carbon::parse($customer->last_order_date)->format('d M Y') }}@else-@endif</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Trend 6 Bulan Terakhir</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th class="text-end">Total Transaksi</th>
                            <th class="text-end">Total Pendapatan</th>
                            <th class="text-end">Rata-rata Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyTrends as $trend)
                        <tr>
                            <td>{{ $trend['label'] }}</td>
                            <td class="text-end">{{ number_format($trend['total_orders'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($trend['total_revenue'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($trend['average_order_value'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var revenueDataJson = '@json($revenueReport)';
    var revenueData = JSON.parse(revenueDataJson.replace(/&quot;/g, '"'));
    if (revenueData && revenueData.length > 0) {
        var ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        var chartCtx = ctx.getContext('2d');
        var labels = [], revenue = [], orders = [];
        for (var i = 0; i < revenueData.length; i++) {
            var item = revenueData[i];
            if (item && item.date) {
                labels.push(item.date);
                revenue.push(item.total_revenue || 0);
                orders.push(item.total_orders || 0);
            }
        }
        var gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.5)');
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0.05)');
        new window.Chart(chartCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenue,
                    backgroundColor: gradient,
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Jumlah Transaksi',
                    data: orders,
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Pendapatan (Rp)' }, ticks: { callback: function(v) { return 'Rp ' + formatNumber(v); } } },
                    y1: { type: 'linear', display: true, position: 'right', title: { display: true, text: 'Jumlah Transaksi' }, grid: { drawOnChartArea: false } }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.datasetIndex === 0) label += 'Rp ' + formatNumber(context.parsed.y);
                                else label += formatNumber(context.parsed.y) + ' transaksi';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});

function formatNumber(num) {
    if (typeof num !== 'number') num = parseFloat(num) || 0;
    return num.toLocaleString('id-ID');
}
</script>
@endpush
