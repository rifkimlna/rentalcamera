<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Brand;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'brand']);
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        
        // Filter berdasarkan brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan kata kunci
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi_singkat', 'like', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $brands = Brand::where('status', 'active')->get();
        $categories = KategoriProduk::where('status', 'active')->get();
        
        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function create()
    {
        $brands = Brand::where('status', 'active')->get();
        $categories = KategoriProduk::where('status', 'active')->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_produk,id',
            'brand_id' => 'required|exists:brands,id',
            'kode_produk' => 'required|unique:produk,kode_produk|max:50',
            'nama_produk' => 'required|max:200',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi_lengkap' => 'nullable|string',
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'nullable|numeric|min:0',
            'harga_per_bulan' => 'nullable|numeric|min:0',
            'stok_total' => 'required|integer|min:0',
            'minimum_sewa' => 'required|integer|min:1',
            'maximum_sewa' => 'required|integer|min:1',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_tambahan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable,maintenance',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'berat' => 'nullable|numeric|min:0',
            'dimensi' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'tahun_pembuatan' => 'nullable|date_format:Y',
            'kondisi' => 'required|in:baru,bekas_excellent,bekas_good,bekas_fair',
            'spesifikasi.*.key' => 'required_with:spesifikasi.*.value',
            'spesifikasi.*.value' => 'required_with:spesifikasi.*.key',
            'fitur' => 'nullable|string',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['nama_produk']) . '-' . Str::random(6);
        
        // Handle spesifikasi JSON
        if ($request->filled('spesifikasi')) {
            $spesifikasi = [];
            foreach ($request->spesifikasi as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $spesifikasi[$spec['key']] = $spec['value'];
                }
            }
            $validated['spesifikasi'] = !empty($spesifikasi) ? json_encode($spesifikasi) : null;
        } else {
            $validated['spesifikasi'] = null;
        }
        
        // Handle gambar utama
        if ($request->hasFile('gambar_utama')) {
            $file = $request->file('gambar_utama');
            $filename = 'product-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['gambar_utama'] = $path;
        }
        
        // Handle gambar tambahan
        $gambarTambahan = [];
        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $image) {
                $filename = 'product-additional-' . time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products/additional', $filename, 'public');
                $gambarTambahan[] = $path;
            }
            $validated['gambar_tambahan'] = !empty($gambarTambahan) ? json_encode($gambarTambahan) : null;
        } else {
            $validated['gambar_tambahan'] = null;
        }
        
        // Set default values
        $validated['stok_tersedia'] = $validated['stok_total'];
        $validated['stok_dipinjam'] = 0;
        $validated['stok_rusak'] = 0;
        $validated['rating'] = 0;
        $validated['jumlah_ulasan'] = 0;
        $validated['jumlah_dipesan'] = 0;
        
        // Handle boolean values
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        
        // Create product
        $product = Produk::create($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

public function show($id)
{
    // Tambahkan 'detailTransaksis.transaksi.user' untuk eager loading
    $product = Produk::with([
        'kategori', 
        'brand', 
        'maintenance', 
        'ulasan.user',
        'detailTransaksis.transaksi.user'
    ])->findOrFail($id);
    
    return view('admin.products.show', compact('product'));
}

    public function edit($id)
    {
        $product = Produk::findOrFail($id);
        $brands = Brand::where('status', 'active')->get();
        $categories = KategoriProduk::where('status', 'active')->get();
        
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Produk::findOrFail($id);
        
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_produk,id',
            'brand_id' => 'required|exists:brands,id',
            'kode_produk' => 'required|unique:produk,kode_produk,' . $id . '|max:50',
            'nama_produk' => 'required|max:200',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi_lengkap' => 'nullable|string',
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'nullable|numeric|min:0',
            'harga_per_bulan' => 'nullable|numeric|min:0',
            'stok_total' => 'required|integer|min:0',
            'minimum_sewa' => 'required|integer|min:1',
            'maximum_sewa' => 'required|integer|min:1',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_tambahan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable,maintenance',
            'is_featured' => 'boolean',
            'is_recommended' => 'boolean',
            'berat' => 'nullable|numeric|min:0',
            'dimensi' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'tahun_pembuatan' => 'nullable|date_format:Y',
            'kondisi' => 'required|in:baru,bekas_excellent,bekas_good,bekas_fair',
            'spesifikasi.*.key' => 'required_with:spesifikasi.*.value',
            'spesifikasi.*.value' => 'required_with:spesifikasi.*.key',
            'fitur' => 'nullable|string',
        ]);
        
        // Update slug if name changed
        if ($product->nama_produk !== $validated['nama_produk']) {
            $validated['slug'] = Str::slug($validated['nama_produk']) . '-' . Str::random(6);
        }
        
        // Handle spesifikasi JSON
        if ($request->filled('spesifikasi')) {
            $spesifikasi = [];
            foreach ($request->spesifikasi as $spec) {
                if (!empty($spec['key']) && !empty($spec['value'])) {
                    $spesifikasi[$spec['key']] = $spec['value'];
                }
            }
            $validated['spesifikasi'] = !empty($spesifikasi) ? json_encode($spesifikasi) : null;
        } else {
            $validated['spesifikasi'] = null;
        }
        
        // Handle gambar utama
        if ($request->hasFile('gambar_utama')) {
            // Delete old image if exists
            if ($product->gambar_utama && Storage::disk('public')->exists($product->gambar_utama)) {
                Storage::disk('public')->delete($product->gambar_utama);
            }
            
            $file = $request->file('gambar_utama');
            $filename = 'product-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['gambar_utama'] = $path;
        }
        
        // Handle gambar tambahan
        if ($request->hasFile('gambar_tambahan')) {
            $gambarTambahan = json_decode($product->gambar_tambahan, true) ?? [];
            foreach ($request->file('gambar_tambahan') as $image) {
                $filename = 'product-additional-' . time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products/additional', $filename, 'public');
                $gambarTambahan[] = $path;
            }
            $validated['gambar_tambahan'] = !empty($gambarTambahan) ? json_encode($gambarTambahan) : null;
        }
        
        // Update stok_tersedia based on new stok_total
        $validated['stok_tersedia'] = $validated['stok_total'] - $product->stok_dipinjam - $product->stok_rusak;
        
        // Handle boolean values
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Produk::findOrFail($id);
        
        // Check if product has related transactions
        if ($product->jumlah_dipesan > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus produk yang sudah pernah dipesan.');
        }
        
        // Delete images
        if ($product->gambar_utama && Storage::disk('public')->exists($product->gambar_utama)) {
            Storage::disk('public')->delete($product->gambar_utama);
        }
        
        if ($product->gambar_tambahan) {
            $images = json_decode($product->gambar_tambahan, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function deleteImage(Request $request, $id)
    {
        try {
            $request->validate([
                'image_path' => 'required|string',
                'type' => 'required|in:utama,tambahan'
            ]);
            
            $product = Produk::findOrFail($id);
            $imagePath = $request->image_path;
            
            // Debug logging
            Log::info('Delete image request:', [
                'product_id' => $id,
                'image_path' => $imagePath,
                'type' => $request->type,
                'index' => $request->index,
                'storage_disk' => 'public',
                'storage_exists' => Storage::disk('public')->exists($imagePath),
                'storage_path' => storage_path('app/public/' . $imagePath)
            ]);
            
            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($imagePath)) {
                // Hapus file dari storage
                Storage::disk('public')->delete($imagePath);
                
                // Update database
                if ($request->type === 'utama') {
                    $product->gambar_utama = null;
                } else {
                    $additionalImages = json_decode($product->gambar_tambahan, true) ?? [];
                    
                    // Cari dan hapus gambar dari array
                    $keyToRemove = null;
                    foreach ($additionalImages as $key => $image) {
                        // Bandingkan dengan path lengkap atau hanya nama file
                        if ($image === $imagePath || basename($image) === basename($imagePath)) {
                            $keyToRemove = $key;
                            break;
                        }
                    }
                    
                    if ($keyToRemove !== null) {
                        unset($additionalImages[$keyToRemove]);
                        // Reset array index
                        $additionalImages = array_values($additionalImages);
                        
                        if (empty($additionalImages)) {
                            $product->gambar_tambahan = null;
                        } else {
                            $product->gambar_tambahan = json_encode($additionalImages);
                        }
                    }
                }
                
                $product->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus.'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan di storage.',
                'debug' => [
                    'requested_path' => $imagePath,
                    'full_storage_path' => storage_path('app/public/' . $imagePath),
                    'exists' => Storage::disk('public')->exists($imagePath),
                    'files_in_directory' => Storage::disk('public')->files(dirname($imagePath))
                ]
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Delete image error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $product = Produk::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:available,unavailable,maintenance'
        ]);
        
        $product->update(['status' => $request->status]);
        
        return redirect()->back()
            ->with('success', 'Status produk berhasil diperbarui.');
    }

    public function updateStock(Request $request, $id)
    {
        $product = Produk::findOrFail($id);
        
        $request->validate([
            'type' => 'required|in:add,subtract,damage',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);
        
        $quantity = $request->quantity;
        
        switch ($request->type) {
            case 'add':
                $product->increment('stok_total', $quantity);
                $product->increment('stok_tersedia', $quantity);
                $message = 'Stok berhasil ditambahkan.';
                break;
                
            case 'subtract':
                if ($product->stok_total < $quantity) {
                    return redirect()->back()
                        ->with('error', 'Jumlah pengurangan melebihi stok total.');
                }
                $product->decrement('stok_total', $quantity);
                $product->decrement('stok_tersedia', $quantity);
                $message = 'Stok berhasil dikurangi.';
                break;
                
            case 'damage':
                if ($product->stok_tersedia < $quantity) {
                    return redirect()->back()
                        ->with('error', 'Jumlah stok rusak melebihi stok tersedia.');
                }
                $product->increment('stok_rusak', $quantity);
                $product->decrement('stok_tersedia', $quantity);
                $message = 'Stok rusak berhasil dicatat.';
                break;
        }
        
        $product->refresh();
        
        return redirect()->back()
            ->with('success', $message);
    }

    public function brands()
    {
        $brands = Brand::orderBy('nama_brand')->get();
        return view('admin.products.brands', compact('brands'));
    }

    public function storeBrand(Request $request)
    {
        $validated = $request->validate([
            'nama_brand' => 'required|string|max:100|unique:brands,nama_brand',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        
        Brand::create($validated);
        
        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand berhasil ditambahkan.');
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $validated = $request->validate([
            'nama_brand' => 'required|string|max:100|unique:brands,nama_brand,' . $id,
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        
        $brand->update($validated);
        
        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroyBrand($id)
    {
        $brand = Brand::findOrFail($id);
        
        // Check if brand has products
        if ($brand->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus brand yang memiliki produk.');
        }
        
        $brand->delete();
        
        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand berhasil dihapus.');
    }

    public function categories()
    {
        $categories = KategoriProduk::orderBy('nama_kategori')->get();
        return view('admin.products.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_produk,nama_kategori',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        
        KategoriProduk::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = KategoriProduk::findOrFail($id);
        
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_produk,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory($id)
    {
        $category = KategoriProduk::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki produk.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}