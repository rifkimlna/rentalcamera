<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksis;
use App\Models\DetailTransaksis;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use App\Models\Ulasan;
use App\Models\StudioBooking;
use App\Models\LayananBooking;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Collect all transaction types
        $allTransactions = collect();

        // 1. Camera rentals (transaksis)
        $rentalQuery = Transaksis::with(['user', 'paymentMethod', 'detailTransaksis']);
        $this->applyFilters($request, $rentalQuery);
        $rentals = $rentalQuery->orderBy('created_at', 'desc')->get()->map(function ($t) {
            $t->tipe = 'sewa_kamera';
            $t->tipe_label = 'Sewa Kamera';
            $t->kode = $t->kode_transaksi;
            $t->nama_pelanggan = $t->nama_customer;
            $t->status_global = $t->status_transaksi;
            $t->status_bayar = $t->status_pembayaran;
            $t->detail_link = route('admin.transactions.show', $t->id);
            return $t;
        });
        $allTransactions = $allTransactions->merge($rentals);

        // 2. Studio bookings
        $studioQuery = StudioBooking::with(['user', 'studio', 'paketStudio', 'paymentMethod']);
        if ($request->filled('search')) {
            $search = $request->search;
            $studioQuery->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('studio', fn($s) => $s->where('nama_studio', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('status_pembayaran')) {
            $studioQuery->where('payment_status', $request->status_pembayaran);
        }
        if ($request->filled('start_date')) {
            $studioQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $studioQuery->whereDate('created_at', '<=', $request->end_date);
        }
        $studios = $studioQuery->orderBy('created_at', 'desc')->get()->map(function ($t) {
            $t->tipe = 'studio';
            $t->tipe_label = 'Booking Studio';
            $t->kode = 'STD-' . $t->id;
            $t->nama_pelanggan = $t->user->nama ?? '-';
            $t->status_global = $t->status;
            $t->status_bayar = $t->payment_status;
            $t->detail_link = route('admin.studio.bookings') . '?search=' . ($t->user->nama ?? '');
            return $t;
        });
        $allTransactions = $allTransactions->merge($studios);

        // 3. Layanan bookings
        $layananQuery = LayananBooking::with(['user', 'layanan', 'paketLayanan', 'paymentMethod']);
        if ($request->filled('search')) {
            $search = $request->search;
            $layananQuery->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('layanan', fn($l) => $l->where('nama_layanan', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('status_pembayaran')) {
            $layananQuery->where('payment_status', $request->status_pembayaran);
        }
        if ($request->filled('start_date')) {
            $layananQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $layananQuery->whereDate('created_at', '<=', $request->end_date);
        }
        $layanans = $layananQuery->orderBy('created_at', 'desc')->get()->map(function ($t) {
            $t->tipe = 'layanan';
            $t->tipe_label = 'Booking Layanan';
            $t->kode = 'LYN-' . $t->id;
            $t->nama_pelanggan = $t->user->nama ?? '-';
            $t->status_global = $t->status;
            $t->status_bayar = $t->payment_status;
            $t->detail_link = route('admin.layanan.bookings') . '?search=' . ($t->user->nama ?? '');
            return $t;
        });
        $allTransactions = $allTransactions->merge($layanans);

        // Sort by created_at desc
        $sorted = $allTransactions->sortByDesc('created_at');

        // Paginate
        $perPage = 20;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginated = new LengthAwarePaginator(
            $sorted->slice($offset, $perPage)->values(),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $statusTransaksi = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'draft' => 'Draft',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Diproses',
            'dikonfirmasi' => 'Dikonfirmasi',

            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'ditolak' => 'Ditolak',
        ];

        $statusPembayaran = [
            'pending' => 'Pending',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            'refunded' => 'Dikembalikan',
            'capture' => 'Capture',
            'settlement' => 'Settlement',
            'deny' => 'Deny',
            'cancel' => 'Cancel',
            'expire' => 'Expire',
            'failure' => 'Failure',
            'refund' => 'Refund',
            'partial_refund' => 'Partial Refund',
            'chargeback' => 'Chargeback',
        ];

        $tipeList = [
            'sewa_kamera' => 'Sewa Kamera',
            'studio' => 'Booking Studio',
            'layanan' => 'Booking Layanan',
        ];

        return view('admin.transactions.index', compact('paginated', 'statusTransaksi', 'statusPembayaran', 'tipeList'));
    }

    private function applyFilters($request, $query)
    {
        if ($request->filled('status_transaksi')) {
            $query->where('status_transaksi', $request->status_transaksi);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_customer', 'like', "%{$search}%")
                  ->orWhere('email_customer', 'like', "%{$search}%")
                  ->orWhere('telepon_customer', 'like', "%{$search}%");
            });
        }
    }


    public function show($id)
{
    $transaction = Transaksis::with([
        'user',
        'paymentMethod',
        'detailTransaksis.produk',  // detailTransaksis bukan detailTransaksi
        'paymentLogs'
    ])->findOrFail($id);
    
    return view('admin.transactions.show', compact('transaction'));
}

public function printInvoice($id)
{
    $transaction = Transaksis::with([
        'user',
        'detailTransaksis.produk',
        'paymentMethod'
    ])->findOrFail($id);
    
    return view('admin.transactions.invoice', compact('transaction'));
}

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaksis::findOrFail($id);
        
        $request->validate([
            'status_transaksi' => 'required|in:draft,menunggu_pembayaran,diproses,dikonfirmasi,siap_diambil,selesai,dibatalkan,ditolak',
            'catatan_admin' => 'nullable|string'
        ]);
        
        $oldStatus = $transaction->status_transaksi;
        $newStatus = $request->status_transaksi;
        
        DB::beginTransaction();
        
        try {
            $transaction->update([
                'status_transaksi' => $newStatus,
                'catatan_admin' => $request->catatan_admin
            ]);
            
            // Update timestamps based on status
            switch ($newStatus) {
                case 'dikonfirmasi':
                    $transaction->update(['confirmed_at' => now()]);
                    break;
                case 'selesai':
                    $transaction->update(['completed_at' => now()]);

                    break;
                case 'dibatalkan':
                    $transaction->update(['cancelled_at' => now()]);
                    // Restore product stock
                    $this->restoreProductStock($transaction);
                    break;
            }
            
            // Log activity
            $this->logActivity(
                $request->user()->id,
                'transaction_update',
                "Status transaksi {$transaction->kode_transaksi} diubah dari {$oldStatus} menjadi {$newStatus}"
            );
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Status transaksi berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status transaksi: ' . $e->getMessage());
        }
    }

    



