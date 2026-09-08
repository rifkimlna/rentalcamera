<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\DetailTransaksis;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\PaymentMethod;
use App\Models\ActivityLog;
use App\Models\Transaksis;
use App\Models\Produk;
use App\Models\Studio;
use App\Models\StudioBooking;
use App\Models\PaketStudio;
use App\Models\Layanan;
use App\Models\LayananBooking;
use App\Models\PaketLayanan;
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
        
        // Cek apakah dari direct rent, direct studio, atau direct layanan
        $directRent = session('direct_rent');
        $directStudio = session('direct_studio');
        $directLayanan = session('direct_layanan');
        $keranjangItems = collect();
        $isStudioBooking = false;
        $isLayananBooking = false;
        $bookingData = null;

        if ($directStudio) {
            $isStudioBooking = true;
            $bookingData = $directStudio;
            $subtotal = $directStudio['total_harga'];
        } elseif ($directLayanan) {
            $isLayananBooking = true;
            $bookingData = $directLayanan;
            $subtotal = $directLayanan['total_harga'];
        } elseif ($directRent) {
            $product = Produk::find($directRent['product_id']);
            if ($product) {
                $item = new \stdClass();
                $item->produk_id = $product->id;
                $item->produk = $product;
                $item->lama_sewa = $directRent['lama_sewa'];
                $item->jumlah = $directRent['jumlah'];
                $item->tanggal_sewa = $directRent['tanggal_sewa'];
                $item->tanggal_kembali = $directRent['tanggal_kembali'];
                $item->jam_mulai = $directRent['jam_mulai'] ?? '08:00';
                $item->harga_per_hari = $product->harga_per_hari;
                $item->catatan = null;
                $keranjangItems = collect([$item]);
            }
        } else {
            return redirect()->route('customer.products.index')
                ->with('error', 'Silakan pilih produk dan tanggal sewa terlebih dahulu.');
        }

        if (!$isStudioBooking && !$isLayananBooking && $keranjangItems->isEmpty()) {
            return redirect()->route('customer.products.index')
                ->with('error', 'Silakan pilih produk dan tanggal sewa terlebih dahulu.');
        }

        if (!$isStudioBooking && !$isLayananBooking) {
            // Validasi tanggal sewa
            foreach ($keranjangItems as $item) {
                if ($item->tanggal_sewa < now()->toDateString()) {
                    return redirect()->route('customer.products.index')
                        ->with('error', "Tanggal sewa untuk {$item->produk->nama_produk} tidak valid.");
                }
                
                if ($item->lama_sewa < $item->produk->minimum_sewa) {
                    return redirect()->route('customer.products.index')
                        ->with('error', "Minimal sewa untuk {$item->produk->nama_produk} adalah {$item->produk->minimum_sewa} hari.");
                }
                
                if ($item->lama_sewa > $item->produk->maximum_sewa) {
                    return redirect()->route('customer.products.index')
                        ->with('error', "Maksimal sewa untuk {$item->produk->nama_produk} adalah {$item->produk->maximum_sewa} hari.");
                }
                
                // Cek stok tersedia
                if ($item->produk->stok_tersedia < $item->jumlah) {
                    return redirect()->route('customer.products.index')
                        ->with('error', "Stok {$item->produk->nama_produk} tidak mencukupi. Stok tersedia: {$item->produk->stok_tersedia}");
                }
            }

            // Hitung subtotal untuk produk
            $subtotal = $keranjangItems->sum(function ($item) {
                return $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
            });
        }
        
        // Ambil metode pembayaran yang aktif (kecuali COD)
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->where('type', '!=', 'cod')
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
        
        return view('customer.checkout.index', compact(
            'keranjangItems',
            'subtotal',
            'paymentMethods',
            'vouchers',
            'userAddress',
            'isStudioBooking',
            'isLayananBooking',
            'bookingData'
        ));
    }

    /**
     * Simpan pilihan sewa langsung ke session lalu lanjut ke checkout.
     * (Pengganti alur keranjang yang sudah dihapus.)
     */
    public function directRent(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'tanggal_sewa' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_sewa',
            'jumlah' => 'required|integer|min:1',
            'jam_mulai' => 'nullable|date_format:H:i',
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

        session()->forget(['direct_studio', 'direct_layanan']);
        session(['direct_rent' => [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'gambar_utama' => $product->gambar_utama,
            'harga_per_hari' => $product->harga_per_hari,
            'brand_nama' => $product->brand->nama_brand ?? null,
            'tanggal_sewa' => $request->tanggal_sewa,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai ?? '08:00',
            'jumlah' => $request->jumlah,
            'lama_sewa' => $lamaSewa,
        ]]);

        return response()->json(['success' => true]);
    }

    /**
     * Process checkout.
     */
    public function process(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $directStudio = session('direct_studio');
        $directLayanan = session('direct_layanan');

        if ($directStudio || $directLayanan) {
            $request->validate([
                'payment_method_id' => 'required|exists:payment_methods,id',
                'catatan' => 'nullable|string',
                'voucher_code' => 'nullable|string',
                'agree_terms' => 'required|accepted',
            ]);
        } else {
            $request->validate([
                'payment_method_id' => 'required|exists:payment_methods,id',

                'catatan' => 'nullable|string',
                'voucher_code' => 'nullable|string',
                'agree_terms' => 'required|accepted',
            ]);
        }
        
        DB::beginTransaction();
        
        try {
            if ($directLayanan) {
                // ===== LAYANAN BOOKING FLOW =====
                $layanan = Layanan::findOrFail($directLayanan['layanan_id']);
                $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

                // Recheck bentrok slot saat checkout (data session bisa basi karena antre pembayaran)
                $jamMulaiCek = $directLayanan['jam_mulai'];
                $jamSelesaiCek = $directLayanan['jam_selesai'];
                $bentrok = LayananBooking::where('layanan_id', $layanan->id)
                    ->where('tanggal_booking', $directLayanan['tanggal_booking'])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function ($q) use ($jamMulaiCek, $jamSelesaiCek) {
                        $q->whereBetween('jam_mulai', [$jamMulaiCek, $jamSelesaiCek])
                          ->orWhereBetween('jam_selesai', [$jamMulaiCek, $jamSelesaiCek])
                          ->orWhere(function ($q2) use ($jamMulaiCek, $jamSelesaiCek) {
                              $q2->where('jam_mulai', '<=', $jamMulaiCek)
                                 ->where('jam_selesai', '>=', $jamSelesaiCek);
                          });
                    })
                    ->exists();

                if ($bentrok) {
                    throw new \Exception('Slot layanan sudah dibooking orang lain pada jam tersebut. Silakan pilih jam lain.');
                }

                $subtotal = $directLayanan['total_harga'];

                $diskon = 0;
                $kodeVoucher = null;

                if ($request->filled('voucher_code')) {
                    $voucher = Voucher::where('kode_voucher', $request->voucher_code)
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->where(function($q) use ($user) {
                            $q->whereNull('user_id')->orWhere('user_id', $user->id);
                        })
                        ->where(function($q) use ($subtotal) {
                            $q->whereNull('min_purchase')->orWhere('min_purchase', '<=', $subtotal);
                        })
                        ->where(function($q) {
                            $q->whereNull('kuota')->orWhereRaw('kuota_terpakai < kuota');
                        })
                        ->first();

                    if ($voucher) {
                        // Clamp: diskon tidak boleh melebihi subtotal
                        $diskon = min($voucher->calculateDiscount($subtotal), $subtotal);
                        $kodeVoucher = $voucher->kode_voucher;
                        // Konsumsi kuota atomik (gagal jika habis karena race)
                        if (!$voucher->consumeQuota()) {
                            throw new \Exception('Kuota voucher sudah habis.');
                        }
                    }
                }

                $adminFee = $paymentMethod->calculateFee(max(0, $subtotal - $diskon));
                $grandTotal = max(0, $subtotal - $diskon) + $adminFee;

                $booking = LayananBooking::create([
                    'user_id' => $user->id,
                    'layanan_id' => $layanan->id,
                    'paket_layanan_id' => $directLayanan['tipe_booking'] === 'paket' ? $directLayanan['paket']['id'] : null,
                    'tipe_booking' => $directLayanan['tipe_booking'],
                    'tanggal_booking' => $directLayanan['tanggal_booking'],
                    'jam_mulai' => $directLayanan['jam_mulai'],
                    'jam_selesai' => $directLayanan['jam_selesai'],
                    'durasi_jam' => $directLayanan['durasi_jam'],
                    'total_harga' => $subtotal,
                    'payment_method_id' => $paymentMethod->id,
                    'admin_fee' => $adminFee,
                    'grand_total' => $grandTotal,
                    'catatan' => $request->catatan,
                    'kode_voucher' => $kodeVoucher,
                    'diskon_voucher' => $diskon,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'payment_expired_at' => now()->addMinutes((int) config('midtrans.expiry_duration', 1440)),
                ]);

                session()->forget('direct_layanan');

                DB::commit();

                \App\Models\Notification::sendToAdmins('transaction',
                    'Booking Layanan Baru',
                    $user->nama . ' booking layanan ' . $layanan->nama_layanan . ' - Rp ' . number_format($grandTotal, 0, ',', '.'),
                    ['booking_id' => $booking->id, 'type' => 'layanan']
                );

                return redirect()->route('customer.layanan.payment', $booking->id)
                    ->with('success', 'Booking layanan berhasil. Silakan lakukan pembayaran.');

            }

            if ($directStudio) {
                // ===== STUDIO BOOKING FLOW =====
                $studio = Studio::findOrFail($directStudio['studio_id']);
                $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

                // Recheck bentrok slot saat checkout (data session bisa basi karena antre pembayaran)
                $jamMulaiCek = $directStudio['jam_mulai'];
                $jamSelesaiCek = $directStudio['jam_selesai'];
                $bentrok = StudioBooking::where('studio_id', $studio->id)
                    ->where('tanggal_booking', $directStudio['tanggal_booking'])
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function ($q) use ($jamMulaiCek, $jamSelesaiCek) {
                        $q->whereBetween('jam_mulai', [$jamMulaiCek, $jamSelesaiCek])
                          ->orWhereBetween('jam_selesai', [$jamMulaiCek, $jamSelesaiCek])
                          ->orWhere(function ($q2) use ($jamMulaiCek, $jamSelesaiCek) {
                              $q2->where('jam_mulai', '<=', $jamMulaiCek)
                                 ->where('jam_selesai', '>=', $jamSelesaiCek);
                          });
                    })
                    ->exists();

                if ($bentrok) {
                    throw new \Exception('Slot studio sudah dibooking orang lain pada jam tersebut. Silakan pilih jam lain.');
                }

                $subtotal = $directStudio['total_harga'];

                // Hitung diskon voucher
                $diskon = 0;
                $kodeVoucher = null;

                if ($request->filled('voucher_code')) {
                    $voucher = Voucher::where('kode_voucher', $request->voucher_code)
                        ->where('is_active', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->where(function($q) use ($user) {
                            $q->whereNull('user_id')->orWhere('user_id', $user->id);
                        })
                        ->where(function($q) use ($subtotal) {
                            $q->whereNull('min_purchase')->orWhere('min_purchase', '<=', $subtotal);
                        })
                        ->where(function($q) {
                            $q->whereNull('kuota')->orWhereRaw('kuota_terpakai < kuota');
                        })
                        ->first();

                    if ($voucher) {
                        // Clamp: diskon tidak boleh melebihi subtotal
                        $diskon = min($voucher->calculateDiscount($subtotal), $subtotal);
                        $kodeVoucher = $voucher->kode_voucher;
                        // Konsumsi kuota atomik (gagal jika habis karena race)
                        if (!$voucher->consumeQuota()) {
                            throw new \Exception('Kuota voucher sudah habis.');
                        }
                    }
                }

                $adminFee = $paymentMethod->calculateFee(max(0, $subtotal - $diskon));
                $grandTotal = max(0, $subtotal - $diskon) + $adminFee;

                $booking = StudioBooking::create([
                    'user_id' => $user->id,
                    'studio_id' => $studio->id,
                    'paket_studio_id' => $directStudio['tipe_booking'] === 'paket' ? $directStudio['paket']['id'] : null,
                    'tipe_booking' => $directStudio['tipe_booking'],
                    'tanggal_booking' => $directStudio['tanggal_booking'],
                    'jam_mulai' => $directStudio['jam_mulai'],
                    'jam_selesai' => $directStudio['jam_selesai'],
                    'durasi_jam' => $directStudio['durasi_jam'],
                    'total_harga' => $subtotal,
                    'payment_method_id' => $paymentMethod->id,
                    'admin_fee' => $adminFee,
                    'grand_total' => $grandTotal,
                    'catatan' => $request->catatan,
                    'kode_voucher' => $kodeVoucher,
                    'diskon_voucher' => $diskon,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'payment_expired_at' => now()->addMinutes((int) config('midtrans.expiry_duration', 1440)),
                ]);

                session()->forget('direct_studio');

                DB::commit();

                \App\Models\Notification::sendToAdmins('transaction',
                    'Booking Studio Baru',
                    $user->nama . ' booking studio ' . $studio->nama_studio . ' - Rp ' . number_format($grandTotal, 0, ',', '.'),
                    ['booking_id' => $booking->id, 'type' => 'studio']
                );

                return redirect()->route('customer.studio.payment', $booking->id)
                    ->with('success', 'Booking studio berhasil. Silakan lakukan pembayaran.');

            }
            
            // ===== PRODUCT RENTAL FLOW (langsung tanpa keranjang) =====
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
                $item->jam_mulai = $directRent['jam_mulai'] ?? '08:00';
                $item->harga_per_hari = $product->harga_per_hari;
                $item->catatan = null;
                $keranjangItems = collect([$item]);
            } else {
                throw new \Exception('Silakan pilih produk dan tanggal sewa terlebih dahulu.');
            }

            if ($keranjangItems->isEmpty()) {
                throw new \Exception('Silakan pilih produk dan tanggal sewa terlebih dahulu.');
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
                    }
                }
            }
            
            // Ambil tanggal dari item keranjang
            $tanggalPengambilan = $keranjangItems->min('tanggal_sewa');
            $tanggalPengembalian = $keranjangItems->max('tanggal_kembali');
            $tanggalPengambilan = $tanggalPengambilan ? \Carbon\Carbon::parse($tanggalPengambilan)->format('Y-m-d') : now()->format('Y-m-d');
            $tanggalPengembalian = $tanggalPengembalian ? \Carbon\Carbon::parse($tanggalPengembalian)->format('Y-m-d') : now()->format('Y-m-d');
            $jamMulai = $keranjangItems->min('jam_mulai') ?? '08:00';
            // Hitung total sewa (clamp: diskon tidak boleh melebihi subtotal)
            $diskon = min($diskon, $subtotal);
            $totalSewa = max(0, $subtotal - $diskon);
            
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
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'kode_voucher' => $request->voucher_code,
                'biaya_lainnya' => $adminFee,
                'total_sewa' => $totalSewa,
                'admin_fee' => $adminFee,
                'grand_total' => $grandTotal,
                'payment_method_id' => $request->payment_method_id,
                'payment_type' => $paymentMethod->midtrans_payment_type,
                'bank' => $paymentMethod->bank_code,
                'status_pembayaran' => 'pending',
                'status_transaksi' => 'menunggu_pembayaran',
                'tanggal_pengambilan' => $tanggalPengambilan . ' ' . $jamMulai . ':00',
                'tanggal_pengembalian' => $tanggalPengembalian . ' 20:00:00',
                'lama_sewa' => $lamaSewa,
                'metode_pengambilan' => 'pickup',
                'metode_pengembalian' => 'return',
                'catatan' => $request->catatan,
                'payment_expired_at' => now()->addMinutes((int) config('midtrans.expiry_duration', 1440)),
            ]);
            
            // Buat detail transaksi & kurangi stok
            foreach ($keranjangItems as $item) {
                $hargaProduk = $item->produk->harga_per_hari * $item->lama_sewa * $item->jumlah;
                
                DetailTransaksis::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'kode_produk' => $item->produk->kode_produk,
                    'nama_produk' => $item->produk->nama_produk,
                    'harga_per_hari' => $item->produk->harga_per_hari,
                    'jumlah' => $item->jumlah,
                    'lama_sewa' => $item->lama_sewa,
                    'subtotal' => $hargaProduk,
                    'catatan' => $item->catatan,
                ]);
                
                // Kurangi stok produk (atomik; gagal jika stok habis karena race)
                if (!$item->produk->updateStock('rent', $item->jumlah)) {
                    throw new \Exception("Stok {$item->produk->nama_produk} habis saat proses checkout.");
                }
            }
            
            
            // Update voucher usage jika ada
            if ($voucherId) {
                // Konsumsi kuota atomik lebih dulu (gagal jika sudah habis karena race)
                if (!$voucher->consumeQuota()) {
                    throw new \Exception('Kuota voucher sudah habis.');
                }

                VoucherUsage::create([
                    'voucher_id' => $voucherId,
                    'user_id' => $user->id,
                    'transaksi_id' => $transaksi->id,
                    'discount_amount' => $diskon,
                ]);
            }
            
            // Hapus data sewa langsung dari session
            if ($directRent) {
                session()->forget('direct_rent');
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

        // FIX: Jika sudah deny/cancel/expire/failure/dibatalkan, jangan generate token lagi
        if (in_array($transaksi->status_pembayaran, ['deny', 'cancel', 'expire', 'failure']) || $transaksi->status_transaksi === 'dibatalkan') {
            return redirect()->route('customer.checkout.failed', $transaksi->id)
                ->with('error', 'Transaksi ini sudah dibatalkan/ditolak ('.$transaksi->status_pembayaran.'). Silakan buat pesanan baru. Stok sudah dikembalikan.');
        }

        // Reuse token jika sudah ada dan belum expired (hindari error order_id sudah digunakan)
        if ($transaksi->midtrans_token && $transaksi->midtrans_order_id && !$this->midtransService->isTransactionExpired($transaksi)) {
            return view('customer.checkout.payment', ['transaksi' => $transaksi, 'snapToken' => $transaksi->midtrans_token]);
        }
        
        // Generate snap token untuk Midtrans
        $snapToken = null;
        try {
            $snapToken = $this->midtransService->generateSnapToken($transaksi);
        } catch (\Exception $e) {
            Log::error('Midtrans token generation failed: ' . $e->getMessage());
            // Jika error karena dibatalkan, redirect ke failed agar user buat baru
            if (str_contains($e->getMessage(), 'dibatalkan/ditolak')) {
                return redirect()->route('customer.checkout.failed', $transaksi->id)
                    ->with('error', $e->getMessage());
            }
            // Jika order_id sudah digunakan (user refresh), pakai token lama jika ada
            if (str_contains($e->getMessage(), 'sudah digunakan') && $transaksi->midtrans_token) {
                return view('customer.checkout.payment', ['transaksi' => $transaksi, 'snapToken' => $transaksi->midtrans_token]);
            }
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
    public function midtransCallback(Request $request)
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

        $processed = $this->midtransService->handleNotification($request);

        return response()->json([
            'message' => $processed ? 'Callback processed' : 'Transaction not found',
        ], $processed ? 200 : 404);
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

        $processed = $this->midtransService->handleNotification($request);

        if (!$processed) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json(['message' => 'Notification processed']);
    }
}
