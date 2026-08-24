<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaksis;
use App\Models\DetailTransaksis;
use App\Models\Ulasan;
use App\Models\ActivityLog;
use App\Models\StudioBooking;
use App\Models\LayananBooking;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;
        $allTransactions = collect();

        // 1. Camera rentals
        $rentalQuery = Transaksis::with('detailTransaksis.produk', 'paymentMethod')
            ->where('user_id', $userId);
        if ($request->filled('search')) {
            $s = $request->search;
            $rentalQuery->where(function ($q) use ($s) {
                $q->where('kode_transaksi', 'like', "%{$s}%")
                  ->orWhere('nama_customer', 'like', "%{$s}%");
            });
        }
        $rentals = $rentalQuery->orderBy('created_at', 'desc')->get()->map(function ($t) {
            $t->tipe = 'sewa_kamera';
            $t->tipe_label = 'Sewa Kamera';
            $t->kode = $t->kode_transaksi;
            $t->status_global = $t->status_transaksi;
            $t->status_bayar = $t->status_pembayaran;
            $t->total = $t->grand_total;
            $t->detail_link = route('customer.transactions.show', [$t->id, 'type' => 'sewa_kamera']);
            return $t;
        });
        $allTransactions = $allTransactions->merge($rentals);
        $studioQuery = StudioBooking::with('studio', 'paketStudio', 'paymentMethod')
            ->where('user_id', $userId);
        if ($request->filled('search')) {
            $s = $request->search;
            $studioQuery->whereHas('studio', fn($q) => $q->where('nama_studio', 'like', "%{$s}%"));
        }
        $studios = $studioQuery->orderBy('created_at', 'desc')->get()->map(function ($t) {
            $t->tipe = 'studio';
            $t->tipe_label = 'Booking Studio';
            $t->kode = 'STD-' . $t->id;
            $t->status_global = $t->status;
            $t->status_bayar = $t->payment_status;
            $t->total = $t->grand_total ?? $t->total_harga;
            $t->detail_link = route('customer.transactions.show', [$t->id, 'type' => 'studio']);
            return $t;
        });
        $allTransactions = $allTransactions->merge($studios);

        // 3. Layanan bookings
        $layananQuery = LayananBooking::with('layanan', 'paketLayanan', 'paymentMethod')
            ->where('user_id', $userId);
        if ($request->filled('search')) {
            $s = $request->search;
            $layananQuery->whereHas('layanan', fn($q) => $q->where('nama_layanan', 'like', "%{$s}%"));
        }
        $layanans = $layananQuery->orderBy('created_at', 'desc')->get()->map(function ($t) {
            $t->tipe = 'layanan';
            $t->tipe_label = 'Booking Layanan';
            $t->kode = 'LYN-' . $t->id;
            $t->status_global = $t->status;
            $t->status_bayar = $t->payment_status;
            $t->total = $t->grand_total ?? $t->total_harga;
            $t->detail_link = route('customer.transactions.show', [$t->id, 'type' => 'layanan']);
            return $t;
        });
        $allTransactions = $allTransactions->merge($layanans);

        // Filter by status
        if ($request->filled('status')) {
            $filtered = $allTransactions;
            if ($request->status === 'pending') {
                $filtered = $allTransactions->filter(fn($t) => $t->status_bayar === 'pending');
            } elseif ($request->status === 'active') {
                $filtered = $allTransactions->filter(fn($t) => in_array($t->status_global, ['confirmed', 'dikonfirmasi', 'siap_diambil']));
            } elseif ($request->status === 'completed') {
                $filtered = $allTransactions->filter(fn($t) => in_array($t->status_global, ['completed', 'selesai']));
            } elseif ($request->status === 'cancelled') {
                $filtered = $allTransactions->filter(fn($t) => in_array($t->status_global, ['cancelled', 'dibatalkan']));
            }
            $allTransactions = $filtered;
        }

        // Sort by created_at desc
        $sorted = $allTransactions->sortByDesc('created_at');

        // Paginate
        $perPage = 15;
        $page = $request->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $sorted->slice(($page - 1) * $perPage, $perPage)->values(),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $statuses = [
            '' => 'Semua Transaksi',
            'pending' => 'Menunggu Pembayaran',
            'active' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('customer.transactions.index', compact('paginated', 'statuses'));
    }

    /**
     * Display the specified transaction (camera rental, studio, or layanan).
     */
    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $type = request('type', 'sewa_kamera');

        if ($type === 'studio') {
            $booking = StudioBooking::with(['studio', 'paketStudio', 'paymentMethod'])
                ->where('user_id', $user->id)
                ->findOrFail($id);
            return view('customer.transactions.show', [
                'transaction' => $booking,
                'bookingType' => 'studio',
            ]);
        }

        if ($type === 'layanan') {
            $booking = LayananBooking::with(['layanan', 'paketLayanan', 'paymentMethod'])
                ->where('user_id', $user->id)
                ->findOrFail($id);
            return view('customer.transactions.show', [
                'transaction' => $booking,
                'bookingType' => 'layanan',
            ]);
        }

        // Default: camera rental
        $transaksi = $user->transaksis()
            ->with([
                'detailTransaksis.produk',
                'paymentMethod',
                'ulasan',
                'reviews',
                'paymentLogs'
            ])
            ->findOrFail($id);
        
        return view('customer.transactions.show', [
            'transaction' => $transaksi,
            'bookingType' => 'sewa_kamera',
        ]);
    }

    /**
     * Cancel a pending transaction.
     */
    public function cancel(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->where(function ($q) {
                $q->where('status_transaksi', 'menunggu_pembayaran')
                  ->orWhere('status_transaksi', 'diproses');
            })
            ->where('status_pembayaran', 'pending')
            ->firstOrFail();
        
        // Batalkan juga di sisi Midtrans agar VA/Snap tidak bisa dibayar setelahnya
        if ($transaction->midtrans_order_id) {
            $this->midtransService->cancelTransaction($transaction->midtrans_order_id);

            if ($this->midtransService->isPaidAtGateway($transaction->midtrans_order_id)) {
                return redirect()->back()
                    ->with('error', 'Transaksi ini sudah terbayar di Midtrans dan tidak dapat dibatalkan. Silakan hubungi admin untuk pengembalian dana.');
            }
        }
        
        $reason = $request->filled('reason') ? $request->reason : 'Tidak ada alasan';
        
        DB::beginTransaction();
        
        try {
            // Update transaction status
            $transaction->update([
                'status_pembayaran' => 'cancel',
                'status_transaksi' => 'dibatalkan',
                'cancelled_at' => now(),
                'catatan' => ($transaction->catatan ? $transaction->catatan . "\n" : '') . 
                            'Dibatalkan oleh customer: ' . $reason,
            ]);

            // Kembalikan stok produk
            foreach ($transaction->detailTransaksis as $detail) {
                if ($detail->produk) {
                    $detail->produk->updateStock('return', $detail->jumlah);
                }
            }

            // Kembalikan kuota voucher & hapus catatan pemakaiannya
            Voucher::releaseByCode($transaction->kode_voucher);
            VoucherUsage::where('transaksi_id', $transaction->id)->delete();
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'transaction',
                'description' => "Membatalkan transaksi #{$transaction->kode_transaksi}",
                'ip_address' => $request->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil dibatalkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Submit a review for completed transaction.
     */
    public function submitReview(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->where('status_transaksi', 'selesai')
            ->firstOrFail();
        
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'judul' => 'nullable|string|max:200',
            'komentar' => 'required|string|min:10|max:1000',
            'foto_ulasan' => 'nullable|array|max:3',
            'foto_ulasan.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Check if product belongs to this transaction
        $productInTransaction = DetailTransaksis::where('transaksi_id', $transaction->id)
            ->where('produk_id', $request->produk_id)
            ->exists();
        
        if (!$productInTransaction) {
            return redirect()->back()
                ->with('error', 'Produk tidak ditemukan dalam transaksi ini.');
        }
        
        // Check if already reviewed this product in this transaction
        $alreadyReviewed = Ulasan::where('transaksi_id', $transaction->id)
            ->where('produk_id', $request->produk_id)
            ->exists();
        
        if ($alreadyReviewed) {
            return redirect()->back()
                ->with('error', 'Anda sudah memberikan ulasan untuk produk ini dalam transaksi ini.');
        }
        
        DB::beginTransaction();
        
        try {
            // Handle photo uploads
            $fotoPaths = [];
            if ($request->hasFile('foto_ulasan')) {
                foreach ($request->file('foto_ulasan') as $foto) {
                    $filename = 'ulasan-' . time() . '-' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('reviews', $filename, 'public');
                    $fotoPaths[] = $path;
                }
            }
            
            // Create review
            $ulasan = Ulasan::create([
                'transaksi_id' => $transaction->id,
                'user_id' => $user->id,
                'produk_id' => $request->produk_id,
                'rating' => $request->rating,
                'judul' => $request->judul,
                'komentar' => $request->komentar,
                'foto_ulasan' => !empty($fotoPaths) ? $fotoPaths : null,
                'status' => 'pending', // Wait for admin approval
            ]);
            
            // Update product rating stats (gunakan query builder untuk menghindari error IDE)
            /** @var \App\Models\Produk $produk */
            $produk = \App\Models\Produk::find($request->produk_id);
            
            if ($produk) {
                // Hitung rating baru
                $newRatingCount = $produk->jumlah_ulasan + 1;
                $newRating = (($produk->rating * $produk->jumlah_ulasan) + $request->rating) / $newRatingCount;
                
                // Update menggunakan query builder agar IDE tidak error
                $produk->update([
                    'rating' => round($newRating, 2),
                    'jumlah_ulasan' => $newRatingCount,
                ]);
            }
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'review',
                'description' => "Memberikan ulasan untuk produk ID {$request->produk_id} pada transaksi #{$transaction->kode_transaksi}",
                'ip_address' => $request->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Ulasan berhasil dikirim. Menunggu persetujuan admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal mengirim ulasan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update a review.
     */
    public function updateReview(Request $request, $id, $reviewId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->firstOrFail();
        
        $ulasan = Ulasan::where('id', $reviewId)
            ->where('transaksi_id', $transaction->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'judul' => 'nullable|string|max:200',
            'komentar' => 'required|string|min:10|max:1000',
            'foto_ulasan' => 'nullable|array|max:3',
            'foto_ulasan.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Handle photo uploads
            $fotoPaths = $ulasan->foto_ulasan ?? [];
            if ($request->hasFile('foto_ulasan')) {
                // Delete old photos
                if (!empty($fotoPaths)) {
                    foreach ($fotoPaths as $oldFoto) {
                        if (Storage::disk('public')->exists($oldFoto)) {
                            Storage::disk('public')->delete($oldFoto);
                        }
                    }
                }
                
                // Upload new photos
                $fotoPaths = [];
                foreach ($request->file('foto_ulasan') as $foto) {
                    $filename = 'ulasan-' . time() . '-' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('reviews', $filename, 'public');
                    $fotoPaths[] = $path;
                }
            }
            
            $oldRating = $ulasan->rating;
            $newRating = $request->rating;
            
            // Update review
            $ulasan->update([
                'rating' => $newRating,
                'judul' => $request->judul,
                'komentar' => $request->komentar,
                'foto_ulasan' => !empty($fotoPaths) ? $fotoPaths : null,
                'status' => 'pending', // Reset to pending for admin approval
            ]);
            
            // Update product rating stats if rating changed
            if ($oldRating != $newRating) {
                /** @var \App\Models\Produk $produk */
                $produk = \App\Models\Produk::find($ulasan->produk_id);
                
                if ($produk && $produk->jumlah_ulasan > 0) {
                    // Hitung rating baru
                    $totalRating = ($produk->rating * $produk->jumlah_ulasan) - $oldRating + $newRating;
                    $newAverage = $totalRating / $produk->jumlah_ulasan;
                    
                    // Update menggunakan query builder
                    $produk->update([
                        'rating' => round($newAverage, 2),
                    ]);
                }
            }
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'review',
                'description' => "Memperbarui ulasan untuk produk ID {$ulasan->produk_id}",
                'ip_address' => $request->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Ulasan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui ulasan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a review.
     */
    public function deleteReview(Request $request, $id, $reviewId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->firstOrFail();
        
        $ulasan = Ulasan::where('id', $reviewId)
            ->where('transaksi_id', $transaction->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        DB::beginTransaction();
        
        try {
            // Delete review photos
            if ($ulasan->foto_ulasan && is_array($ulasan->foto_ulasan)) {
                foreach ($ulasan->foto_ulasan as $foto) {
                    if (Storage::disk('public')->exists($foto)) {
                        Storage::disk('public')->delete($foto);
                    }
                }
            }
            
            $produkId = $ulasan->produk_id;
            $oldRating = $ulasan->rating;
            
            // Delete review
            $ulasan->delete();
            
            // Update product rating stats
            /** @var \App\Models\Produk $produk */
            $produk = \App\Models\Produk::find($produkId);
            
            if ($produk && $produk->jumlah_ulasan > 1) {
                // Hitung rating baru tanpa ulasan yang dihapus
                $totalRating = ($produk->rating * $produk->jumlah_ulasan) - $oldRating;
                $newRatingCount = $produk->jumlah_ulasan - 1;
                $newAverage = $totalRating / $newRatingCount;
                
                // Update menggunakan query builder
                $produk->update([
                    'rating' => round($newAverage, 2),
                    'jumlah_ulasan' => $newRatingCount,
                ]);
            } elseif ($produk && $produk->jumlah_ulasan == 1) {
                // Reset rating jika hanya ada 1 ulasan
                $produk->update([
                    'rating' => 0,
                    'jumlah_ulasan' => 0,
                ]);
            }
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'review',
                'description' => "Menghapus ulasan untuk produk ID {$produkId}",
                'ip_address' => $request->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Ulasan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus ulasan: ' . $e->getMessage());
        }
    }

    public function invoice($id)
    {
        return $this->downloadInvoice($id);
    }

    public function requestCancel(Request $request, $id)
    {
        return $this->cancel($request, $id);
    }

    public function extend(Request $request, $id)
    {
        return redirect()->back()->with('info', 'Fitur perpanjangan sewa sedang dalam pengembangan.');
    }

    /**
     * Download transaction invoice.
     */
    public function downloadInvoice($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->with('detailTransaksis.produk', 'paymentMethod')
            ->findOrFail($id);
        
        // Check if transaction is paid
        if (!in_array($transaction->status_pembayaran, ['settlement', 'capture'])) {
            return redirect()->back()
                ->with('error', 'Invoice hanya tersedia untuk transaksi yang sudah dibayar.');
        }
        
        // In a real application, you would generate PDF invoice here
        // For now, return view
        return view('customer.transactions.invoice', compact('transaction'));
    }

    /**
     * Confirm return of goods (customer has returned to store).
     */
    public function confirmReturn($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaction = $user->transaksis()
            ->where('id', $id)
            ->whereIn('status_transaksi', ['dikonfirmasi', 'siap_diambil', 'diproses'])
            ->whereIn('status_pembayaran', ['settlement', 'capture'])
            ->firstOrFail();

        DB::beginTransaction();

        try {
            $transaction->update([
                'status_transaksi' => 'selesai',
                'completed_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'transaction',
                'description' => "Mengonfirmasi pengembalian barang untuk transaksi #{$transaction->kode_transaksi}",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Pengembalian barang berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal mengonfirmasi pengembalian: ' . $e->getMessage());
        }
    }


}
