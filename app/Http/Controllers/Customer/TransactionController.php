<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksis;
use App\Models\Ulasan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
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
        
        return view('customer.transactions.index', compact('transactions', 'statuses'));
    }

    /**
     * Display the specified transaction.
     */
    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->with([
                'detailTransaksis.produk',
                'paymentMethod',
                'pengiriman',
                'ulasan',
                'paymentLogs'
            ])
            ->findOrFail($id);
        
        return view('customer.transactions.show', compact('transaction'));
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
            ->where('status_transaksi', 'menunggu_pembayaran')
            ->where('status_pembayaran', 'pending')
            ->firstOrFail();
        
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

    public function deposit()
    {
        return view('customer.deposit.index');
    }

    public function topup(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur top up deposit sedang dalam pengembangan.');
    }

    public function depositHistory(Request $request)
    {
        return redirect()->route('customer.deposit.index')->with('info', 'Fitur riwayat deposit sedang dalam pengembangan.');
    }

    public function shipping($id)
    {
        $user = Auth::user();
        $transaction = $user->transaksis()->with('pengiriman')->findOrFail($id);
        return view('customer.shipping.track', compact('transaction'));
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
     * Confirm receipt of goods.
     */
    public function confirmReceipt($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->where('status_transaksi', 'dikirim')
            ->firstOrFail();
        
        DB::beginTransaction();
        
        try {
            // Update transaction status
            $transaction->update([
                'status_transaksi' => 'dalam_perjalanan',
                'shipped_at' => now(),
            ]);
            
            // Update pengiriman status
            if ($transaction->pengiriman) {
                $transaction->pengiriman->update([
                    'status' => 'delivered',
                    'actual_delivery' => now(),
                ]);
            }
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'transaction',
                'description' => "Mengonfirmasi penerimaan barang untuk transaksi #{$transaction->kode_transaksi}",
                'ip_address' => request()->ip(),
            ]);
            
            DB::commit();
            
            return redirect()->route('customer.transactions.show', $transaction->id)
                ->with('success', 'Penerimaan barang berhasil dikonfirmasi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal mengonfirmasi penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Confirm return of goods.
     */
    public function confirmReturn($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->where('status_transaksi', 'dalam_perjalanan')
            ->firstOrFail();
        
        DB::beginTransaction();
        
        try {
            // Update transaction status
            $transaction->update([
                'status_transaksi' => 'selesai',
                'completed_at' => now(),
            ]);
            
            // Log activity
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

    /**
     * Request pickup for return.
     */
    public function requestPickup(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaction = $user->transaksis()
            ->where('id', $id)
            ->whereIn('status_transaksi', ['dalam_perjalanan', 'dikirim'])
            ->firstOrFail();
        
        $request->validate([
            'pickup_date' => 'required|date|after:today',
            'pickup_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Update pengiriman for return pickup
        if ($transaction->pengiriman) {
            $transaction->pengiriman->update([
                'status' => 'returned',
                'estimated_delivery' => $request->pickup_date . ' ' . $request->pickup_time,
                'catatan' => ($transaction->pengiriman->catatan ? $transaction->pengiriman->catatan . "\n" : '') .
                            'Pickup requested: ' . $request->notes,
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Permintaan penjemputan telah dikirim. Tim kami akan menghubungi Anda.');
    }
}
