<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display customer dashboard.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Recent transactions (last 5)
        $recentTransactions = $user->transaksis()
            ->with('detailTransaksis.produk')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        

        
        // Cart items count
        $cartCount = $user->keranjangs()->count();
        
        // Statistics
        $totalSpent = $user->transaksis()
            ->where('status_pembayaran', 'settlement')
            ->sum('grand_total');

        $stats = [
            'total_orders' => $user->transaksis()->count(),
            'completed_orders' => $user->transaksis()
                ->where('status_transaksi', 'selesai')
                ->count(),
            'pending_orders' => $user->transaksis()
                ->whereIn('status_transaksi', ['dikonfirmasi', 'siap_diambil'])
                ->count(),
            'total_spent' => $totalSpent,
        ];
        
        // Upcoming rentals (within next 3 days)
        $upcomingRentals = $user->transaksis()
            ->where('status_transaksi', 'dikonfirmasi')
            ->whereDate('tanggal_pengambilan', '<=', Carbon::now()->addDays(3))
            ->whereDate('tanggal_pengambilan', '>=', Carbon::now())
            ->with('detailTransaksis.produk')
            ->orderBy('tanggal_pengambilan')
            ->limit(3)
            ->get();
        
        // Products to review (completed transactions without review)
        $transactionsToReview = $user->transaksis()
            ->where('status_transaksi', 'selesai')
            ->whereDoesntHave('ulasan')
            ->with('detailTransaksis.produk')
            ->orderBy('completed_at', 'desc')
            ->limit(3)
            ->get();
        
        // Current balance and points
        $balance = [
            'points' => $user->poin_reward,
        ];
        
        return view('customer.dashboard.index', compact(
            'user',
            'recentTransactions',
            'cartCount',
            'stats',
            'upcomingRentals',
            'transactionsToReview',
            'balance'
        ));
    }

    public function getSummary()
    {
        $user = Auth::user();
        $stats = [
            'total_transactions' => $user->transaksis()->count(),
            'active_rentals' => $user->transaksis()->whereIn('status_transaksi', ['dikonfirmasi', 'siap_diambil'])->count(),
            'pending_payments' => $user->transaksis()->where('status_pembayaran', 'pending')->count(),
            'completed' => $user->transaksis()->where('status_transaksi', 'selesai')->count(),
        ];
        return response()->json($stats);
    }

    public function activity()
    {
        $user = Auth::user();
        $activityLogs = $user->activityLogs()->orderBy('created_at', 'desc')->paginate(20);
        return view('customer.dashboard.activity_logs', compact('activityLogs'));
    }

    /**
     * Display profile page.
     */
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();
        
        return view('customer.dashboard.profile', compact('user'));
    }

    /**
     * Display notifications page.
     */
    public function notifications()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Mark as read when viewed
        $user->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return view('customer.dashboard.notifications', compact('notifications'));
    }

    /**
     * Display activity logs page.
     */
    public function activityLogs()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $activityLogs = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('customer.dashboard.activity_logs', compact('activityLogs'));
    }

    /**
     * Display vouchers page.
     */
    public function vouchers()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Available vouchers
        $availableVouchers = \App\Models\Voucher::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function($query) use ($user) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', $user->id);
            })
            ->where(function($query) {
                $query->whereNull('kuota')
                      ->orWhereRaw('kuota_terpakai < kuota');
            })
            ->orderBy('end_date')
            ->get();
        
        // Used vouchers
        $usedVouchers = $user->voucherUsages()
            ->with('voucher')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('customer.dashboard.vouchers', compact('availableVouchers', 'usedVouchers'));
    }

    /**
     * Display rental history.
     */
    public function rentalHistory()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get all completed transactions with product details
        $rentalHistory = $user->transaksis()
            ->where('status_transaksi', 'selesai')
            ->with(['detailTransaksis.produk', 'ulasan'])
            ->orderBy('completed_at', 'desc')
            ->paginate(10);
        
        // Calculate total spending
        $totalSpending = $user->transaksis()
            ->where('status_pembayaran', 'settlement')
            ->sum('grand_total');
        
        // Most rented products
        $mostRented = DB::table('detail_transaksi as dt')
            ->join('transaksis as t', 'dt.transaksi_id', '=', 't.id')
            ->join('produk as p', 'dt.produk_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nama_produk',
                'p.kode_produk',
                DB::raw('SUM(dt.jumlah) as total_rented'),
                DB::raw('SUM(dt.subtotal) as total_spent')
            )
            ->where('t.user_id', $user->id)
            ->where('t.status_pembayaran', 'settlement')
            ->groupBy('p.id', 'p.nama_produk', 'p.kode_produk')
            ->orderBy('total_rented', 'desc')
            ->limit(5)
            ->get();
        
        return view('customer.dashboard.rental_history', compact(
            'rentalHistory',
            'totalSpending',
            'mostRented'
        ));
    }

    /**
     * Cancel transaction.
     */
    public function cancelTransaction(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->where('status_transaksi', 'menunggu_pembayaran')
            ->where('status_pembayaran', 'pending')
            ->firstOrFail();
        
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);
        
        // Update transaction status
        $transaction->update([
            'status_pembayaran' => 'cancel',
            'status_transaksi' => 'dibatalkan',
            'cancelled_at' => now(),
            'catatan' => $request->reason . ' - ' . ($transaction->catatan ?? ''),
        ]);
        
        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'type' => 'transaction',
            'description' => "Membatalkan transaksi #{$transaction->kode_transaksi}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->route('customer.transactions.index')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }

    /**
     * Request extension for rental.
     */
    public function requestExtension(Request $request, $id)
{
    /** @var User $user */
    $user = Auth::user();
    
    $transaction = $user->transaksis()
        ->where('id', $id)
        ->whereIn('status_transaksi', ['dikonfirmasi', 'siap_diambil'])
        ->firstOrFail();
    
    $request->validate([
        'extra_days' => 'required|integer|min:1|max:7',
        'reason' => 'required|string|max:500',
    ]);
    
    // Calculate extension cost
    $extensionCost = 0;
    foreach ($transaction->detailTransaksis as $detail) {
        $extensionCost += $detail->harga_per_hari * $request->extra_days * $detail->jumlah;
    }
    
    // TODO: Implement extension request feature
    // ExtensionRequest model belum dibuat
    
    return redirect()->back()
        ->with('info', 'Fitur perpanjangan sewa sedang dalam pengembangan.');
    }
}