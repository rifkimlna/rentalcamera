<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\PaketLayanan;
use App\Models\LayananBooking;
use App\Models\Voucher;
use App\Models\PaymentMethod;
use App\Models\Notification;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class LayananController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;

        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$clientKey = config('midtrans.client_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }

    public function index(Request $request)
    {
        $query = Layanan::withCount('paketActive')->with('paketActive')->active();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_layanan', 'like', "%{$search}%");
        }

        $layanans = $query->orderBy('nama_layanan')->paginate(12);
        $layanansJson = $layanans->getCollection()->map(function ($l) {
            return [
                'id' => $l->id,
                'nama_layanan' => $l->nama_layanan,
                'slug' => $l->slug,
                'harga_mulai' => $l->harga_mulai,
                'harga_mulai_formatted' => $l->harga_mulai_formatted,
                'paketActive' => $l->paketActive->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama_paket' => $p->nama_paket,
                        'deskripsi' => $p->deskripsi,
                        'harga' => $p->harga,
                        'harga_formatted' => $p->harga_formatted,
                        'durasi_jam' => $p->durasi_jam,
                        'include' => $p->include,
                    ];
                }),
            ];
        })->keyBy('id');

        return view('customer.layanan.index', compact('layanans', 'layanansJson'));
    }

    public function show($slug)
    {
        $layanan = Layanan::with(['paketActive' => function ($q) {
            $q->where('status', 'active');
        }, 'ulasan.user'])->where('slug', $slug)->active()->firstOrFail();

        $relatedLayanans = Layanan::active()
            ->where('id', '!=', $layanan->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('customer.layanan.show', compact('layanan', 'relatedLayanans'));
    }

    public function directLayanan(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'tipe_booking' => 'required|in:layanan,paket',
            'paket_layanan_id' => 'nullable|exists:paket_layanan,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi_jam' => 'required_if:tipe_booking,layanan|integer|min:1',
        ]);

        $layanan = Layanan::findOrFail($request->layanan_id);

        if ($request->tipe_booking === 'paket') {
            $request->validate(['paket_layanan_id' => 'required|exists:paket_layanan,id']);
            $paket = PaketLayanan::findOrFail($request->paket_layanan_id);
            $durasi = $paket->durasi_jam;
            $totalHarga = $paket->harga;
            $paketInfo = [
                'id' => $paket->id,
                'nama_paket' => $paket->nama_paket,
                'deskripsi' => $paket->deskripsi,
                'durasi_jam' => $paket->durasi_jam,
                'harga' => $paket->harga,
            ];
        } else {
            $durasi = (int) $request->durasi_jam;
            $totalHarga = $layanan->harga_mulai * $durasi;
            $paketInfo = null;
        }

        $jamMulai = $request->jam_mulai;
        $jamSelesai = date('H:i', strtotime($jamMulai) + ($durasi * 3600));

        $conflict = LayananBooking::where('layanan_id', $layanan->id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                  ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                      $q2->where('jam_mulai', '<=', $jamMulai)
                         ->where('jam_selesai', '>=', $jamSelesai);
                  });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['success' => false, 'message' => 'Layanan sudah dibooking pada jam tersebut. Silakan pilih jam lain.']);
        }

        session()->forget(['direct_rent', 'direct_studio']);
        session(['direct_layanan' => [
            'layanan_id' => $layanan->id,
            'nama_layanan' => $layanan->nama_layanan,
            'slug' => $layanan->slug,
            'harga_mulai' => $layanan->harga_mulai,
            'gambar_utama' => $layanan->gambar_utama,
            'tipe_booking' => $request->tipe_booking,
            'paket' => $paketInfo,
            'tanggal_booking' => $request->tanggal_booking,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'durasi_jam' => $durasi,
            'total_harga' => $totalHarga,
        ]]);

        return response()->json(['success' => true]);
    }

    public function booking(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'tipe_booking' => 'required|in:layanan,paket',
            'paket_layanan_id' => 'nullable|exists:paket_layanan,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'voucher_code' => 'nullable|string',
            'catatan' => 'nullable|string|max:500',
        ]);

        $layanan = Layanan::findOrFail($request->layanan_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        if ($request->tipe_booking === 'paket') {
            $request->validate(['paket_layanan_id' => 'required|exists:paket_layanan,id']);
            $paket = PaketLayanan::findOrFail($request->paket_layanan_id);
            $durasi = $paket->durasi_jam;
            $totalHarga = $paket->harga;
        } else {
            $request->validate(['durasi_jam' => 'required|integer|min:1']);
            $durasi = $request->durasi_jam;
            $totalHarga = $layanan->harga_mulai * $durasi;
        }

        $jamMulai = $request->jam_mulai;
        $jamSelesai = date('H:i', strtotime($jamMulai) + ($durasi * 3600));

        $conflict = LayananBooking::where('layanan_id', $layanan->id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                  ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                      $q2->where('jam_mulai', '<=', $jamMulai)
                         ->where('jam_selesai', '>=', $jamSelesai);
                  });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->with('error', 'Layanan sudah dibooking pada jam tersebut. Silakan pilih jam lain.')
                ->withInput();
        }

        // Proses voucher jika ada
        $diskonVoucher = 0;
        $kodeVoucher = null;

        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('kode_voucher', $request->voucher_code)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where(function ($query) {
                    $query->whereNull('user_id')
                        ->orWhere('user_id', Auth::id());
                })
                ->where(function ($query) use ($totalHarga) {
                    $query->whereNull('min_purchase')
                        ->orWhere('min_purchase', '<=', $totalHarga);
                })
                ->where(function ($query) {
                    $query->whereNull('kuota')
                        ->orWhereRaw('kuota_terpakai < kuota');
                })
                ->first();

            if ($voucher) {
                $diskonVoucher = min($voucher->calculateDiscount($totalHarga), $totalHarga);
                $kodeVoucher = $voucher->kode_voucher;

                $voucher->increment('kuota_terpakai');
            }
        }

        $adminFee = $paymentMethod->calculateFee($totalHarga - $diskonVoucher);
        $grandTotal = ($totalHarga - $diskonVoucher) + $adminFee;

        $booking = LayananBooking::create([
            'user_id' => Auth::id(),
            'layanan_id' => $layanan->id,
            'paket_layanan_id' => $request->tipe_booking === 'paket' ? $request->paket_layanan_id : null,
            'tipe_booking' => $request->tipe_booking,
            'tanggal_booking' => $request->tanggal_booking,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'durasi_jam' => $durasi,
            'total_harga' => $totalHarga,
            'kode_voucher' => $kodeVoucher,
            'diskon_voucher' => $diskonVoucher,
            'payment_method_id' => $paymentMethod->id,
            'admin_fee' => $adminFee,
            'grand_total' => $grandTotal,
            'catatan' => $request->catatan,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        Notification::sendToAdmins('transaction',
            'Booking Layanan Baru',
            Auth::user()->nama . ' booking layanan ' . $layanan->nama_layanan . ' - ' . $booking->tanggal_booking->format('d M Y'),
            ['booking_id' => $booking->id, 'type' => 'layanan']
        );

        return redirect()->route('customer.layanan.payment', $booking->id);
    }

    public function checkVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        $voucher = Voucher::where('kode_voucher', $request->voucher_code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->where(function ($query) use ($request) {
                $query->whereNull('min_purchase')
                    ->orWhere('min_purchase', '<=', $request->total_harga);
            })
            ->where(function ($query) {
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

        $diskon = min($voucher->calculateDiscount($request->total_harga), $request->total_harga);

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
            'message' => 'Voucher valid!',
        ]);
    }

    public function payment($id)
    {
        $booking = LayananBooking::with(['layanan', 'paketLayanan', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('customer.layanan.booking.success', $booking->id);
        }

        $snapToken = null;
        try {
            $orderId = $booking->midtrans_order_id
                ?: 'LYN-' . strtoupper(dechex(time())) . '-' . $booking->id;

            $itemName = $booking->tipe_booking === 'paket'
                ? $booking->paketLayanan->nama_paket
                : $booking->layanan->nama_layanan . ' (' . $booking->durasi_jam . ' jam)';

            $itemDetails = [
                [
                    'id' => $booking->tipe_booking === 'paket' ? 'PAKET-' . $booking->paket_layanan_id : 'LYN-' . $booking->layanan_id,
                    'price' => (int) $booking->total_harga,
                    'quantity' => 1,
                    'name' => $itemName,
                ],
            ];

            if ($booking->diskon_voucher > 0) {
                $itemDetails[] = [
                    'id' => 'DISKON_VOUCHER',
                    'price' => (int) -$booking->diskon_voucher,
                    'quantity' => 1,
                    'name' => 'Diskon Voucher (' . ($booking->kode_voucher ?? '') . ')',
                ];
            }

            if ($booking->admin_fee > 0) {
                $itemDetails[] = [
                    'id' => 'ADMIN_FEE',
                    'price' => (int) $booking->admin_fee,
                    'quantity' => 1,
                    'name' => 'Biaya Admin (' . $booking->paymentMethod->name . ')',
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $booking->grand_total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $booking->user->nama,
                    'email' => $booking->user->email,
                    'phone' => $booking->user->telepon ?? '',
                ],
                'callbacks' => [
                    'finish' => route('customer.layanan.booking.success', $booking->id),
                ],
            ];

            if ($booking->paymentMethod && $booking->paymentMethod->midtrans_payment_type) {
                $params['enabled_payments'] = [$booking->paymentMethod->midtrans_payment_type];
            }

            $snapToken = Snap::getSnapToken($params);

            $booking->update([
                'midtrans_order_id' => $orderId,
                'midtrans_token' => $snapToken,
                'midtrans_redirect_url' => route('customer.layanan.payment', $booking->id),
            ]);
            $booking->refresh();

        } catch (\Exception $e) {
            Log::error('Midtrans layanan token failed: ' . $e->getMessage());
        }

        return view('customer.layanan.payment', compact('booking', 'snapToken'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = LayananBooking::where('midtrans_order_id', $request->order_id)->first();
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($request->filled('gross_amount') && abs((float) $booking->grand_total - (float) $request->gross_amount) > 0.01) {
            return response()->json(['message' => 'Invalid amount'], 403);
        }

        $this->updatePaymentStatus($booking, $request);

        return response()->json(['message' => 'OK']);
    }

    public function notification(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::warning('Midnotif layanan: invalid signature', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = LayananBooking::where('midtrans_order_id', $request->order_id)->first();
        if (!$booking) {
            Log::warning('Midnotif layanan: booking not found', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Verifikasi jumlah pembayaran harus sama dengan grand_total di database
        if ($request->filled('gross_amount') && abs((float) $booking->grand_total - (float) $request->gross_amount) > 0.01) {
            Log::error('Midnotif layanan: gross_amount mismatch', [
                'order_id' => $request->order_id,
                'expected' => $booking->grand_total,
                'received' => $request->gross_amount,
            ]);
            return response()->json(['message' => 'Invalid amount'], 403);
        }

        $this->updatePaymentStatus($booking, $request);
        $this->midtransService->recordPaymentLog($request);

        Log::info('Midnotif layanan: OK', ['order_id' => $request->order_id, 'status' => $request->transaction_status]);
        return response()->json(['message' => 'OK']);
    }

    protected function updatePaymentStatus($booking, Request $request)
    {
        switch ($request->transaction_status) {
            case 'capture':
                // Kartu kredit: hanya accept yang lunas, challenge tetap pending
                if (($request->fraud_status ?? null) === 'challenge') {
                    if ($booking->payment_status !== 'paid') {
                        $booking->update(['payment_status' => 'pending']);
                    }
                    break;
                }
                if (($request->fraud_status ?? null) !== 'accept') {
                    break;
                }
                $this->markBookingPaid($booking);
                break;
            case 'settlement':
                $this->markBookingPaid($booking);
                break;
            case 'pending':
                // Jangan turunkan booking yang sudah lunas/direfund
                if (!in_array($booking->payment_status, ['paid', 'refunded'])) {
                    $booking->update(['payment_status' => 'pending']);
                }
                break;
            case 'deny':
            case 'cancel':
                // Jangan batalkan booking yang sudah dibayar (uang sudah masuk)
                if ($booking->payment_status === 'paid') {
                    Log::warning('Midnotif layanan: deny/cancel diabaikan untuk booking lunas', ['booking_id' => $booking->id]);
                    break;
                }
                $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                break;
            case 'expire':
                if ($booking->payment_status === 'paid') {
                    Log::warning('Midnotif layanan: expire diabaikan untuk booking lunas', ['booking_id' => $booking->id]);
                    break;
                }
                $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);
                break;
            case 'refund':
                $booking->update(['payment_status' => 'refunded']);
                break;
        }
    }

    /**
     * Tandai booking lunas. Idempoten + anti-resurrect: settlement telat untuk
     * booking yang sudah dibatalkan tidak boleh mengaktifkannya kembali.
     */
    protected function markBookingPaid($booking)
    {
        if ($booking->status === 'cancelled') {
            Log::warning('Midnotif layanan: settlement untuk booking dibatalkan, perlu rekonsiliasi manual', ['booking_id' => $booking->id]);
            Notification::sendToAdmins('payment',
                'Perlu Rekonsiliasi: Booking Layanan Dibayar Setelah Dibatalkan',
                'Booking layanan #' . $booking->id . ' menerima pembayaran padahal sudah dibatalkan customer.',
                ['booking_id' => $booking->id, 'type' => 'layanan']
            );
            return false;
        }

        if ($booking->payment_status === 'paid') {
            return false;
        }

        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);
        Notification::sendToAdmins('payment',
            'Pembayaran Layanan Lunas',
            'Booking layanan ' . $booking->layanan->nama_layanan . ' oleh ' . ($booking->user->nama ?? 'User') . ' telah dibayar.',
            ['booking_id' => $booking->id, 'type' => 'layanan']
        );
        return true;
    }

    public function checkStatus($id)
    {
        $booking = LayananBooking::where('user_id', Auth::id())->findOrFail($id);
        return response()->json([
            'payment_status' => $booking->payment_status,
            'status' => $booking->status,
        ]);
    }

    public function bookingSuccess($id)
    {
        $booking = LayananBooking::with(['layanan', 'paketLayanan', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('customer.layanan.booking_success', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = LayananBooking::with(['layanan', 'paketLayanan', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.layanan.my_bookings', compact('bookings'));
    }

    public function bookingCancel($id)
    {
        $booking = LayananBooking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        // Batalkan juga di sisi Midtrans agar VA/Snap tidak bisa dibayar setelahnya
        if ($booking->midtrans_order_id) {
            $this->midtransService->cancelTransaction($booking->midtrans_order_id);

            if ($this->midtransService->isPaidAtGateway($booking->midtrans_order_id)) {
                return redirect()->back()
                    ->with('error', 'Booking ini sudah terbayar di Midtrans dan tidak dapat dibatalkan. Silakan hubungi admin untuk pengembalian dana.');
            }
        }

        $booking->update(['status' => 'cancelled']);

        // Kembalikan kuota voucher yang tercatat pada booking
        Voucher::releaseByCode($booking->kode_voucher);

        return redirect()->route('customer.layanan.my-bookings')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
