<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use App\Models\Brand;
use App\Models\Portfolio;
use App\Models\Ulasan;
use App\Models\Transaksis;
use App\Models\Studio;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $featuredProducts = Produk::where('is_featured', 1)
            ->where('status', 'available')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $recommendedProducts = Produk::where('is_recommended', 1)
            ->where('status', 'available')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $categories = KategoriProduk::where('status', 'active')
            ->with(['products' => fn ($q) => $q->where('status', 'available')->orderBy('created_at', 'desc')])
            ->orderBy('urutan', 'asc')
            ->get();

        $studios = Studio::where('status', 'active')
            ->orderBy('rating', 'desc')
            ->limit(4)
            ->get();

        $layanans = Layanan::where('status', 'active')
            ->orderBy('rating', 'desc')
            ->limit(4)
            ->get();

        $testimonials = Ulasan::with('user')
            ->approved()
            ->whereNotNull('komentar')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $stats = [
            'products' => Produk::where('status', 'available')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'transactions' => Transaksis::whereIn('status_pembayaran', ['settlement', 'capture'])->count(),
            'satisfaction' => 98,
        ];

        $faqs = [
            ['question' => 'Berapa lama proses penyewaan?', 'answer' => 'Proses penyewaan dapat diselesaikan dalam waktu 1-2 jam setelah pembayaran berhasil.'],
            ['question' => 'Bagaimana cara pengembalian alat?', 'answer' => 'Anda dapat mengembalikan alat langsung ke toko kami. Pastikan alat dalam kondisi baik dan lengkap.'],
            ['question' => 'Boleh memperpanjang masa sewa?', 'answer' => 'Ya, Anda dapat memperpanjang masa sewa dengan menghubungi customer service kami sebelum masa sewa berakhir.'],
            ['question' => 'Bagaimana jika alat rusak?', 'answer' => 'Jika terjadi kerusakan, Anda akan dikenakan biaya perbaikan sesuai dengan tingkat kerusakan.'],
        ];

        return view('home', compact('featuredProducts', 'recommendedProducts', 'categories', 'studios', 'layanans', 'testimonials', 'stats', 'faqs'));
    }

    /**
     * Show about page.
     */
    public function about()
    {
        $portfolios = Portfolio::active()->ordered()->get();
        return view('about', compact('portfolios'));
    }

    /**
     * Show contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Show products page.
     */
    public function products(Request $request)
    {
        $query = Produk::where('status', 'available')
            ->where('stok_tersedia', '>', 0);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('deskripsi_singkat', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category')) {
            $category = KategoriProduk::where('slug', $request->category)->first();
            if ($category) {
                $query->where('kategori_id', $category->id);
            }
        }

        // Brand filter
        if ($request->has('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Price filter
        if ($request->has('min_price')) {
            $query->where('harga_per_hari', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('harga_per_hari', '<=', $request->max_price);
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

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show product detail.
     */
    public function productDetail($slug)
    {
        $product = Produk::where('slug', $slug)
            ->where('status', 'available')
            ->firstOrFail();

        // Related products
        $relatedProducts = Produk::where('kategori_id', $product->kategori_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->where('stok_tersedia', '>', 0)
            ->limit(4)
            ->get();

        return view('products.detail', compact('product', 'relatedProducts'));
    }

    /**
     * Show pricing page.
     */
    public function pricing()
    {
        $products = Produk::where('status', 'available')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('harga_per_hari', 'asc')
            ->limit(6)
            ->get();

        return view('pricing', compact('products'));
    }

    /**
     * Show FAQ page.
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Berapa lama proses penyewaan?',
                'answer' => 'Proses penyewaan dapat diselesaikan dalam waktu 1-2 jam setelah pembayaran berhasil. Pengiriman dilakukan sesuai jadwal yang Anda pilih.'
            ],
            [
                'question' => 'Bagaimana cara pengembalian alat?',
                'answer' => 'Anda dapat mengembalikan alat langsung ke toko kami atau menggunakan jasa kurir yang telah kami sediakan. Pastikan alat dalam kondisi baik dan lengkap.'
            ],
            [
                'question' => 'Boleh memperpanjang masa sewa?',
                'answer' => 'Ya, Anda dapat memperpanjang masa sewa dengan menghubungi customer service kami sebelum masa sewa berakhir.'
            ],
            [
                'question' => 'Bagaimana jika alat rusak?',
                'answer' => 'Jika terjadi kerusakan, Anda akan dikenakan biaya perbaikan sesuai dengan tingkat kerusakan. Disarankan untuk menggunakan asuransi sewa.'
            ],
        ];

        return view('faq', compact('faqs'));
    }
}