<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksis;
use App\Models\Produk;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalRevenue = Transaksis::where('status_pembayaran', 'settlement')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('grand_total');

        $totalOrders = Transaksis::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $totalCustomers = User::where('role', 'customer')->count();

        $totalProducts = Produk::count();
        $availableProducts = Produk::where('status', 'available')->where('stok_tersedia', '>', 0)->count();

        $recentTransactions = Transaksis::with('paymentMethod')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue', 'totalOrders', 'totalCustomers',
            'totalProducts', 'availableProducts', 'recentTransactions'
        ));
    }

    public function activityLogs(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.activity-logs', compact('logs'));
    }
}