<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\PaketStudio;
use App\Models\StudioBooking;
use App\Models\Voucher;
use App\Models\PaymentMethod;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class StudioController extends Controller
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
        $query = Studio::withCount('paketActive')->with('paketActive')->active();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_studio', 'like', "%{$search}%");
        }

        $studios = $query->orderBy('nama_studio')->paginate(12);
        $studiosJson = $studios->getCollection()->map(function ($s) {
            return [
                'id' => $s->id,
                'nama_studio' => $s->nama_studio,
                'slug' => $s->slug,
                'harga_per_jam' => $s->harga_per_jam,
                'harga_per_jam_formatted' => $s->harga_per_jam_formatted,
                'paketActive' => $s->paketActive->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama_paket' => $p->nama_paket,
                        'deskripsi' => $p->deskripsi,
                        'harga' => $p->harga,
                        'harga_formatted' => $p->harga_formatted,
                        'durasi_jam' => $p->durasi_jam,
                        'include_alat' => $p->include_alat,
                    ];
                }),
            ];
        })->keyBy('id');

        $paymentMethods = PaymentMethod::where('is_active', true)
            ->where('type', '!=', 'cod')
            ->orderBy('sort_order')
            ->get();

        return view('customer.studio.index', compact('studios', 'studiosJson', 'paymentMethods'));
    }

    public function show($slug)
    {
        $studio = Studio::with(['paketActive' => function ($q) {
            $q->where('status', 'active');
        }, 'ulasan.user'])->where('slug', $slug)->active()->firstOrFail();

        $relatedStudios = Studio::active()
            ->where('id', '!=', $studio->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('customer.studio.show', compact('studio', 'relatedStudios'));
    }

    public function directStudio(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studio,id',
            'tipe_booking' => 'required|in:studio,paket',
            'paket_studio_id' => 'nullable|exists:paket_studio,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi_jam' => 'required_if:tipe_booking,studio|integer|min:1',
        ]);

        $studio = Studio::findOrFail($request->studio_id);

        if ($request->tipe_booking === 'paket') {
            $request->validate(['paket_studio_id' => 'required|exists:paket_studio,id']);
            $paket = PaketStudio::findOrFail($request->paket_studio_id);
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
            $totalHarga = $studio->harga_per_jam * $durasi;
            $paketInfo = null;
        }

        $jamMulai = $request->jam_mulai;
        $jamSelesai = date('H:i', strtotime($jamMulai) + ($durasi * 3600));

        // Check conflicts
        $conflict = StudioBooking::where('studio_id', $studio->id)
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
            return response()->json(['success' => false, 'message' => 'Studio sudah dibooking pada jam tersebut. Silakan pilih jam lain.']);
        }

        session()->forget(['direct_rent', 'direct_layanan']);
        session(['direct_studio' => [
            'studio_id' => $studio->id,
            'nama_studio' => $studio->nama_studio,
            'slug' => $studio->slug,
            'harga_per_jam' => $studio->harga_per_jam,
            'gambar_utama' => $studio->gambar_utama,
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
            'studio_id' => 'required|exists:studio,id',
            'tipe_booking' => 'required|in:studio,paket',
            'paket_studio_id' => 'nullable|exists:paket_studio,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'voucher_code' => 'nullable|string',
            'catatan' => 'nullable|string|max:500',
            'durasi_jam' => 'required_if:tipe_booking,studio|integer|min:1',
        ]);

        $studio = Studio::findOrFail($request->studio_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        if ($request->tipe_booking === 'paket') {
            $request->validate(['paket_studio_id' => 'required|exists:paket_studio,id']);
            $paket = PaketStudio::findOrFail($request->paket_studio_id);
            $durasi = $paket->durasi_jam;
            $totalHarga = $paket->harga;
        } else {
            $durasi = $request->durasi_jam;
            $totalHarga = $studio->harga_per_jam * $durasi;
        }

        $jamMulai = $request->jam_mulai;
        $jamSelesai = date('H:i', strtotime($jamMulai) + ($durasi * 3600));

        $conflict = StudioBooking::where('studio_id', $studio->id)
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
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Studio sudah dibooking pada jam tersebut. Silakan pilih jam lain.']);
            }
            return redirect()->back()
                ->with('error', 'Studio sudah dibooking pada jam tersebut. Silakan pilih jam lain.')
                ->withInput();
        }

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

        $adminFee = $paymentMethod->calculateFee(max(0, $totalHarga - $diskonVoucher));
        $grandTotal = max(0, $totalHarga - $diskonVoucher) + $adminFee;

        $booking = StudioBooking::create([
            'user_id' => Auth::id(),
            'studio_id' => $studio->id,
            'paket_studio_id' => $request->tipe_booking === 'paket' ? $request->paket_studio_id : null,
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
            'payment_expired_at' => now()->addMinutes((int) config('midtrans.expiry_duration', 1440)),
        ]);

        \App\Models\Notification::sendToAdmins('transaction',
            'Booking Studio Baru',
            Auth::user()->nama . ' booking studio ' . $studio->nama_studio . ' - ' . $booking->tanggal_booking->format('d M Y'),
            ['booking_id' => $booking->id, 'type' => 'studio']
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('customer.studio.payment', $booking->id)]);
        }

        return redirect()->route('customer.studio.payment', $booking->id);
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
        $booking = StudioBooking::with(['studio', 'paketStudio', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('customer.studio.booking.success', $booking->id);
        }
        if (in_array($booking->payment_status, ['failed','expired','cancelled','deny']) || $booking->status === 'cancelled') {
            return redirect()->route('customer.studio.my-bookings')->with('error', 'Booking sudah dibatalkan/ditolak. Silakan buat booking baru.');
        }
        if ($booking->isExpired()) {
            $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);
            return redirect()->route('customer.studio.my-bookings')->with('error', 'Waktu pembayaran habis. Silakan buat booking baru.');
        }
        // Reuse token jika sudah ada dan belum expired (hindari order_id sudah digunakan)
        if ($booking->midtrans_token && $booking->midtrans_order_id && !$booking->isExpired()) {
            return view('customer.studio.payment', ['booking' => $booking, 'snapToken' => $booking->midtrans_token]);
        }

        $snapToken = null;
        try {
            $orderId = $booking->midtrans_order_id
                ?: 'STD-' . strtoupper(dechex(time())) . '-' . $booking->id;

            $itemName = $booking->tipe_booking === 'paket'
                ? $booking->paketStudio->nama_paket
                : $booking->studio->nama_studio . ' (' . $booking->durasi_jam . ' jam)';

            $itemDetails = [
                [
                    'id' => $booking->tipe_booking === 'paket' ? 'PAKET-' . $booking->paket_studio_id : 'STUDIO-' . $booking->studio_id,
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
                    'finish' => route('customer.studio.booking.success', $booking->id),
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s O'),
                    'unit' => 'minute',
                    'duration' => (int) config('midtrans.expiry_duration', 1440),
                ],
            ];

            if ($booking->paymentMethod && $booking->paymentMethod->midtrans_payment_type) {
                $params['enabled_payments'] = [$booking->paymentMethod->midtrans_payment_type];
            }

            $snapToken = Snap::getSnapToken($params);

            $booking->update([
                'midtrans_order_id' => $orderId,
                'midtrans_token' => $snapToken,
                'midtrans_redirect_url' => route('customer.studio.payment', $booking->id),
                'payment_expired_at' => now()->addMinutes((int) config('midtrans.expiry_duration', 1440)),
            ]);
            $booking->refresh();

        } catch (\Exception $e) {
            Log::error('Midtrans studio token failed: ' . $e->getMessage());
        }

        return view('customer.studio.payment', compact('booking', 'snapToken'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = StudioBooking::where('midtrans_order_id', $request->order_id)->first();
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
            Log::warning('Midnotif studio: invalid signature', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = StudioBooking::where('midtrans_order_id', $request->order_id)->first();
        if (!$booking) {
            Log::warning('Midnotif studio: booking not found', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Verifikasi jumlah pembayaran harus sama dengan grand_total di database
        if ($request->filled('gross_amount') && abs((float) $booking->grand_total - (float) $request->gross_amount) > 0.01) {
            Log::error('Midnotif studio: gross_amount mismatch', [
                'order_id' => $request->order_id,
                'expected' => $booking->grand_total,
                'received' => $request->gross_amount,
            ]);
            return response()->json(['message' => 'Invalid amount'], 403);
        }

        $this->updatePaymentStatus($booking, $request);
        $this->midtransService->recordPaymentLog($request);

        Log::info('Midnotif studio: OK', ['order_id' => $request->order_id, 'status' => $request->transaction_status]);
        return response()->json(['message' => 'OK']);
    }

    protected function updatePaymentStatus($booking, Request $request)
    {
        switch ($request->transaction_status) {
            case 'capture':
                // Kartu kredit: hanya accept yang lunas, challenge tetap pending, deny dibatalkan
                if (($request->fraud_status ?? null) === 'challenge') {
                    if ($booking->payment_status !== 'paid') {
                        $booking->update(['payment_status' => 'pending']);
                    }
                    break;
                }
                if (($request->fraud_status ?? null) === 'deny') {
                    if ($booking->payment_status !== 'paid') {
                        $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                    }
                    break;
                }
                if (($request->fraud_status ?? null) !== 'accept') {
                    Log::warning('Midnotif studio: capture tanpa fraud accept/deny', ['fraud' => $request->fraud_status, 'booking_id' => $booking->id]);
                    break;
                }
                $this->markBookingPaid($booking);
                break;
            case 'settlement':
                $this->markBookingPaid($booking);
                break;
            case 'pending':
                // Jangan turunkan booking yang sudah lunas/refund/expired/failed/cancelled (anti-resurrect)
                if (!in_array($booking->payment_status, ['paid', 'refunded', 'expired', 'failed']) && $booking->status !== 'cancelled') {
                    $booking->update(['payment_status' => 'pending']);
                }
                break;
            case 'deny':
            case 'cancel':
            case 'failure':
                // Jangan batalkan booking yang sudah dibayar (uang sudah masuk)
                if ($booking->payment_status === 'paid') {
                    Log::warning('Midnotif studio: deny/cancel/failure diabaikan untuk booking lunas', ['booking_id' => $booking->id]);
                    break;
                }
                $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                break;
            case 'expire':
                if ($booking->payment_status === 'paid') {
                    Log::warning('Midnotif studio: expire diabaikan untuk booking lunas', ['booking_id' => $booking->id]);
                    break;
                }
                $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);
                \App\Models\Voucher::releaseByCode($booking->kode_voucher);
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
            Log::warning('Midnotif studio: settlement untuk booking dibatalkan, perlu rekonsiliasi manual', ['booking_id' => $booking->id]);
            \App\Models\Notification::sendToAdmins('payment',
                'Perlu Rekonsiliasi: Booking Studio Dibayar Setelah Dibatalkan',
                'Booking studio #' . $booking->id . ' menerima pembayaran padahal sudah dibatalkan customer.',
                ['booking_id' => $booking->id, 'type' => 'studio']
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
        \App\Models\Notification::sendToAdmins('payment',
            'Pembayaran Studio Lunas',
            'Booking studio ' . $booking->studio->nama_studio . ' oleh ' . ($booking->user->nama ?? 'User') . ' telah dibayar.',
            ['booking_id' => $booking->id, 'type' => 'studio']
        );
        return true;
    }

    public function checkStatus($id)
    {
        $booking = StudioBooking::where('user_id', Auth::id())->findOrFail($id);
        return response()->json([
            'payment_status' => $booking->payment_status,
            'status' => $booking->status,
        ]);
    }

    public function bookingSuccess($id)
    {
        $booking = StudioBooking::with(['studio', 'paketStudio', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('customer.studio.booking_success', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = StudioBooking::with(['studio', 'paketStudio', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.studio.my_bookings', compact('bookings'));
    }

    public function bookingCancel($id)
    {
        $booking = StudioBooking::where('user_id', Auth::id())
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

        return redirect()->route('customer.studio.my-bookings')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
