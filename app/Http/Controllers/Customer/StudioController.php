<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\PaketStudio;
use App\Models\StudioBooking;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class StudioController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$clientKey = config('midtrans.client_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
    }

    public function index(Request $request)
    {
        $query = Studio::withCount('paketActive')->active();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_studio', 'like', "%{$search}%");
        }

        $studios = $query->orderBy('nama_studio')->paginate(12);
        return view('customer.studio.index', compact('studios'));
    }

    public function show($slug)
    {
        $studio = Studio::with(['paketActive' => function ($q) {
            $q->where('status', 'active');
        }])->where('slug', $slug)->active()->firstOrFail();

        $paymentMethods = PaymentMethod::active()
            ->whereIn('type', ['bank_transfer', 'qris', 'ewallet', 'credit_card'])
            ->orderBy('sort_order')
            ->get();

        return view('customer.studio.show', compact('studio', 'paymentMethods'));
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
            'catatan' => 'nullable|string|max:500',
        ]);

        $studio = Studio::findOrFail($request->studio_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        if ($request->tipe_booking === 'paket') {
            $request->validate(['paket_studio_id' => 'required|exists:paket_studio,id']);
            $paket = PaketStudio::findOrFail($request->paket_studio_id);
            $durasi = $paket->durasi_jam;
            $totalHarga = $paket->harga;
        } else {
            $request->validate(['durasi_jam' => 'required|integer|min:1']);
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
            return redirect()->back()
                ->with('error', 'Studio sudah dibooking pada jam tersebut. Silakan pilih jam lain.')
                ->withInput();
        }

        $adminFee = $paymentMethod->calculateFee($totalHarga);
        $grandTotal = $totalHarga + $adminFee;

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
            'payment_method_id' => $paymentMethod->id,
            'admin_fee' => $adminFee,
            'grand_total' => $grandTotal,
            'catatan' => $request->catatan,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        \App\Models\Notification::sendToAdmins('transaction',
            'Booking Studio Baru',
            Auth::user()->nama . ' booking studio ' . $studio->nama_studio . ' - ' . $booking->tanggal_booking->format('d M Y'),
            ['booking_id' => $booking->id, 'type' => 'studio']
        );

        return redirect()->route('customer.studio.payment', $booking->id);
    }

    public function payment($id)
    {
        $booking = StudioBooking::with(['studio', 'paketStudio', 'paymentMethod'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('customer.studio.booking.success', $booking->id);
        }

        $snapToken = null;
        try {
            $orderId = 'STD-' . strtoupper(dechex(time())) . '-' . $booking->id;

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
            ];

            if ($booking->paymentMethod && $booking->paymentMethod->midtrans_payment_type) {
                $params['enabled_payments'] = [$booking->paymentMethod->midtrans_payment_type];
            }

            $snapToken = Snap::getSnapToken($params);

            $booking->update([
                'midtrans_order_id' => $orderId,
                'midtrans_token' => $snapToken,
                'midtrans_redirect_url' => route('customer.studio.payment', $booking->id),
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

        $this->updatePaymentStatus($booking, $request->transaction_status);

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

        $this->updatePaymentStatus($booking, $request->transaction_status);

        Log::info('Midnotif studio: OK', ['order_id' => $request->order_id, 'status' => $request->transaction_status]);
        return response()->json(['message' => 'OK']);
    }

    protected function updatePaymentStatus($booking, $transactionStatus)
    {
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
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
                break;
            case 'pending':
                $booking->update(['payment_status' => 'pending']);
                break;
            case 'deny':
            case 'cancel':
                $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                break;
            case 'expire':
                $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);
                break;
            case 'refund':
                $booking->update(['payment_status' => 'refunded']);
                break;
        }
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

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('customer.studio.my-bookings')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
