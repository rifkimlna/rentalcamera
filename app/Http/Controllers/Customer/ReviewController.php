<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\Transaksis;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        return $this->myReviews();
    }

    public function available()
    {
        $user = Auth::user();
        $transactions = $user->transaksis()
            ->where('status_transaksi', 'selesai')
            ->with(['detailTransaksis.produk', 'reviews'])
            ->orderBy('completed_at', 'desc')
            ->get()
            ->filter(function ($t) {
                return $t->detailTransaksis->contains(function ($d) use ($t) {
                    return !$t->hasReviewForProduct($d->produk_id);
                });
            });

        return view('customer.reviews.available', compact('transactions'));
    }

    public function create($transactionId)
    {
        $transaction = Transaksis::where('user_id', Auth::id())
            ->where('id', $transactionId)
            ->where('status_transaksi', 'selesai')
            ->firstOrFail();

        $productId = request('product');
        if ($productId) {
            $exists = $transaction->detailTransaksis()->where('produk_id', $productId)->exists();
            if (!$exists) {
                return redirect()->route('customer.transactions.show', $transactionId)
                    ->with('error', 'Produk tidak ditemukan dalam transaksi ini.');
            }
            $reviewed = Ulasan::where('transaksi_id', $transactionId)
                ->where('produk_id', $productId)->exists();
            if ($reviewed) {
                return redirect()->route('customer.transactions.show', $transactionId)
                    ->with('info', 'Anda sudah memberikan ulasan untuk produk ini.');
            }
        }

        return view('customer.reviews.create', compact('transaction'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'judul' => 'nullable|string|max:200',
            'komentar' => 'required|string',
            'foto_ulasan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $transaction = Transaksis::where('user_id', Auth::id())
            ->where('id', $request->transaksi_id)
            ->where('status_transaksi', 'selesai')
            ->firstOrFail();
        
        // Check if review already exists for this product in this transaction
        $existingReview = Ulasan::where('transaksi_id', $request->transaksi_id)
            ->where('produk_id', $request->produk_id)
            ->first();
        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }
        
        // Verify that the product belongs to the transaction
        $productInTransaction = $transaction->detailTransaksis()
            ->where('produk_id', $request->produk_id)
            ->exists();
        
        if (!$productInTransaction) {
            return redirect()->back()
                ->with('error', 'Produk tidak ditemukan dalam transaksi ini.');
        }
        
        $ulasanData = $request->only(['produk_id', 'rating', 'judul', 'komentar']);
        $ulasanData['transaksi_id'] = $request->transaksi_id;
        $ulasanData['user_id'] = Auth::id();
        $ulasanData['status'] = 'pending';
        
        // Handle review photos
        $fotoUlasan = [];
        if ($request->hasFile('foto_ulasan')) {
            foreach ($request->file('foto_ulasan') as $image) {
                $filename = 'review-' . Auth::id() . '-' . time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('reviews', $filename, 'public');
                $fotoUlasan[] = $path;
            }
            $ulasanData['foto_ulasan'] = $fotoUlasan;
        }
        
        Ulasan::create($ulasanData);
        
        return redirect()->route('customer.transactions.show', $request->transaksi_id)
            ->with('success', 'Ulasan berhasil dikirim. Menunggu persetujuan admin.');
    }
    
    public function show($id)
    {
        $review = Ulasan::with(['produk', 'transaksi.paymentMethod'])
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('customer.reviews.show', compact('review'));
    }

    public function edit($id)
    {
        $review = Ulasan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        return view('customer.reviews.edit', compact('review'));
    }
    
    public function update(Request $request, $id)
    {
        $review = Ulasan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'judul' => 'nullable|string|max:200',
            'komentar' => 'required|string',
            'foto_ulasan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $reviewData = $request->only(['rating', 'judul', 'komentar']);
        $reviewData['status'] = 'pending'; // Needs re-approval after edit
        
        // Handle new review photos
        $fotoUlasan = $review->foto_ulasan ?? [];
        if ($request->hasFile('foto_ulasan')) {
            foreach ($request->file('foto_ulasan') as $image) {
                $filename = 'review-' . Auth::id() . '-' . time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('reviews', $filename, 'public');
                $fotoUlasan[] = $path;
            }
            $reviewData['foto_ulasan'] = $fotoUlasan;
        }
        
        $review->update($reviewData);
        
        return redirect()->route('customer.transactions.show', $review->transaksi_id)
            ->with('success', 'Ulasan berhasil diperbarui. Menunggu persetujuan admin.');
    }
    
    public function destroy($id)
    {
        $review = Ulasan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        // Delete review photos
        if ($review->foto_ulasan) {
            foreach ($review->foto_ulasan as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }
        
        $transactionId = $review->transaksi_id;
        $review->delete();
        
        return redirect()->route('customer.transactions.show', $transactionId)
            ->with('success', 'Ulasan berhasil dihapus.');
    }
    
    public function deletePhoto(Request $request, $id)
    {
        $review = Ulasan::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $imagePath = $request->get('image_path');
        
        $photos = $review->foto_ulasan ?? [];
        if (($key = array_search($imagePath, $photos)) !== false) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            unset($photos[$key]);
            $review->update(['foto_ulasan' => array_values($photos)]);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function myReviews()
    {
        $reviews = Ulasan::with(['produk', 'transaksi'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('customer.reviews.index', compact('reviews'));
    }
}