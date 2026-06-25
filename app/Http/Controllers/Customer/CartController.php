<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate totals
        $subtotal = 0;
        $depositTotal = 0;
        
        foreach ($cartItems as $item) {
            if ($item->produk) {
                $itemPrice = $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
                $itemDeposit = $item->produk->harga_per_hari * 2 * $item->jumlah;
                $subtotal += $itemPrice;
                $depositTotal += $itemDeposit;
            }
        }
        
        return view('customer.cart.index', compact('cartItems', 'subtotal', 'depositTotal'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'tanggal_sewa' => 'nullable|date|after_or_equal:today',
            'tanggal_kembali' => 'nullable|date|after:tanggal_sewa',
            'jumlah' => 'required|integer|min:1',
        ]);
        
        $product = Produk::findOrFail($request->product_id);
        
        // Check product availability
        if ($product->status !== 'available') {
            return redirect()->back()
                ->with('error', 'Produk tidak tersedia untuk disewa.');
        }
        
        if ($product->stok_tersedia < $request->jumlah) {
            return redirect()->back()
                ->with('error', 'Stok produk tidak mencukupi.');
        }
        
        // Check rental period
        $tanggalSewa = new \DateTime($request->tanggal_sewa);
        $tanggalKembali = new \DateTime($request->tanggal_kembali);
        $lamaSewa = $tanggalSewa->diff($tanggalKembali)->days;
        if ($lamaSewa < 1) $lamaSewa = 1;
        
        if ($lamaSewa < $product->minimum_sewa) {
            return redirect()->back()
                ->with('error', 'Minimum sewa untuk produk ini adalah ' . $product->minimum_sewa . ' hari.');
        }
        
        if ($lamaSewa > $product->maximum_sewa) {
            return redirect()->back()
                ->with('error', 'Maksimum sewa untuk produk ini adalah ' . $product->maximum_sewa . ' hari.');
        }
        
        // Check if item already exists in cart
        $existingItem = Keranjang::where('user_id', Auth::id())
            ->where('produk_id', $product->id)
            ->where('tanggal_sewa', $request->tanggal_sewa)
            ->where('tanggal_kembali', $request->tanggal_kembali)
            ->first();
        
        if ($existingItem) {
            $existingItem->update([
                'jumlah' => $existingItem->jumlah + $request->jumlah
            ]);
        } else {
            Keranjang::create([
                'user_id' => Auth::id(),
                'produk_id' => $product->id,
                'jumlah' => $request->jumlah,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_kembali' => $request->tanggal_kembali,
            ]);
        }
        
        return redirect()->route('customer.cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function directRent(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'tanggal_sewa' => 'nullable|date|after_or_equal:today',
            'tanggal_kembali' => 'nullable|date|after:tanggal_sewa',
            'jumlah' => 'required|integer|min:1',
        ]);

        $product = Produk::findOrFail($request->product_id);

        if ($product->status !== 'available') {
            return response()->json(['success' => false, 'message' => 'Produk tidak tersedia.'], 400);
        }

        if ($product->stok_tersedia < $request->jumlah) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi.'], 400);
        }

        $tanggalSewa = new \DateTime($request->tanggal_sewa);
        $tanggalKembali = new \DateTime($request->tanggal_kembali);
        $lamaSewa = $tanggalSewa->diff($tanggalKembali)->days;
        if ($lamaSewa < 1) $lamaSewa = 1;

        if ($lamaSewa < $product->minimum_sewa) {
            return response()->json(['success' => false, 'message' => 'Minimum sewa ' . $product->minimum_sewa . ' hari.'], 400);
        }

        if ($lamaSewa > $product->maximum_sewa) {
            return response()->json(['success' => false, 'message' => 'Maksimum sewa ' . $product->maximum_sewa . ' hari.'], 400);
        }

        session(['direct_rent' => [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'gambar_utama' => $product->gambar_utama,
            'harga_per_hari' => $product->harga_per_hari,
            'brand_nama' => $product->brand->nama_brand ?? null,
            'tanggal_sewa' => $request->tanggal_sewa,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jumlah' => $request->jumlah,
            'lama_sewa' => $lamaSewa,
        ]]);

        return response()->json(['success' => true]);
    }
    
    public function update(Request $request, $id)
    {
        $cartItem = Keranjang::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'tanggal_sewa' => 'nullable|date|after_or_equal:today',
            'tanggal_kembali' => 'nullable|date|after:tanggal_sewa',
        ]);
        
        $product = Produk::findOrFail($cartItem->produk_id);
        
        // Check stock availability for new quantity
        if ($product->stok_tersedia < $request->jumlah) {
            return redirect()->back()
                ->with('error', 'Stok produk tidak mencukupi.');
        }
        
        // Check rental period only if dates are provided
        if ($request->filled('tanggal_sewa') && $request->filled('tanggal_kembali')) {
            $tanggalSewa = new \DateTime($request->tanggal_sewa);
            $tanggalKembali = new \DateTime($request->tanggal_kembali);
            $lamaSewa = $tanggalSewa->diff($tanggalKembali)->days;
            if ($lamaSewa < 1) $lamaSewa = 1;
            
            if ($lamaSewa < $product->minimum_sewa) {
                return redirect()->back()
                    ->with('error', 'Minimum sewa untuk produk ini adalah ' . $product->minimum_sewa . ' hari.');
            }
            
            if ($lamaSewa > $product->maximum_sewa) {
                return redirect()->back()
                    ->with('error', 'Maksimum sewa untuk produk ini adalah ' . $product->maximum_sewa . ' hari.');
            }
        }
        
        $data = ['jumlah' => $request->jumlah];
        if ($request->filled('tanggal_sewa')) {
            $data['tanggal_sewa'] = $request->tanggal_sewa;
        }
        if ($request->filled('tanggal_kembali')) {
            $data['tanggal_kembali'] = $request->tanggal_kembali;
        }
        $cartItem->update($data);
        
        return redirect()->back()
            ->with('success', 'Keranjang berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $cartItem = Keranjang::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $cartItem->delete();
        
        return redirect()->back()
            ->with('success', 'Item berhasil dihapus dari keranjang.');
    }
    
    public function clear()
    {
        Keranjang::where('user_id', Auth::id())->delete();
        
        return redirect()->route('customer.cart.index')
            ->with('success', 'Keranjang berhasil dikosongkan.');
    }
    
    public function updateDates(Request $request, $id)
    {
        $cartItem = Keranjang::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'tanggal_sewa' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_sewa',
        ]);

        $cartItem->update([
            'tanggal_sewa' => $request->tanggal_sewa,
            'tanggal_kembali' => $request->tanggal_kembali,
        ]);

        return redirect()->back()->with('success', 'Tanggal sewa berhasil diperbarui.');
    }

    public function getCartSummary()
    {
        $cartItems = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->get();
        
        $subtotal = 0;
        $depositTotal = 0;
        $itemsCount = 0;
        
        foreach ($cartItems as $item) {
            if ($item->produk) {
                $itemPrice = $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
                $itemDeposit = $item->produk->harga_per_hari * 2 * $item->jumlah;
                $subtotal += $itemPrice;
                $depositTotal += $itemDeposit;
                $itemsCount += $item->jumlah;
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'items_count' => $itemsCount,
                'subtotal' => $subtotal,
                'deposit_total' => $depositTotal,
                'total' => $subtotal + $depositTotal
            ]
        ]);
    }
}
