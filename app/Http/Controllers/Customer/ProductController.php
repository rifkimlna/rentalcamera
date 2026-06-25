<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'brand'])
            ->where('status', 'available')
            ->where('stok_tersedia', '>', 0);
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }
        
        // Filter berdasarkan brand
        if ($request->filled('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }
        
        // Filter berdasarkan harga
        if ($request->filled('min_price')) {
            $query->where('harga_per_hari', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('harga_per_hari', '<=', $request->max_price);
        }
        
        // Filter berdasarkan fitur
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }
        
        if ($request->filled('recommended')) {
            $query->where('is_recommended', true);
        }
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi_singkat', 'like', "%{$search}%")
                  ->orWhere('deskripsi_lengkap', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('harga_per_hari', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga_per_hari', 'desc');
                break;
            case 'popular':
                $query->orderBy('jumlah_dipesan', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        $categories = KategoriProduk::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        
        return view('customer.products.index', compact('products', 'categories', 'brands'));
    }
    
    public function show($slug)
    {
        $product = Produk::with(['kategori', 'brand', 'ulasan' => function($query) {
            $query->where('status', 'approved')
                ->with('user')
                ->orderBy('created_at', 'desc');
        }])
        ->where('slug', $slug)
        ->where('status', 'available')
        ->firstOrFail();
        
        // Related products
        $relatedProducts = Produk::where('kategori_id', $product->kategori_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->where('stok_tersedia', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
    
    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function byCategory($slug)
    {
        $request = request()->merge(['kategori' => $slug]);
        return $this->index($request);
    }

    public function byBrand($slug)
    {
        $request = request()->merge(['brand' => $slug]);
        return $this->index($request);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'tanggal_sewa' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_sewa',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Produk::findOrFail($request->product_id);
        
        // Check stock availability
        if ($product->stok_tersedia < $request->quantity) {
            return response()->json([
                'available' => false,
                'message' => 'Stok tidak mencukupi untuk tanggal yang diminta.',
                'available_stock' => $product->stok_tersedia
            ]);
        }
        
        // Check rental period
        $tanggalSewa = new \DateTime($request->tanggal_sewa);
        $tanggalKembali = new \DateTime($request->tanggal_kembali);
        $lamaSewa = $tanggalSewa->diff($tanggalKembali)->days;
        if ($lamaSewa < 1) $lamaSewa = 1;
        
        if ($lamaSewa < $product->minimum_sewa) {
            return response()->json([
                'available' => false,
                'message' => 'Minimum sewa untuk produk ini adalah ' . $product->minimum_sewa . ' hari.'
            ]);
        }
        
        if ($lamaSewa > $product->maximum_sewa) {
            return response()->json([
                'available' => false,
                'message' => 'Maksimum sewa untuk produk ini adalah ' . $product->maximum_sewa . ' hari.'
            ]);
        }
        
        // Calculate price
        $totalPrice = $product->harga_per_hari * $lamaSewa * $request->quantity;
        $deposit = $product->harga_per_hari * 2 * $request->quantity; // 2 days deposit
        
        return response()->json([
            'available' => true,
            'rental_days' => $lamaSewa,
            'price_per_day' => $product->harga_per_hari,
            'total_price' => $totalPrice,
            'deposit' => $deposit,
            'subtotal' => $totalPrice + $deposit,
            'available_stock' => $product->stok_tersedia,
            'start_date' => $request->tanggal_sewa,
            'end_date' => $request->tanggal_kembali,
        ]);
    }
}