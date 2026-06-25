<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;

use App\Models\DetailTransaksis;
use App\Models\Pengiriman;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\PaymentMethod;
use App\Models\ActivityLog;
use App\Models\PaymentLog;
use App\Models\Transaksis;
use App\Models\Produk;
use App\Services\MidtransService;
use Illuminate\Http\Request; // Tambahkan ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Tambahkan ini
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display checkout page.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Cek apakah dari direct rent (Sewa tanpa kart)
        $directRent = session('direct_rent');
        $keranjangItems = collect();

        if ($directRent) {
            $product = Produk::find($directRent['product_id']);
            if ($product) {
                $item = new \stdClass();
                $item->produk_id = $product->id;
                $item->produk = $product;
                $item->lama_sewa = $directRent['lama_sewa'];
                $item->jumlah = $directRent['jumlah'];
                $item->tanggal_sewa = $directRent['tanggal_sewa'];
                $item->tanggal_kembali = $directRent['tanggal_kembali'];
                $item->harga_per_hari = $product->harga_per_hari;
                $item->catatan = null;
                $keranjangItems = collect([$item]);
            }
        } else {
            $keranjangItems = Keranjang::with('produk')
                ->where('user_id', $user->id)
                ->get();
        }
        
        if ($keranjangItems->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang kosong. Silakan tambah produk terlebih dahulu.');
        }
        
        // Validasi tanggal sewa
        foreach ($keranjangItems as $item) {
            if ($item->tanggal_sewa < now()->toDateString()) {
                return redirect()->route('customer.cart.index')
                    ->with('error', "Tanggal sewa untuk {$item->produk->nama_produk} tidak valid.");
            }
            
            if ($item->lama_sewa < $item->produk->minimum_sewa) {
                return redirect()->route('customer.cart.index')
                    ->with('error', "Minimal sewa untuk {$item->produk->nama_produk} adalah {$item->produk->minimum_sewa} hari.");
            }
            
            if ($item->lama_sewa > $item->produk->maximum_sewa) {
                return redirect()->route('customer.cart.index')
                    ->with('error', "Maksimal sewa untuk {$item->produk->nama_produk} adalah {$item->produk->maximum_sewa} hari.");
            }
            
            // Cek stok tersedia
            if ($item->produk->stok_tersedia < $item->jumlah) {
                return redirect()->route('customer.cart.index')
                    ->with('error', "Stok {$item->produk->nama_produk} tidak mencukupi. Stok tersedia: {$item->produk->stok_tersedia}");
            }
        }
        
        // Hitung subtotal
        $subtotal = $keranjangItems->sum(function ($item) {
            return $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
        });
        
        // Ambil metode pembayaran yang aktif
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Ambil voucher yang berlaku
        $vouchers = Voucher::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function($query) use ($user) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', $user->id);
            })
            ->where(function($query) use ($subtotal) {
                $query->whereNull('min_purchase')
                      ->orWhere('min_purchase', '<=', $subtotal);
            })
            ->where(function($query) {
                $query->whereNull('kuota')
                      ->orWhereRaw('kuota_terpakai < kuota');
            })
            ->get();
        
        // Ambil alamat user
        $userAddress = [
            'alamat' => $user->alamat,
            'kota' => $user->kota,
            'provinsi' => $user->provinsi,
            'kode_pos' => $user->kode_pos,
        ];
        
        // Settings untuk biaya
        $shippingFee = config('settings.shipping_fee', 20000);
        $insurancePercentage = config('settings.insurance_percentage', 1);
        $depositPercentage = config('settings.deposit_percentage', 20);
        
        // Hitung biaya asuransi
        $insuranceFee = ($subtotal * $insurancePercentage) / 100;
        
        // Hitung deposit
        $depositAmount = ($subtotal * $depositPercentage) / 100;
        
        return view('customer.checkout.index', compact(
            'keranjangItems',
            'subtotal',
            'paymentMethods',
            'vouchers',
            'userAddress',
            'shippingFee',
            'insuranceFee',
            'depositAmount'
        ));
    }

    /**
     * Process checkout.
     */
    public function process(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'metode_pengambilan' => 'required|in:pickup,delivery,both',
            'metode_pengembalian' => 'required|in:return,pickup,both',
            'alamat_pengiriman' => 'required_if:metode_pengambilan,delivery,both|string',
            'kota_pengiriman' => 'required_if:metode_pengambilan,delivery,both|string',
            'provinsi_pengiriman' => 'required_if:metode_pengambilan,delivery,both|string',
            'kode_pos_pengiriman' => 'required_if:metode_pengambilan,delivery,both|string',
            'catatan' => 'nullable|string',
            'voucher_code' => 'nullable|string',
            'agree_terms' => 'required|accepted',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Cek apakah dari direct rent
            $directRent = session('direct_rent');
            $keranjangItems = collect();

            if ($directRent) {
                $product = Produk::find($directRent['product_id']);
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }
                if ($product->stok_tersedia < $directRent['jumlah']) {
                    throw new \Exception('Stok produk tidak mencukupi.');
                }
                $item = new \stdClass();
                $item->produk_id = $product->id;
                $item->produk = $product;
                $item->lama_sewa = $directRent['lama_sewa'];
                $item->jumlah = $directRent['jumlah'];
                $item->tanggal_sewa = $directRent['tanggal_sewa'];
                $item->tanggal_kembali = $directRent['tanggal_kembali'];
                $item->harga_per_hari = $product->harga_per_hari;
                $item->catatan = null;
                $keranjangItems = collect([$item]);
            } else {
                $keranjangItems = Keranjang::with('produk')
                    ->where('user_id', $user->id)
                    ->get();
            }

            if ($keranjangItems->isEmpty()) {
                throw new \Exception('Keranjang kosong.');
            }
            
            // Hitung subtotal
            $subtotal = 0;
            $lamaSewa = 0;
            
            foreach ($keranjangItems as $item) {
                $hargaProduk = $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
                $subtotal += $hargaProduk;
                
                // Update lama sewa (ambil yang terpanjang)
                if ($item->lama_sewa > $lamaSewa) {
                    $lamaSewa = $item->lama_sewa;
                }
            }
            
            // Hitung diskon voucher jika ada
            $diskon = 0;
            $voucherId = null;
            
            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('kode_voucher', $request->voucher_code)
                    ->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where(function($query) use ($user) {
                        $query->whereNull('user_id')
                              ->orWhere('user_id', $user->id);
                    })
                    ->where(function($query) use ($subtotal) {
                        $query->whereNull('min_purchase')
                              ->orWhere('min_purchase', '<=', $subtotal);
                    })
                    ->where(function($query) {
                        $query->whereNull('kuota')
                              ->orWhereRaw('kuota_terpakai < kuota');
                    })
                    ->first();
                
                if ($voucher) {
                    $voucherId = $voucher->id;
                    
                    if ($voucher->type === 'percentage') {
                        $diskon = ($subtotal * $voucher->value) / 100;
                        if ($voucher->max_discount && $diskon > $voucher->max_discount) {
                            $diskon = $voucher->max_discount;
                        }
                    } elseif ($voucher->type === 'fixed') {
                        $diskon = $voucher->value;
                    } elseif ($voucher->type === 'shipping') {
                        // Diskon pengiriman, akan dihitung nanti
                    }
                }
            }
            
            // Ambil tanggal dari item keranjang
            $tanggalPengambilan = $keranjangItems->min('tanggal_sewa');
            $tanggalPengembalian = $keranjangItems->max('tanggal_kembali');
            $tanggalPengambilan = $tanggalPengambilan ? \Carbon\Carbon::parse($tanggalPengambilan)->format('Y-m-d') : now()->format('Y-m-d');
            $tanggalPengembalian = $tanggalPengembalian ? \Carbon\Carbon::parse($tanggalPengembalian)->format('Y-m-d') : now()->format('Y-m-d');
            // Hitung biaya lainnya
            $shippingFee = ($request->metode_pengambilan === 'pickup') ? 0 : config('settings.shipping_fee', 20000);
            $insurancePercentage = config('settings.insurance_percentage', 1);
            $insuranceFee = ($subtotal * $insurancePercentage) / 100;
            $depositPercentage = config('settings.deposit_percentage', 20);
            $depositAmount = ($subtotal * $depositPercentage) / 100;
            
            // Jika voucher tipe shipping
            if ($voucherId && $voucher->type === 'shipping') {
                $shippingFee -= $voucher->value;
                if ($shippingFee < 0) $shippingFee = 0;
            }
            
            // Hitung total sewa (sebelum deposit)
            $totalSewa = $subtotal - $diskon + $shippingFee + $insuranceFee;
            
            // Biaya admin berdasarkan metode pembayaran
            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $adminFee = $paymentMethod->calculateFee($totalSewa);
            
            // Grand total
            $grandTotal = $totalSewa + $adminFee;
            
            // Buat transaksi
            $transaksi = Transaksis::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'nama_customer' => $user->nama,
                'telepon_customer' => $user->telepon,
                'email_customer' => $user->email,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'kota_pengiriman' => $request->kota_pengiriman,
                'provinsi_pengiriman' => $request->provinsi_pengiriman,
                'kode_pos_pengiriman' => $request->kode_pos_pengiriman,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'kode_voucher' => $request->voucher_code,
                'biaya_pengiriman' => $shippingFee,
                'biaya_asuransi' => $insuranceFee,
                'biaya_lainnya' => $adminFee,
                'total_sewa' => $totalSewa,
                'deposit_amount' => $depositAmount,
                'admin_fee' => $adminFee,
                'grand_total' => $grandTotal,
                'payment_method_id' => $request->payment_method_id,
                'payment_type' => $paymentMethod->midtrans_payment_type,
                'bank' => $paymentMethod->bank_code,
                'status_pembayaran' => 'pending',
                'status_transaksi' => 'menunggu_pembayaran',
                'status_deposit' => 'pending',
                'tanggal_pengambilan' => $tanggalPengambilan . ' 08:00:00',
                'tanggal_pengembalian' => $tanggalPengembalian . ' 20:00:00',
                'lama_sewa' => $lamaSewa,
                'metode_pengambilan' => $request->metode_pengambilan,
                'metode_pengembalian' => $request->metode_pengembalian,
                'catatan' => $request->catatan,
                'payment_expired_at' => now()->addHours(config('settings.payment_expiry_hours', 24)),
            ]);
            
            // Buat detail transaksi & kurangi stok
            foreach ($keranjangItems as $item) {
                $hargaProduk = $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
                $depositItem = ($hargaProduk * $depositPercentage) / 100;
                
                DetailTransaksis::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'kode_produk' => $item->produk->kode_produk,
                    'nama_produk' => $item->produk->nama_produk,
                    'harga_per_hari' => $item->produk->harga_per_hari,
                    'jumlah' => $item->jumlah,
                    'lama_sewa' => $item->lama_sewa,
                    'subtotal' => $hargaProduk,
                    'deposit_amount' => $depositItem,
                    'catatan' => $item->catatan,
                ]);
                
                // Kurangi stok produk
                $item->produk->updateStock('rent', $item->jumlah);
            }
            
            // Buat record pengiriman jika diperlukan
            if (in_array($request->metode_pengambilan, ['delivery', 'both'])) {
                Pengiriman::create([
                    'transaksi_id' => $transaksi->id,
                    'metode' => 'delivery',
                    'biaya' => $shippingFee,
                    'alamat_asal' => config('settings.company_address', 'Jl. Contoh No. 123, Jakarta'),
                    'alamat_tujuan' => $request->alamat_pengiriman,
                    'status' => 'pending',
                    'estimated_delivery' => $tanggalPengambilan . ' 08:00:00',
                ]);
            }
            
            // Update voucher usage jika ada
            if ($voucherId) {
                VoucherUsage::create([
                    'voucher_id' => $voucherId,
                    'user_id' => $user->id,
                    'transaksi_id' => $transaksi->id,
                    'discount_amount' => $diskon,
                ]);
                
                // Update kuota voucher
                $voucher->increment('kuota_terpakai');
            }
            
            // Hapus item dari keranjang (atau dari session untuk direct rent)
            if ($directRent) {
                session()->forget('direct_rent');
            } else {
                $checkedOutIds = $keranjangItems->pluck('id');
                Keranjang::whereIn('id', $checkedOutIds)->delete();
            }
            
            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'type' => 'transaction',
                'description' => "Checkout transaksi #{$transaksi->kode_transaksi} dengan total Rp " . number_format($grandTotal, 0, ',', '.'),
                'ip_address' => $request->ip(),
            ]);
            
            DB::commit();

            // Notifikasi ke admin
            \App\Models\Notification::sendToAdmins('transaction',
                'Transaksi Baru: ' . $transaksi->kode_transaksi,
                $user->nama . ' melakukan penyewaan kamera dengan total Rp ' . number_format($grandTotal, 0, ',', '.'),
                ['transaksi_id' => $transaksi->id, 'type' => 'camera']
            );
            
            // Redirect ke halaman pembayaran
            return redirect()->route('customer.checkout.payment', $transaksi->id)
                ->with('success', 'Checkout berhasil. Silakan lakukan pembayaran.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display payment page.
     */
    public function payment($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaksi = Transaksis::with('paymentMethod')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Jika sudah bayar, redirect ke halaman sukses
        if ($transaksi->status_pembayaran === 'settlement') {
            return redirect()->route('customer.checkout.success', $transaksi->id);
        }
        
        // Generate snap token untuk Midtrans
        $snapToken = null;
        try {
            $snapToken = $this->midtransService->generateSnapToken($transaksi);
        } catch (\Exception $e) {
            Log::error('Midtrans token generation failed: ' . $e->getMessage());
        }
        
        if ($snapToken) {
            $transaksi->update([
                'midtrans_token' => $snapToken,
                'midtrans_redirect_url' => route('customer.checkout.payment', $transaksi->id),
            ]);
            $transaksi->refresh();
        }
        
        return view('customer.checkout.payment', compact('transaksi', 'snapToken'));
    }

    /**
     * Handle Midtrans payment.
     */
    public function midtransCallback(Request $request) // Perbaiki tipe parameter
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );
        
        if ($hashed != $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        
        $transaksi = Transaksis::where('midtrans_order_id', $request->order_id)->first();
        
        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        
        DB::beginTransaction();
        
        try {
            switch ($request->transaction_status) {
                case 'capture':
                case 'settlement':
                    $transaksi->update([
                        'status_pembayaran' => 'settlement',
                        'status_transaksi' => 'dikonfirmasi',
                        'paid_at' => now(),
                        'confirmed_at' => now(),
                    ]);

                    \App\Models\Notification::sendToAdmins('payment',
                        'Pembayaran Kamera Lunas',
                        ($transaksi->user->nama ?? 'User') . ' telah membayar transaksi ' . $transaksi->kode_transaksi,
                        ['transaksi_id' => $transaksi->id, 'type' => 'camera']
                    );
                    break;
                    
                case 'pending':
                    $transaksi->update([
                        'status_pembayaran' => 'pending',
                        'status_transaksi' => 'menunggu_pembayaran',
                    ]);
                    break;
                    
                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failure':
                    $transaksi->update([
                        'status_pembayaran' => $request->transaction_status,
                        'status_transaksi' => 'dibatalkan',
                        'cancelled_at' => now(),
                    ]);
                    break;
            }
            
            // Stok sudah dikurangi saat checkout process
            
            DB::commit();
            
            return response()->json(['message' => 'Callback processed']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error processing callback: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display success page.
     */
    public function success($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaksi = Transaksis::with('detailTransaksis', 'paymentMethod')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('customer.checkout.success', compact('transaksi'));
    }

    /**
     * Display pending page.
     */
    public function pending($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaksi = Transaksis::with('paymentMethod')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('customer.checkout.pending', compact('transaksi'));
    }

    /**
     * Display failed page.
     */
    public function failed($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $transaksi = Transaksis::with('paymentMethod')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('customer.checkout.failed', compact('transaksi'));
    }

    /**
     * Check voucher validity.
     */
    public function checkVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $voucher = Voucher::where('kode_voucher', $request->voucher_code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function($query) use ($user) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', $user->id);
            })
            ->where(function($query) use ($request) {
                $query->whereNull('min_purchase')
                      ->orWhere('min_purchase', '<=', $request->subtotal);
            })
            ->where(function($query) {
                $query->whereNull('kuota')
                      ->orWhereRaw('kuota_terpakai < kuota');
            })
            ->first();
        
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid atau sudah tidak berlaku.',
            ]);
        }
        
        // Cek apakah user sudah menggunakan voucher ini
        $alreadyUsed = VoucherUsage::where('voucher_id', $voucher->id)
            ->where('user_id', $user->id)
            ->exists();
        
        if ($alreadyUsed && $voucher->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah menggunakan voucher ini.',
            ]);
        }
        
        // Hitung diskon
        $diskon = 0;
        $maxDiscount = null;
        
        if ($voucher->type === 'percentage') {
            $diskon = ($request->subtotal * $voucher->value) / 100;
            $maxDiscount = $voucher->max_discount;
            if ($maxDiscount && $diskon > $maxDiscount) {
                $diskon = $maxDiscount;
            }
        } elseif ($voucher->type === 'fixed') {
            $diskon = $voucher->value;
        }
        
        return response()->json([
            'success' => true,
            'voucher' => [
                'id' => $voucher->id,
                'name' => $voucher->nama_voucher,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'max_discount' => $voucher->max_discount,
                'diskon' => $diskon,
            ],
            'message' => 'Voucher valid.',
        ]);
    }

    /**
     * Calculate shipping cost.
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'kota' => 'required|string',
            'provinsi' => 'required|string',
        ]);
        
        $baseFee = config('settings.shipping_fee', 20000);
        $expressFee = config('settings.express_shipping_cost', 30000);
        
        // Di aplikasi nyata, Anda akan menghitung berdasarkan jarak atau kurir
        // Ini hanya contoh sederhana
        $shippingOptions = [
            [
                'name' => 'Reguler (2-3 hari)',
                'cost' => $baseFee,
                'code' => 'reguler',
            ],
            [
                'name' => 'Express (1 hari)',
                'cost' => $expressFee,
                'code' => 'express',
            ],
            [
                'name' => 'Same Day Delivery',
                'cost' => $expressFee * 1.5,
                'code' => 'same_day',
                'note' => 'Hanya tersedia jika order sebelum ' . config('settings.same_day_delivery_cutoff', '14:00'),
            ],
        ];
        
        return response()->json([
            'success' => true,
            'options' => $shippingOptions,
        ]);
    }

    /**
     * Check payment status via AJAX.
     */
    public function checkStatus($id)
    {
        $transaksi = Transaksis::with("paymentMethod")
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'status' => $transaksi->status_pembayaran,
            'status_transaksi' => $transaksi->status_transaksi,
        ]);
    }

    /**
     * Handle Midtrans notification webhook (public).
     */
    public function midtransNotification(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512",
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed != $request->signature_key) {
            Log::error('Invalid Midtrans signature', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaksi = Transaksis::where('midtrans_order_id', $request->order_id)->first();

        if (!$transaksi) {
            Log::error('Transaction not found for Midtrans notification', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();

        try {
            switch ($request->transaction_status) {
                case 'capture':
                case 'settlement':
                    $transaksi->update([
                        'status_pembayaran' => 'settlement',
                        'status_transaksi' => 'dikonfirmasi',
                        'paid_at' => now(),
                        'confirmed_at' => now(),
                    ]);

                    foreach ($transaksi->detailTransaksis as $detail) {
                        if ($detail->produk) {
                            $detail->produk->update([
                                'stok_dipinjam' => $detail->produk->stok_dipinjam + $detail->jumlah,
                                'stok_tersedia' => $detail->produk->stok_total -
                                                  ($detail->produk->stok_dipinjam + $detail->jumlah) -
                                                  $detail->produk->stok_rusak,
                            ]);
                        }
                    }

                    \App\Models\Notification::sendToAdmins('payment',
                        'Pembayaran Kamera Lunas',
                        ($transaksi->user->nama ?? 'User') . ' telah membayar transaksi ' . $transaksi->kode_transaksi . ' sebesar Rp ' . number_format($transaksi->grand_total, 0, ',', '.'),
                        ['transaksi_id' => $transaksi->id, 'type' => 'camera']
                    );
                    break;

                case 'pending':
                    $transaksi->update([
                        'status_pembayaran' => 'pending',
                        'status_transaksi' => 'menunggu_pembayaran',
                    ]);
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failure':
                    $transaksi->update([
                        'status_pembayaran' => $request->transaction_status,
                        'status_transaksi' => 'dibatalkan',
                        'cancelled_at' => now(),
                    ]);
                    break;
            }

            PaymentLog::create([
                'transaksi_id' => $transaksi->id,
                'order_id' => $request->order_id,
                'transaction_id' => $request->transaction_id,
                'transaction_status' => $request->transaction_status,
                'payment_type' => $request->payment_type,
                'gross_amount' => $request->gross_amount,
                'fraud_status' => $request->fraud_status,
                'status_code' => $request->status_code,
                'bank' => $request->bank,
                'va_number' => isset($request->va_numbers[0]) ? $request->va_numbers[0]->va_number : null,
                'merchant_id' => $request->merchant_id,
                'request_data' => json_encode($request->all()),
            ]);

            DB::commit();

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing Midtrans notification: ' . $e->getMessage(), [
                'order_id' => $request->order_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}
