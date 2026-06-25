<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksis;
use App\Models\Produk;
use App\Models\User;
use App\Models\DetailTransaksis;
use App\Models\Ulasan;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: last 30 days
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();
            
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();
        
        // Total summary
        $summary = $this->getSummary($startDate, $endDate);
        
        // Revenue report (daily)
        $revenueReport = $this->getRevenueReport($startDate, $endDate);
        
        // Top products
        $topProducts = $this->getTopProducts($startDate, $endDate, 10);
        
        // Top customers
        $topCustomers = $this->getTopCustomers($startDate, $endDate, 10);
        
        // Payment method distribution
        $paymentMethods = $this->getPaymentMethodsDistribution($startDate, $endDate);
        
        // Monthly trends (last 6 months)
        $monthlyTrends = $this->getMonthlyTrends(6);
        
        return view('admin.reports.index', compact(
            'summary',
            'revenueReport',
            'topProducts',
            'topCustomers',
            'paymentMethods',
            'monthlyTrends',
            'startDate',
            'endDate'
        ));
    }
    
    private function getSummary($startDate, $endDate)
    {
        // Total revenue
        $totalRevenue = Transaksis::whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'settlement')
            ->sum('grand_total');
        
        // Total orders
        $totalOrders = Transaksis::whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'settlement')
            ->count();
        
        // Average order value
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Total customers
        $totalCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Total products rented
        $totalProductsRented = DetailTransaksis::whereHas('transaksi', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status_pembayaran', 'settlement');
            })
            ->sum('jumlah');
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'avg_order_value' => $avgOrderValue,
            'total_customers' => $totalCustomers,
            'total_products_rented' => $totalProductsRented,
        ];
    }
    
    private function getRevenueReport($startDate, $endDate)
    {
        return Transaksis::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(grand_total) as total_revenue'),
                DB::raw('AVG(grand_total) as average_order_value')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'settlement')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'total_orders' => (int) $item->total_orders,
                    'total_revenue' => (float) $item->total_revenue,
                    'average_order_value' => (float) $item->average_order_value,
                ];
            });
    }
    
    private function getTopProducts($startDate, $endDate, $limit = 10)
    {
        return Produk::select(
                'produk.id',
                'produk.nama_produk',
                'produk.kode_produk',
                DB::raw('COALESCE(SUM(dt.jumlah), 0) as total_rented'),
                DB::raw('COALESCE(SUM(dt.subtotal), 0) as total_revenue')
            )
            ->leftJoin('detail_transaksi as dt', 'produk.id', '=', 'dt.produk_id')
            ->leftJoin('transaksis as t', function($join) use ($startDate, $endDate) {
                $join->on('dt.transaksi_id', '=', 't.id')
                    ->whereBetween('t.created_at', [$startDate, $endDate])
                    ->where('t.status_pembayaran', 'settlement');
            })
            ->groupBy('produk.id', 'produk.nama_produk', 'produk.kode_produk')
            ->orderBy('total_revenue', 'desc')
            ->orderBy('total_rented', 'desc')
            ->limit($limit)
            ->get();
    }
    
    private function getTopCustomers($startDate, $endDate, $limit = 10)
    {
        return User::select(
                'users.id',
                'users.nama',
                'users.email',
                DB::raw('COUNT(t.id) as total_orders'),
                DB::raw('COALESCE(SUM(t.grand_total), 0) as total_spent'),
                DB::raw('MAX(t.created_at) as last_order_date')
            )
            ->where('users.role', 'customer')
            ->leftJoin('transaksis as t', function($join) use ($startDate, $endDate) {
                $join->on('users.id', '=', 't.user_id')
                    ->whereBetween('t.created_at', [$startDate, $endDate])
                    ->where('t.status_pembayaran', 'settlement');
            })
            ->groupBy('users.id', 'users.nama', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->orderBy('total_orders', 'desc')
            ->limit($limit)
            ->get();
    }
    
    private function getPaymentMethodsDistribution($startDate, $endDate)
    {
        return PaymentMethod::select(
                'payment_methods.id',
                'payment_methods.name',
                'payment_methods.code',
                DB::raw('COUNT(t.id) as total_orders'),
                DB::raw('COALESCE(SUM(t.grand_total), 0) as total_amount')
            )
            ->leftJoin('transaksis as t', function($join) use ($startDate, $endDate) {
                $join->on('payment_methods.id', '=', 't.payment_method_id')
                    ->whereBetween('t.created_at', [$startDate, $endDate])
                    ->where('t.status_pembayaran', 'settlement');
            })
            ->where('payment_methods.is_active', 1)
            ->groupBy('payment_methods.id', 'payment_methods.name', 'payment_methods.code')
            ->orderBy('total_amount', 'desc')
            ->get();
    }
    
    private function getMonthlyTrends($months = 6)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();
        
        $results = Transaksis::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(grand_total) as total_revenue'),
                DB::raw('AVG(grand_total) as average_order_value')
            )
            ->where('status_pembayaran', 'settlement')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Fill missing months with zero values
        $trends = [];
        $current = clone $startDate;
        
        while ($current <= $endDate) {
            $year = $current->year;
            $month = $current->month;
            $key = "{$year}-{$month}";
            
            $found = $results->first(function($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            
            $trends[] = [
                'label' => $current->format('M Y'),
                'total_orders' => $found ? (int) $found->total_orders : 0,
                'total_revenue' => $found ? (float) $found->total_revenue : 0,
                'average_order_value' => $found ? (float) $found->average_order_value : 0,
            ];
            
            $current->addMonth();
        }
        
        return $trends;
    }
    
    public function transactionReport(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();
            
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();
        
        $transactions = Transaksis::with(['paymentMethod', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.reports.transactions', compact('transactions', 'startDate', 'endDate'));
    }
    
    public function productReport(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();
            
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();
        
        $products = Produk::with(['kategori', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.reports.products', compact('products', 'startDate', 'endDate'));
    }
    
    public function userReport(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();
            
        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();
        
        $users = User::withCount(['transactions' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status_pembayaran', 'settlement');
            }])
            ->where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.reports.users', compact('users', 'startDate', 'endDate'));
    }
    
    public function exportReport(Request $request, $type)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        switch ($type) {
            case 'revenue':
                return $this->exportRevenueReport($startDate, $endDate);
            case 'products':
                return $this->exportProductReport($startDate, $endDate);
            case 'customers':
                return $this->exportCustomerReport($startDate, $endDate);
            case 'transactions':
                return $this->exportTransactionReport($startDate, $endDate);
            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid.');
        }
    }
    
    private function exportRevenueReport($startDate, $endDate)
    {
        $data = Transaksis::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(grand_total) as total_pendapatan'),
                DB::raw('AVG(grand_total) as rata_rata_transaksi')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'settlement')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'asc')
            ->get();
        
        $filename = "laporan_pendapatan_{$startDate}_to_{$endDate}.csv";
        
        return $this->generateCSV($filename, [
            'Tanggal', 'Jumlah Transaksi', 'Total Pendapatan', 'Rata-rata Transaksi'
        ], $data);
    }
    
    private function exportProductReport($startDate, $endDate)
    {
        $data = Produk::select(
                'produk.kode_produk',
                'produk.nama_produk',
                'produk.harga_per_hari',
                'produk.stok_tersedia',
                'produk.jumlah_dipesan',
                DB::raw('COALESCE(SUM(dt.jumlah), 0) as total_disewa'),
                DB::raw('COALESCE(SUM(dt.subtotal), 0) as total_pendapatan')
            )
            ->leftJoin('detail_transaksi as dt', 'produk.id', '=', 'dt.produk_id')
            ->leftJoin('transaksis as t', function($join) use ($startDate, $endDate) {
                $join->on('dt.transaksi_id', '=', 't.id')
                    ->whereBetween('t.created_at', [$startDate, $endDate])
                    ->where('t.status_pembayaran', 'settlement');
            })
            ->groupBy('produk.id', 'produk.kode_produk', 'produk.nama_produk', 'produk.harga_per_hari', 'produk.stok_tersedia', 'produk.jumlah_dipesan')
            ->orderBy('total_pendapatan', 'desc')
            ->get();
        
        $filename = "laporan_produk_{$startDate}_to_{$endDate}.csv";
        
        return $this->generateCSV($filename, [
            'Kode Produk', 'Nama Produk', 'Harga per Hari', 'Stok Tersedia', 'Total Disewa', 'Total Pendapatan'
        ], $data);
    }
    
    private function exportCustomerReport($startDate, $endDate)
    {
        $data = User::select(
                'users.nama',
                'users.email',
                'users.telepon',
                DB::raw('COUNT(t.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(t.grand_total), 0) as total_pengeluaran')
            )
            ->where('users.role', 'customer')
            ->leftJoin('transaksis as t', function($join) use ($startDate, $endDate) {
                $join->on('users.id', '=', 't.user_id')
                    ->whereBetween('t.created_at', [$startDate, $endDate])
                    ->where('t.status_pembayaran', 'settlement');
            })
            ->groupBy('users.id', 'users.nama', 'users.email', 'users.telepon')
            ->orderBy('total_pengeluaran', 'desc')
            ->get();
        
        $filename = "laporan_customer_{$startDate}_to_{$endDate}.csv";
        
        return $this->generateCSV($filename, [
            'Nama', 'Email', 'Telepon', 'Total Transaksi', 'Total Pengeluaran'
        ], $data);
    }
    
    private function exportTransactionReport($startDate, $endDate)
    {
        $data = Transaksis::with('paymentMethod')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'settlement')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = "laporan_transaksi_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'Kode Transaksi',
                'Tanggal',
                'Customer',
                'Email',
                'Telepon',
                'Total',
                'Status',
                'Metode Pembayaran',
                'Tanggal Bayar'
            ], ';');
            
            foreach ($data as $transaction) {
                fputcsv($file, [
                    $transaction->kode_transaksi,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->nama_customer,
                    $transaction->email_customer,
                    $transaction->telepon_customer,
                    'Rp ' . number_format($transaction->grand_total, 0, ',', '.'),
                    $this->getStatusLabel($transaction->status_transaksi),
                    $transaction->paymentMethod ? $transaction->paymentMethod->name : '-',
                    $transaction->paid_at ? $transaction->paid_at->format('Y-m-d H:i:s') : '-'
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Diproses',
            'dikonfirmasi' => 'Dikonfirmasi',
            'dikemas' => 'Dikemas',
            'dikirim' => 'Dikirim',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'ditolak' => 'Ditolak',
        ];
        
        return $labels[$status] ?? $status;
    }
    
    private function generateCSV($filename, $headers, $data)
    {
        $callback = function() use ($headers, $data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $headers, ';');
            
            foreach ($data as $row) {
                $values = [];
                foreach ($row->toArray() as $key => $value) {
                    $values[] = $value;
                }
                fputcsv($file, $values, ';');
            }
            
            fclose($file);
        };
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function dashboardWidgets()
    {
        $today = Carbon::today();
        
        $widgets = [
            'today_revenue' => Transaksis::whereDate('created_at', $today)
                ->where('status_pembayaran', 'settlement')
                ->sum('grand_total'),
            'today_orders' => Transaksis::whereDate('created_at', $today)->count(),
            'pending_orders' => Transaksis::where('status_transaksi', 'menunggu_pembayaran')->count(),
            'active_customers' => User::where('role', 'customer')
                ->where('status', 'active')
                ->count(),
            'available_products' => Produk::where('status', 'available')
                ->where('stok_tersedia', '>', 0)
                ->count(),
            'maintenance_products' => Produk::where('status', 'maintenance')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $widgets
        ]);
    }
}