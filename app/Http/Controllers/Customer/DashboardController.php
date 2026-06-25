<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Keranjang;
use App\Models\Ulasan;
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
        
        // Recent deposit transactions
        $recentDeposits = $user->depositTransactions()
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
                ->whereIn('status_transaksi', ['dikonfirmasi', 'dikemas', 'dikirim'])
                ->count(),
            'total_spent' => $totalSpent,
        ];
        
        // Upcoming rentals (within next 3 days)
        $upcomingRentals = $user->transaksis()
            ->whereIn('status_transaksi', ['dikonfirmasi', 'dikemas'])
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
            'deposit' => $user->saldo_deposit,
            'credit' => $user->saldo_credit,
            'points' => $user->poin_reward,
        ];
        
        return view('customer.dashboard.index', compact(
            'user',
            'recentTransactions',
            'recentDeposits',
            'cartCount',
            'stats',
            'upcomingRentals',
            'transactionsToReview',
            'balance'
        ));
    }

    /**
     * Display transactions page.
     */
    public function transactions(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $query = $user->transaksis()->with('detailTransaksis.produk', 'paymentMethod');
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereIn('status_transaksi', ['dikonfirmasi', 'dikemas', 'dikirim']);
            } elseif ($request->status === 'completed') {
                $query->where('status_transaksi', 'selesai');
            } elseif ($request->status === 'cancelled') {
                $query->where('status_transaksi', 'dibatalkan');
            } elseif ($request->status === 'pending') {
                $query->where('status_pembayaran', 'pending');
            }
        }
        
        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_produk', 'like', "%{$search}%")
                  ->orWhereHas('detailTransaksis', function($q2) use ($search) {
                      $q2->where('nama_produk', 'like', "%{$search}%");
                  });
            });
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $statuses = [
            'all' => 'Semua Transaksi',
            'active' => 'Sedang Disewa',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'pending' => 'Menunggu Pembayaran',
        ];
        
        return view('customer.dashboard.transactions', compact('transactions', 'statuses'));
    }

    public function getSummary()
    {
        $user = Auth::user();
        $stats = [
            'total_transactions' => $user->transaksis()->count(),
            'active_rentals' => $user->transaksis()->whereIn('status_transaksi', ['dikonfirmasi', 'dikemas', 'dikirim'])->count(),
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
     * Display transaction detail.
     */
    public function transactionDetail($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->with([
                'detailTransaksis.produk',
                'paymentMethod',
                'pengiriman',
                'ulasan'
            ])
            ->findOrFail($id);
        
        return view('customer.dashboard.transaction_detail', compact('transaction'));
    }

    /**
     * Display deposit transactions.
     */
    public function deposits(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $query = $user->depositTransactions();
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $deposits = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $types = [
            'all' => 'Semua Tipe',
            'topup' => 'Top Up',
            'withdraw' => 'Penarikan',
            'payment' => 'Pembayaran',
            'refund' => 'Pengembalian',
            'penalty' => 'Denda',
            'reward' => 'Reward',
        ];
        
        $statuses = [
            'all' => 'Semua Status',
            'pending' => 'Pending',
            'success' => 'Sukses',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
        ];
        
        return view('customer.dashboard.deposits', compact('deposits', 'types', 'statuses'));
    }

    /**
     * Display cart page.
     */
    public function cart()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $cartItems = $user->keranjangs()
            ->with('produk')
            ->get();
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            if ($item->produk && $item->produk->harga_per_hari) {
                $subtotal += $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
            }
        }
        
        return view('customer.dashboard.cart', compact('cartItems', 'subtotal'));
    }

    /**
     * Display reviews page.
     */
    public function reviews()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $reviews = $user->ulasans()
            ->with('produk', 'transaksi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Transactions that can be reviewed
        $transactionsToReview = $user->transaksis()
            ->where('status_transaksi', 'selesai')
            ->whereDoesntHave('ulasan')
            ->with('detailTransaksis.produk')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('customer.dashboard.reviews', compact('reviews', 'transactionsToReview'));
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
     * Download transaction invoice.
     */
    public function downloadInvoice($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->with('detailTransaksis.produk', 'paymentMethod')
            ->findOrFail($id);
        
        // In a real application, you would generate PDF invoice
        // For now, just show the invoice view
        return view('customer.dashboard.invoice', compact('transaction'));
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
        
        return redirect()->route('customer.dashboard.transactions')
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
        ->whereIn('status_transaksi', ['dikonfirmasi', 'dikirim'])
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