public function createManual()
{
    // Ambil data customers
    $customers = User::where('role', 'customer')
        ->where('status', 'active')
        ->orderBy('nama')
        ->get();
    
    // Ambil data produk yang available
    $products = Produk::where('status', 'available')
        ->where('stok_tersedia', '>', 0)
        ->orderBy('nama_produk')
        ->get();
    
    // AMBIL DATA PAYMENT METHODS - INI YANG DIBUTUHKAN
    $paymentMethods = PaymentMethod::where('is_active', 1)
        ->orderBy('sort_order')
        ->get();
    
    // Debug: cek apakah data ada
    // dd($paymentMethods);
    
    // Kirim ke view yang SAMA dengan create.blade.php
    return view('admin.transactions.create', compact('customers', 'products', 'paymentMethods'));
}



    public function storeManual(Request $request)
{
    try {
        // Validate request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_sewa',
            'metode_pengambilan' => 'required|in:pickup',
            'metode_pengembalian' => 'required|in:return',
            'catatan' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:produk,id',
            'products.*.quantity' => 'required|integer|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_status' => 'required|in:pending,capture,settlement,deny,cancel,expire,failure,refund,partial_refund,chargeback',
            'bank' => 'nullable|string',
            'va_number' => 'nullable|string',
            'payment_code' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'payment_notes' => 'nullable|string',
        ]);
        
        // Calculate rental days
        $tanggalSewa = Carbon::parse($validated['tanggal_sewa']);
        $tanggalKembali = Carbon::parse($validated['tanggal_kembali']);
        $lamaSewa = $tanggalSewa->diffInDays($tanggalKembali);
        
        // Get user info
        $user = User::find($validated['user_id']);
        
        DB::beginTransaction();
        
        // Create transaction
        $transaction = new Transaksis();
        $transaction->uuid = Str::uuid();
        $transaction->user_id = $validated['user_id'];
        $transaction->nama_customer = $user->nama;
        $transaction->telepon_customer = $user->telepon ?? '';
        $transaction->email_customer = $user->email;
        $transaction->tanggal_pengambilan = $tanggalSewa;
        $transaction->tanggal_pengembalian = $tanggalKembali;
        $transaction->lama_sewa = $lamaSewa;
        $transaction->metode_pengambilan = $validated['metode_pengambilan'];
        $transaction->metode_pengembalian = $validated['metode_pengembalian'];
        $transaction->catatan = $validated['catatan'] ?? null;
        $transaction->payment_method_id = $validated['payment_method_id'];
        $transaction->status_pembayaran = $validated['payment_status'];
        $transaction->status_transaksi = 'menunggu_pembayaran';
        
        // Set payment details if provided
        if ($request->filled('bank')) $transaction->bank = $validated['bank'];
        if ($request->filled('va_number')) $transaction->va_number = $validated['va_number'];
        if ($request->filled('payment_code')) $transaction->payment_code = $validated['payment_code'];
        if ($request->filled('paid_at')) $transaction->paid_at = Carbon::parse($validated['paid_at']);
        
        // Calculate totals
        $subtotal = 0;
        
        foreach ($validated['products'] as $productData) {
            $product = Produk::find($productData['id']);
            $quantity = $productData['quantity'];
            
            // Check stock availability
            if ($product->stok_tersedia < $quantity) {
                throw new \Exception("Stok tidak cukup untuk {$product->nama_produk}. Tersedia: {$product->stok_tersedia}, Dibutuhkan: {$quantity}");
            }
            
            $productSubtotal = $product->harga_per_hari * $quantity * $lamaSewa;
            $subtotal += $productSubtotal;
            
            // Update product stock
            $product->stok_dipinjam += $quantity;
            $product->stok_tersedia = $product->stok_total - $product->stok_dipinjam - $product->stok_rusak;
            $product->save();
        }
        
        // Calculate other costs
        $insuranceCost = $subtotal * 0.005; // 0.5% insurance
        $adminFee = 0;
        
        // Get payment method fee
        $paymentMethod = PaymentMethod::find($validated['payment_method_id']);
        if ($paymentMethod) {
            $adminFee = ($subtotal * $paymentMethod->fee_percentage / 100) + $paymentMethod->fee_flat;
        }
        
        $totalSewa = $subtotal + $insuranceCost;
        $grandTotal = $totalSewa + $adminFee;
        
        // Set transaction amounts
        $transaction->subtotal = $subtotal;
        $transaction->biaya_asuransi = $insuranceCost;
        $transaction->biaya_lainnya = 0;
        $transaction->total_sewa = $totalSewa;
        $transaction->admin_fee = $adminFee;
        $transaction->grand_total = $grandTotal;
        
        // If payment is settled, update status
        if (in_array($validated['payment_status'], ['settlement', 'capture'])) {
            $transaction->status_transaksi = 'dikonfirmasi';
            $transaction->confirmed_at = now();
            
            if (!$transaction->paid_at) {
                $transaction->paid_at = now();
            }
        }
        
        $transaction->save();
        
        // Create transaction details
        foreach ($validated['products'] as $productData) {
            $product = Produk::find($productData['id']);
            $quantity = $productData['quantity'];
            
            $detail = new DetailTransaksis();
            $detail->transaksi_id = $transaction->id;
            $detail->produk_id = $product->id;
            $detail->kode_produk = $product->kode_produk;
            $detail->nama_produk = $product->nama_produk;
            $detail->harga_per_hari = $product->harga_per_hari;
            $detail->jumlah = $quantity;
            $detail->lama_sewa = $lamaSewa;
            $detail->subtotal = $product->harga_per_hari * $quantity * $lamaSewa;
            $detail->save();
        }
        
        // Create payment log
        $paymentLog = new PaymentLog();
        $paymentLog->transaksi_id = $transaction->id;
        $paymentLog->order_id = $transaction->kode_transaksi;
        $paymentLog->transaction_status = $validated['payment_status'];
        $paymentLog->payment_type = $paymentMethod->midtrans_payment_type ?? 'manual';
        $paymentLog->gross_amount = $grandTotal;
        $paymentLog->status_code = '200';
        $paymentLog->status_message = 'Transaksi manual dibuat oleh admin';
        $paymentLog->bank = $validated['bank'] ?? null;
        $paymentLog->va_number = $validated['va_number'] ?? null;
        $paymentLog->save();
        
        // Create activity log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'transaction',
            'description' => 'Membuat transaksi manual: ' . $transaction->kode_transaksi . ' untuk customer ' . $user->nama,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => json_encode([
                'transaction_id' => $transaction->id,
                'customer' => $user->nama,
                'total' => $grandTotal,
                'status' => $validated['payment_status']
            ])
        ]);
        
        DB::commit();
        
        return redirect()->route('admin.transactions.show', $transaction->id)
            ->with('success', 'Transaksi manual berhasil dibuat!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        return back()->withInput()
            ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
    }
}


    

    public function export(Request $request)
    {
        $query = Transaksis::with(['user', 'paymentMethod']);
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions_' . date('Ymd_His') . '.csv"',
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Kode Transaksi',
                'Tanggal',
                'Customer',
                'Email',
                'Telepon',
                'Total',
                'Status Transaksi',
                'Status Pembayaran',
                'Metode Pembayaran',
                'Tanggal Bayar'
            ]);
            
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->kode_transaksi,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->nama_customer,
                    $transaction->email_customer,
                    $transaction->telepon_customer,
                    'Rp ' . number_format($transaction->grand_total, 0, ',', '.'),
                    $transaction->status_transaksi,
                    $transaction->status_pembayaran,
                    $transaction->paymentMethod ? $transaction->paymentMethod->name : '-',
                    $transaction->paid_at ? $transaction->paid_at->format('Y-m-d H:i:s') : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function reviews(Request $request)
    {
        $query = Ulasan::with(['user', 'produk', 'transaksi']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('produk', function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhere('komentar', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $ratings = [1, 2, 3, 4, 5];

        return view('admin.reviews.index', compact('reviews', 'ratings'));
    }

    public function showReview($id)
    {
        $review = Ulasan::with(['user', 'produk', 'transaksi.paymentMethod'])->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    public function approveReview(Request $request, $id)
    {
        $review = Ulasan::findOrFail($id);

        if ($review->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya ulasan dengan status pending yang bisa disetujui.');
        }

        $review->approve();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'review',
            'description' => 'Menyetujui ulasan #' . $review->id . ' untuk produk ' . ($review->produk->nama_produk ?? ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => json_encode([
                'review_id' => $review->id,
                'produk_id' => $review->produk_id,
                'rating' => $review->rating
            ])
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil disetujui.');
    }

    public function rejectReview(Request $request, $id)
    {
        $review = Ulasan::findOrFail($id);

        if ($review->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya ulasan dengan status pending yang bisa ditolak.');
        }

        $review->reject();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'review',
            'description' => 'Menolak ulasan #' . $review->id . ' untuk produk ' . ($review->produk->nama_produk ?? ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => json_encode([
                'review_id' => $review->id,
                'produk_id' => $review->produk_id,
            ])
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil ditolak.');
    }

    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string|min:1',
        ]);

        $review = Ulasan::findOrFail($id);

        $review->reply($request->balasan);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'review',
            'description' => 'Membalas ulasan #' . $review->id . ' untuk produk ' . ($review->produk->nama_produk ?? ''),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => json_encode([
                'review_id' => $review->id,
                'produk_id' => $review->produk_id,
            ])
        ]);

        return redirect()->back()->with('success', 'Balasan ulasan berhasil dikirim.');
    }

    private function restoreProductStock($transaction)
    {
        foreach ($transaction->detailTransaksis as $detail) {
            if ($detail->produk_id) {
                $product = Produk::find($detail->produk_id);
                if ($product) {
                    $product->update([
                        'stok_dipinjam' => $product->stok_dipinjam - $detail->jumlah,
                        'stok_tersedia' => $product->stok_tersedia + $detail->jumlah,
                    ]);
                }
            }
        }
    }

    private function logActivity($userId, $type, $description)
    {
        \App\Models\ActivityLog::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }



    
}
