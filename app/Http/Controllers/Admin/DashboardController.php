<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksis;
use App\Models\Produk;
use App\Models\User;
use App\Models\Brand;
use App\Models\KategoriProduk;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        // Today's date
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Summary statistics
        $summary = [
            'total_revenue' => Transaksis::where('status_pembayaran', 'settlement')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('grand_total'),
            
            'total_orders' => Transaksis::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            
            'pending_orders' => Transaksis::where('status_pembayaran', 'pending')->count(),
            
            'total_customers' => User::where('role', 'customer')->count(),
            
            'total_products' => Produk::count(),
            
            'available_products' => Produk::where('status', 'available')
                ->where('stok_tersedia', '>', 0)
                ->count(),
            
            'today_revenue' => Transaksis::where('status_pembayaran', 'settlement')
                ->whereDate('created_at', $today)
                ->sum('grand_total'),
            
            'today_orders' => Transaksis::whereDate('created_at', $today)->count(),
        ];
        
        // Recent transactions
        $recentTransactions = Transaksis::with(['paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Top products
        $topProducts = Produk::with(['brand', 'kategori'])
            ->where('status', 'available')
            ->orderBy('jumlah_dipesan', 'desc')
            ->limit(5)
            ->get();
        
        // Recent customers
        $recentCustomers = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Revenue chart data (last 7 days)
        $revenueData = $this->getRevenueChartData();
        
        // Payment methods distribution
        $paymentMethods = $this->getPaymentMethodsData();
        
        return view('admin.dashboard.index', compact(
            'summary',
            'recentTransactions',
            'topProducts',
            'recentCustomers',
            'revenueData',
            'paymentMethods'
        ));
    }
    
    /**
     * Get revenue chart data for last 7 days.
     */
    private function getRevenueChartData()
    {
        $data = [];
        $today = Carbon::today();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            
            $revenue = Transaksis::where('status_pembayaran', 'settlement')
                ->whereDate('created_at', $date)
                ->sum('grand_total');
            
            $orders = Transaksis::whereDate('created_at', $date)->count();
            
            $data[] = [
                'date' => $date->format('d M'),
                'revenue' => $revenue ?: 0,
                'orders' => $orders,
            ];
        }
        
        return $data;
    }
    
    /**
     * Get payment methods distribution.
     */
    private function getPaymentMethodsData()
    {
        return Transaksis::select(
                'payment_methods.name',
                DB::raw('COUNT(transaksis.id) as total'),
                DB::raw('SUM(transaksis.grand_total) as amount')
            )
            ->join('payment_methods', 'transaksis.payment_method_id', '=', 'payment_methods.id')
            ->where('transaksis.status_pembayaran', 'settlement')
            ->where('transaksis.created_at', '>=', Carbon::now()->subMonth())
            ->groupBy('payment_methods.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Get dashboard summary data (AJAX).
     */
    public function getSummary(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        $today = Carbon::today();
        $startDate = $this->getStartDateByPeriod($period);
        
        $summary = [
            'total_revenue' => Transaksis::where('status_pembayaran', 'settlement')
                ->whereBetween('created_at', [$startDate, $today])
                ->sum('grand_total'),
            
            'total_orders' => Transaksis::whereBetween('created_at', [$startDate, $today])->count(),
            
            'average_order_value' => $this->getAverageOrderValue($startDate, $today),
            
            'new_customers' => User::where('role', 'customer')
                ->whereBetween('created_at', [$startDate, $today])
                ->count(),
            
            'top_product' => $this->getTopProduct($startDate, $today),
            
            'conversion_rate' => $this->getConversionRate($startDate, $today),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
    
    /**
     * Get start date based on period.
     */
    private function getStartDateByPeriod($period)
    {
        switch ($period) {
            case 'day':
                return Carbon::today();
            case 'week':
                return Carbon::now()->subWeek();
            case 'month':
                return Carbon::now()->subMonth();
            case 'year':
                return Carbon::now()->subYear();
            default:
                return Carbon::now()->subMonth();
        }
    }
    
    /**
     * Get average order value.
     */
    private function getAverageOrderValue($startDate, $endDate)
    {
        $totalRevenue = Transaksis::where('status_pembayaran', 'settlement')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grand_total');
        
        $totalOrders = Transaksis::where('status_pembayaran', 'settlement')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        return $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
    }
    
    /**
     * Get top product.
     */
    private function getTopProduct($startDate, $endDate)
    {
        $product = Produk::select('produk.*')
            ->join('detail_transaksi', 'produk.id', '=', 'detail_transaksi.produk_id')
            ->join('transaksis', 'detail_transaksi.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.status_pembayaran', 'settlement')
            ->whereBetween('transaksis.created_at', [$startDate, $endDate])
            ->orderBy(DB::raw('SUM(detail_transaksi.jumlah)'), 'desc')
            ->groupBy('produk.id')
            ->first();
        
        return $product ? [
            'name' => $product->nama_produk,
            'total_rented' => $product->jumlah_dipesan,
            'revenue' => $product->jumlah_dipesan * $product->harga_per_hari
        ] : null;
    }
    
    /**
     * Get conversion rate (orders / visitors - simplified).
     */
    private function getConversionRate($startDate, $endDate)
    {
        $orders = Transaksis::whereBetween('created_at', [$startDate, $endDate])->count();
        $customers = User::where('role', 'customer')->count();
        
        // Simplified conversion rate
        return $customers > 0 ? min(100, ($orders / $customers) * 100) : 0;
    }
    
    /**
     * Get chart data for dashboard.
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'week');
        $data = [];
        
        if ($period === 'week') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $revenue = Transaksis::where('status_pembayaran', 'settlement')
                    ->whereDate('created_at', $date)
                    ->sum('grand_total');
                
                $data[] = [
                    'date' => $date->format('d M'),
                    'revenue' => $revenue ?: 0,
                ];
            }
        } else {
            // Last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $revenue = Transaksis::where('status_pembayaran', 'settlement')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('grand_total');
                
                $data[] = [
                    'date' => $date->format('M Y'),
                    'revenue' => $revenue ?: 0,
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    /**
     * Show settings page.
     */
    public function settings()
    {
        $settings = DB::table('settings')->get()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }
    
    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'company_email' => 'required|email',
            'company_phone' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
            'min_rental_days' => 'required|integer|min:1',
            'max_rental_days' => 'required|integer|min:1',
        ]);
        
        foreach ($validated as $key => $value) {
            DB::table('settings')
                ->where('key', $key)
                ->update(['value' => $value]);
        }
        
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
    
    /**
     * Show payment methods settings.
     */
    public function paymentMethods()
    {
        $paymentMethods = DB::table('payment_methods')->orderBy('sort_order')->get();
        return view('admin.settings.payment-methods', compact('paymentMethods'));
    }
    
    /**
     * Update payment methods.
     */
    public function updatePaymentMethods(Request $request)
    {
        $methods = $request->input('methods', []);
        
        foreach ($methods as $id => $data) {
            DB::table('payment_methods')
                ->where('id', $id)
                ->update([
                    'is_active' => isset($data['is_active']) ? 1 : 0,
                    'sort_order' => $data['sort_order'] ?? 0,
                ]);
        }
        
        return back()->with('success', 'Metode pembayaran berhasil diperbarui.');
    }
    
    /**
     * Show activity logs.
     */
    public function activityLogs(Request $request)
    {
        $logs = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select('activity_logs.*', 'users.nama', 'users.email')
            ->orderBy('activity_logs.created_at', 'desc')
            ->paginate(20);
        
        return view('admin.activity-logs', compact('logs'));
    }
}