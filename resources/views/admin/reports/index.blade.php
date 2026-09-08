@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Ringkasan pendapatan & performa — ' . $startDate->format('d M Y') . ' — ' . $endDate->format('d M Y'))

@section('content')
<div class="space-y-5">

    {{-- Filter Pill — auto-layout wrap --}}
    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white border border-[#e5e5e7] rounded-full px-2 py-2 shadow-sm w-fit max-w-full">
        <div class="flex items-center gap-1.5 flex-wrap">
            <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
                <x-admin.icon name="calendar" :size="14" color="text-[#86868b]" />
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
            </div>
            <span class="text-xs text-[#86868b] hidden sm:inline">—</span>
            <div class="flex items-center gap-1.5 bg-[#f5f5f7] rounded-full px-2 py-1">
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="bg-transparent border-0 text-xs font-medium text-[#1d1d1f] focus:ring-0 p-0 outline-none">
            </div>
        </div>
        <div class="h-6 w-px bg-[#e5e5e7] hidden sm:block"></div>
        <div class="flex items-center gap-1.5 flex-wrap">
            <button type="submit" class="bg-[#1d1d1f] text-white text-xs font-medium rounded-full px-4 py-2 hover:bg-black transition inline-flex items-center gap-1.5">
                <x-admin.icon name="filter" :size="14" /> Tampilkan
            </button>
            <a href="{{ route('admin.reports.index') }}" class="bg-[#f5f5f7] text-[#6e6e73] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#e8e8ed] transition">Reset</a>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex items-center gap-1.5 bg-white border border-[#e5e5e7] text-xs font-medium rounded-full px-3 py-2 hover:bg-[#f5f5f7] transition">
                    <x-admin.icon name="download" :size="14" /> Export <x-admin.icon name="chevron-down" :size="12" color="text-[#86868b]" />
                </button>
                <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-56 bg-white border border-[#e5e5e7] rounded-[20px] shadow-lg shadow-black/5 p-2 z-50">
                    <div class="px-3 py-1.5 text-[10px] font-semibold tracking-widest text-[#86868b] uppercase">Export Laporan</div>
                    <a href="{{ route('admin.reports.export', ['type' => 'revenue']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-xl hover:bg-[#f5f5f7] text-[#1d1d1f] transition"><span class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-[10px] font-bold">Rp</span> Pendapatan</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'products']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-xl hover:bg-[#f5f5f7] text-[#1d1d1f] transition"><span class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center"><x-admin.icon name="package" :size="14" /></span> Produk</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'customers']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-xl hover:bg-[#f5f5f7] text-[#1d1d1f] transition"><span class="w-7 h-7 rounded-full bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center"><x-admin.icon name="users" :size="14" /></span> Customer</a>
                    <a href="{{ route('admin.reports.export', ['type' => 'transactions']) . '?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-xl hover:bg-[#f5f5f7] text-[#1d1d1f] transition"><span class="w-7 h-7 rounded-full bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center"><x-admin.icon name="transactions" :size="14" /></span> Transaksi</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Pill Nav — selaras --}}
    <div class="flex items-center gap-1 p-1 bg-[#f5f5f7] rounded-full w-fit border border-[#e5e5e7]/60 overflow-x-auto no-scrollbar max-w-full">
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-1.5 text-xs font-medium rounded-full bg-[#1d1d1f] text-white shadow-sm whitespace-nowrap">Ringkasan</a>
        <a href="{{ route('admin.reports.transactions') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Transaksi</a>
        <a href="{{ route('admin.reports.products') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Produk</a>
        <a href="{{ route('admin.reports.users') }}" class="px-4 py-1.5 text-xs font-medium rounded-full text-[#6e6e73] hover:text-[#1d1d1f] hover:bg-white transition whitespace-nowrap">Customer</a>
    </div>

    {{-- Summary — minimalis berwarna (tidak semua hitam) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <x-admin.kpi label="Total Pendapatan" :value="'Rp ' . number_format($summary['total_revenue'], 0, ',', '.')" :hint="number_format($summary['total_orders'], 0, ',', '.') . ' trx • Avg Rp ' . number_format($summary['avg_order_value'], 0, ',', '.')" icon="money" color="emerald" />
        <x-admin.kpi label="Transaksi" :value="number_format($summary['total_orders'], 0, ',', '.')" hint="Periode terpilih" icon="transactions" color="blue" />
        <x-admin.kpi label="Customer Baru" :value="number_format($summary['total_customers'], 0, ',', '.')" hint="User terdaftar periode ini" icon="users" color="violet" />
        <x-admin.kpi label="Produk Disewa" :value="number_format($summary['total_products_rented'], 0, ',', '.')" hint="Total unit keluar" icon="package" color="amber" />
    </div>

    {{-- Chart Utama + Metode Pembayaran --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <div class="lg:col-span-8 bg-white rounded-[24px] border border-[#e5e5e7] p-5">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-[#1d1d1f]">Pendapatan Harian</h2>
                    <p class="text-xs text-[#86868b] mt-0.5">Garis emerald = pendapatan • Titik biru = transaksi</p>
                </div>
                <span class="hidden sm:inline-flex items-center gap-2 text-[11px] bg-[#f5f5f7] border border-[#e5e5e7] rounded-full px-3 py-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Revenue
                    <span class="w-2 h-2 rounded-full bg-blue-500 ml-1"></span> Transaksi
                </span>
            </div>
            <div class="relative h-[280px] sm:h-[300px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
            @if(empty($revenueReport) || $revenueReport->isEmpty())
                <p class="text-xs text-center text-[#86868b] mt-3">Belum ada data pada periode ini.</p>
            @endif
        </div>

        <div class="lg:col-span-4 bg-white rounded-[24px] border border-[#e5e5e7] p-5 flex flex-col">
            <h2 class="text-sm font-semibold text-[#1d1d1f]">Metode Pembayaran</h2>
            <p class="text-xs text-[#86868b] mb-4">Bar berwarna — bukan semua hitam</p>
            <div class="relative h-[180px] w-full flex items-center justify-center">
                <canvas id="paymentChart"></canvas>
            </div>
            <div class="mt-4 space-y-0 max-h-[200px] overflow-auto pr-1 -mr-1">
                @php $payDots = ['bg-emerald-500','bg-blue-500','bg-violet-500','bg-amber-500','bg-slate-400','bg-[#86868b]']; @endphp
                @forelse($paymentMethods as $i => $method)
                    <div class="flex items-center justify-between py-2.5 border-b border-[#f5f5f7] last:border-0 gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full {{ $payDots[$i % count($payDots)] }} shrink-0"></span>
                            <span class="text-xs font-medium text-[#1d1d1f] truncate">{{ $method->name }}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($method->total_amount, 0, ',', '.') }}</div>
                            <div class="text-[11px] text-[#86868b]">{{ number_format($method->total_orders, 0, ',', '.') }} trx</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-center text-[#86868b] py-6">Belum ada data pembayaran.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top 5 + 5 — lebih minimal dari 10 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-[24px] border border-[#e5e5e7] p-5">
            <div class="flex items-center justify-between mb-4 gap-3">
                <h2 class="text-sm font-semibold text-[#1d1d1f]">5 Produk Terlaris</h2>
                <a href="{{ route('admin.reports.products') }}" class="text-xs text-[#6e6e73] hover:text-[#1d1d1f] inline-flex items-center gap-1">Lihat semua <x-admin.icon name="chevron-down" :size="12" /></a>
            </div>
            <div class="space-y-0">
                @forelse($topProducts->take(5) as $idx => $product)
                @php $rankColors = ['bg-emerald-50 text-emerald-700 border-emerald-100','bg-blue-50 text-blue-700 border-blue-100','bg-violet-50 text-violet-700 border-violet-100','bg-amber-50 text-amber-700 border-amber-100','bg-slate-50 text-slate-600 border-[#e5e5e7]']; $rc = $rankColors[$idx % 5]; @endphp
                <div class="flex items-center gap-3 py-3 border-b border-[#f5f5f7] last:border-0">
                    <span class="w-7 h-7 rounded-full border flex items-center justify-center text-[11px] font-bold shrink-0 {{ $rc }}">{{ sprintf('%02d', $idx+1) }}</span>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $product->nama_produk }}</div>
                        <div class="text-[11px] text-[#86868b]">{{ $product->kode_produk }}</div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</div>
                        <div class="inline-flex items-center gap-1 mt-1 bg-[#f5f5f7] border border-[#e5e5e7] rounded-full px-2 py-0.5 text-[11px] font-medium text-[#6e6e73]">{{ number_format($product->total_rented, 0, ',', '.') }}x</div>
                    </div>
                </div>
                @empty
                    <x-admin.empty title="Belum ada produk" subtitle="Belum ada transaksi settlement pada periode ini." icon="package" />
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-[#e5e5e7] p-5">
            <div class="flex items-center justify-between mb-4 gap-3">
                <h2 class="text-sm font-semibold text-[#1d1d1f]">5 Customer Terbaik</h2>
                <a href="{{ route('admin.reports.users') }}" class="text-xs text-[#6e6e73] hover:text-[#1d1d1f] inline-flex items-center gap-1">Lihat semua <x-admin.icon name="chevron-down" :size="12" /></a>
            </div>
            <div class="space-y-0">
                @forelse($topCustomers->take(5) as $idx => $customer)
                <div class="flex items-center gap-3 py-3 border-b border-[#f5f5f7] last:border-0">
                    <x-avatar :user="$customer" :size="32" />
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-[#1d1d1f] truncate">{{ $customer->nama }}</div>
                        <div class="text-[11px] text-[#86868b] truncate">{{ $customer->email }}</div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-xs font-semibold text-[#1d1d1f]">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</div>
                        <div class="text-[11px] text-[#86868b]">{{ number_format($customer->total_orders, 0, ',', '.') }} trx</div>
                    </div>
                </div>
                @empty
                    <x-admin.empty title="Belum ada customer" icon="users" />
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueData = @json($revenueReport);
    const paymentMethods = @json($paymentMethods);
    function formatRp(n){ return 'Rp ' + Number(n||0).toLocaleString('id-ID'); }
    function formatNum(n){ return Number(n||0).toLocaleString('id-ID'); }

    const revCanvas = document.getElementById('revenueChart');
    if (revCanvas && revenueData && revenueData.length > 0) {
        const ctx = revCanvas.getContext('2d');
        const labels = revenueData.map(d => { try{ return new Date(d.date).toLocaleDateString('id-ID',{day:'2-digit', month:'short'});}catch(e){return d.date;} });
        const revenue = revenueData.map(d => Number(d.total_revenue||0));
        const orders = revenueData.map(d => Number(d.total_orders||0));
        const grad = ctx.createLinearGradient(0,0,0,280);
        grad.addColorStop(0,'rgba(16,185,129,0.14)');
        grad.addColorStop(1,'rgba(16,185,129,0)');
        new Chart(ctx, {
            type:'line',
            data:{
                labels,
                datasets:[
                    { label:'Pendapatan', data:revenue, backgroundColor:grad, borderColor:'#10b981', borderWidth:2, tension:0.35, fill:true, pointRadius:0, pointHoverRadius:5, pointHoverBackgroundColor:'#10b981', yAxisID:'y' },
                    { label:'Transaksi', data:orders, borderColor:'#3b82f6', borderWidth:1.6, borderDash:[5,4], tension:0.35, fill:false, pointRadius:0, pointHoverRadius:4, yAxisID:'y1' }
                ]
            },
            options:{
                responsive:true, maintainAspectRatio:false, interaction:{mode:'index', intersect:false},
                scales:{
                    x:{ grid:{display:false}, border:{display:false}, ticks:{color:'#86868b', font:{size:10, family:'Inter'}, maxRotation:0, maxTicksLimit:8} },
                    y:{ position:'left', grid:{color:'#f5f5f7'}, border:{display:false}, ticks:{color:'#86868b', font:{size:10}, callback:v=> v>=1e6 ? 'Rp'+(v/1e6).toFixed(1)+'jt' : v>=1e3 ? 'Rp'+(v/1e3).toFixed(0)+'rb' : 'Rp'+v } },
                    y1:{ position:'right', grid:{display:false}, border:{display:false}, ticks:{color:'#86868b', font:{size:10}, stepSize:1} }
                },
                plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#1d1d1f', titleColor:'#fff', bodyColor:'#fff', padding:10, cornerRadius:12, displayColors:true, callbacks:{ label:ctx=> ctx.datasetIndex===0 ? ' Pendapatan: '+formatRp(ctx.parsed.y) : ' Transaksi: '+formatNum(ctx.parsed.y) } } }
            }
        });
    } else if (revCanvas) {
        const c = revCanvas.getContext('2d'); c.font='12px Inter'; c.fillStyle='#86868b'; c.textAlign='center'; c.fillText('Tidak ada data pendapatan pada periode ini', revCanvas.width/2, 140);
    }

    const payCanvas = document.getElementById('paymentChart');
    if (payCanvas && paymentMethods && paymentMethods.length > 0) {
        const ctx2 = payCanvas.getContext('2d');
        const hasAmount = paymentMethods.some(m=> Number(m.total_amount)>0);
        if (!hasAmount) { ctx2.font='12px Inter'; ctx2.fillStyle='#86868b'; ctx2.textAlign='center'; ctx2.fillText('Belum ada transaksi', payCanvas.width/2, 90); }
        else {
            const labels = paymentMethods.map(m=> m.name);
            const amounts = paymentMethods.map(m=> Number(m.total_amount||0));
            const palette = ['#10b981','#3b82f6','#8b5cf6','#f59e0b','#94a3b8','#d2d2d7'];
            new Chart(ctx2, {
                type:'bar',
                data:{ labels, datasets:[{ data:amounts, backgroundColor: palette.slice(0, amounts.length), borderRadius:8, borderSkipped:false, barThickness:14 }] },
                options:{
                    indexAxis:'y', responsive:true, maintainAspectRatio:false,
                    scales:{ x:{ grid:{color:'#f5f5f7'}, border:{display:false}, ticks:{color:'#86868b', font:{size:10}, callback:v=> v>=1e6 ? (v/1e6).toFixed(1)+'jt' : v>=1e3 ? (v/1e3).toFixed(0)+'rb' : v } }, y:{ grid:{display:false}, border:{display:false}, ticks:{color:'#1d1d1f', font:{size:11, weight:500}} } },
                    plugins:{ legend:{display:false}, tooltip:{ backgroundColor:'#1d1d1f', cornerRadius:10, callbacks:{ label:ctx=> ' '+ctx.label+': '+formatRp(ctx.parsed.x) } } }
                }
            });
        }
    }
});
</script>
@endpush
