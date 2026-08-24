@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h1 class="text-lg font-semibold">Laporan</h1>
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2">
            <input type="date" class="input-apple input-xs w-36" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <span class="text-xs text-[#86868b]">s/d</span>
            <input type="date" class="input-apple input-xs w-36" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Tampilkan</button>
            <a href="{{ route('admin.reports.index') }}" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors">Reset</a>
            <div class="relative">
                <button tabindex="0" role="button" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors btn-outline">Export</button>
                <ul tabindex="0" class="absolute right-0 z-10 mt-1 space-y-1 bg-white  z-[1]  p-1.5 text-xs w-44">
                    <li><a href="{{ route('admin.reports.export', ['type' => 'revenue']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Pendapatan</a></li>
                    <li><a href="{{ route('admin.reports.export', ['type' => 'products']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Produk</a></li>
                    <li><a href="{{ route('admin.reports.export', ['type' => 'customers']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Customer</a></li>
                    <li><a href="{{ route('admin.reports.export', ['type' => 'transactions']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}">Transaksi</a></li>
                </ul>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-3">
            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Total Pendapatan</div>
            <div class="text-base font-semibold">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3">
            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Total Transaksi</div>
            <div class="text-base font-semibold">{{ number_format($summary['total_orders'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3">
            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Rata-rata Transaksi</div>
            <div class="text-base font-semibold">Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3">
            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Total Customer</div>
            <div class="text-base font-semibold">{{ number_format($summary['total_customers'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3">
            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Produk Disewa</div>
            <div class="text-base font-semibold">{{ number_format($summary['total_products_rented'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3">
            <div class="text-[11px] font-medium text-gray-500 uppercase tracking-wide mb-0.5">Periode</div>
            <div class="text-xs font-medium">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 p-4">
            <h2 class="text-sm font-semibold mb-3">Grafik Pendapatan Harian</h2>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h2 class="text-sm font-semibold mb-3">Metode Pembayaran</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="pb-2 font-medium">Metode</th>
                            <th class="pb-2 font-medium text-right">Transaksi</th>
                            <th class="pb-2 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $method)
                        <tr class="border-b border-gray-50">
                            <td class="py-2">{{ $method->name }}</td>
                            <td class="py-2 text-right">{{ number_format($method->total_orders, 0, ',', '.') }}</td>
                            <td class="py-2 text-right font-medium">Rp {{ number_format($method->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h2 class="text-sm font-semibold mb-3">10 Produk Terlaris</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="pb-2 font-medium">Produk</th>
                            <th class="pb-2 font-medium text-right">Disewa</th>
                            <th class="pb-2 font-medium text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        <tr class="border-b border-gray-50">
                            <td class="py-2">
                                <div class="font-medium">{{ $product->nama_produk }}</div>
                                <div class="text-gray-400">{{ $product->kode_produk }}</div>
                            </td>
                            <td class="py-2 text-right">{{ number_format($product->total_rented, 0, ',', '.') }}x</td>
                            <td class="py-2 text-right font-medium">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h2 class="text-sm font-semibold mb-3">10 Customer Terbaik</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="pb-2 font-medium">Customer</th>
                            <th class="pb-2 font-medium text-right">Transaksi</th>
                            <th class="pb-2 font-medium text-right">Total</th>
                            <th class="pb-2 font-medium text-right">Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomers as $customer)
                        <tr class="border-b border-gray-50">
                            <td class="py-2">
                                <div class="font-medium">{{ $customer->nama }}</div>
                                <div class="text-gray-400">{{ $customer->email }}</div>
                            </td>
                            <td class="py-2 text-right">{{ number_format($customer->total_orders, 0, ',', '.') }}x</td>
                            <td class="py-2 text-right font-medium">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                            <td class="py-2 text-right text-gray-500">@if($customer->last_order_date){{ \Carbon\Carbon::parse($customer->last_order_date)->format('d M Y') }}@else-@endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h2 class="text-sm font-semibold mb-3">Trend 6 Bulan Terakhir</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="pb-2 font-medium">Bulan</th>
                        <th class="pb-2 font-medium text-right">Transaksi</th>
                        <th class="pb-2 font-medium text-right">Pendapatan</th>
                        <th class="pb-2 font-medium text-right">Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyTrends as $trend)
                    <tr class="border-b border-gray-50">
                        <td class="py-2">{{ $trend['label'] }}</td>
                        <td class="py-2 text-right">{{ number_format($trend['total_orders'], 0, ',', '.') }}</td>
                        <td class="py-2 text-right font-medium">Rp {{ number_format($trend['total_revenue'], 0, ',', '.') }}</td>
                        <td class="py-2 text-right">Rp {{ number_format($trend['average_order_value'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        var gradient = chartCtx.createLinearGradient(0, 0, 0, 180);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.3)');
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0.02)');
        new window.Chart(chartCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenue,
                    backgroundColor: gradient,
                    borderColor: 'rgba(78, 115, 223, 0.8)',
                    borderWidth: 1.5,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                    yAxisID: 'y'
                }, {
                    label: 'Transaksi',
                    data: orders,
                    borderColor: 'rgba(28, 200, 138, 0.8)',
                    borderWidth: 1.5,
                    tension: 0.3,
                    fill: false,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { type: 'linear', display: true, position: 'left', ticks: { font: { size: 9 }, callback: function(v) { if (v >= 1000000) return 'Rp' + (v/1000000).toFixed(1) + 'jt'; if (v >= 1000) return 'Rp' + (v/1000).toFixed(0) + 'rb'; return 'Rp' + v; } } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { size: 9 }, stepSize: 1 } }
                },
                plugins: {
                    legend: { labels: { boxWidth: 10, padding: 8, font: { size: 10 } } },
                    : {
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
