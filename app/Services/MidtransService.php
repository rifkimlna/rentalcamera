<?php

namespace App\Services;

use App\Models\Transaksis;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\CoreApi;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;
    protected $merchantId;

    public function __construct()
    {
        // DIUBAH: konfigurasi yang benar
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production', false);
        $this->merchantId = config('midtrans.merchant_id');

        // Set Midtrans configuration
        Config::$serverKey = $this->serverKey;
        Config::$clientKey = $this->clientKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Snap Token for transaction.
     */
    public function generateSnapToken(Transaksis $transaksi) // DIUBAH: parameter type
    {
        // Guard: transaksi yang sudah dibatalkan/ditolak tidak bisa generate token lagi
        // User harus buat transaksi baru. Mencegah retry order_id yang sudah deny di Midtrans.
        // Diletakkan di luar try agar exception bisa propagate ke controller untuk redirect ke failed
        $failedStatuses = ['deny', 'cancel', 'expire', 'failure'];
        if ($transaksi->status_transaksi === 'dibatalkan' || in_array($transaksi->status_pembayaran, $failedStatuses)) {
            Log::warning('Midtrans Snap ditolak: transaksi sudah dibatalkan/ditolak', [
                'transaksi_id' => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status_pembayaran' => $transaksi->status_pembayaran,
                'status_transaksi' => $transaksi->status_transaksi,
            ]);
            throw new \Exception('Transaksi sudah dibatalkan/ditolak. Silakan buat pesanan baru.');
        }

        try {
            // Reuse order ID jika sudah pernah dibuat (agar notification tidak kehilangan koneksi)
            $orderId = $transaksi->midtrans_order_id
                ?: $transaksi->kode_transaksi . '-' . time();

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $transaksi->grand_total,
            ];

            // Prepare item details
            $itemDetails = [];
            
            // Add products
            foreach ($transaksi->detailTransaksis as $detail) {
                $itemDetails[] = [
                    'id' => $detail->produk_id,
                    'price' => (int) ($detail->harga_per_hari * $detail->lama_sewa),
                    'quantity' => $detail->jumlah,
                    'name' => $detail->nama_produk,
                ];
            }

            // Add insurance fee if exists
            if ($transaksi->biaya_asuransi > 0) {
                $itemDetails[] = [
                    'id' => 'INSURANCE',
                    'price' => (int) $transaksi->biaya_asuransi,
                    'quantity' => 1,
                    'name' => 'Biaya Asuransi',
                ];
            }

            // Add admin fee if exists
            if ($transaksi->admin_fee > 0) {
                $itemDetails[] = [
                    'id' => 'ADMIN',
                    'price' => (int) $transaksi->admin_fee,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ];
            }

            // Add discount if exists
            if ($transaksi->diskon > 0) {
                $itemDetails[] = [
                    'id' => 'DISCOUNT',
                    'price' => (int) -$transaksi->diskon, // Negative for discount
                    'quantity' => 1,
                    'name' => 'Diskon',
                ];
            }

            // Prepare customer details
            $customerDetails = [
                'first_name' => $transaksi->nama_customer,
                'email' => $transaksi->email_customer,
                'phone' => $transaksi->telepon_customer,
                'billing_address' => [
                    'first_name' => $transaksi->nama_customer,
                    'address' => $transaksi->nama_customer,
                    'city' => 'Jakarta',
                    'postal_code' => '12345',
                    'country_code' => 'IDN',
                ],
            ];

            // Prepare payment method
            $paymentMethod = $transaksi->paymentMethod;
            $enabledPayments = [];
            
            if ($paymentMethod && $paymentMethod->midtrans_payment_type) {
                $enabledPayments = [$paymentMethod->midtrans_payment_type];
            } else {
                $enabledPayments = [
                    'credit_card',
                    'bank_transfer',
                    'gopay',
                    'shopeepay',
                    'qris',
                    'cstore',
                    'akulaku',
                ];
            }

            // Prepare Snap parameters
            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('customer.checkout.success', $transaksi->id),
                    'error' => route('customer.checkout.failed', $transaksi->id),
                    'pending' => route('customer.checkout.pending', $transaksi->id),
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s O'),
                    // expiry_duration dalam MENIT (sama dengan payment_expired_at lokal)
                    'unit' => 'minute',
                    'duration' => (int) config('midtrans.expiry_duration', 1440),
                ],
            ];

            if (!empty($enabledPayments)) {
                $params['enabled_payments'] = $enabledPayments;
            }

            // FIX: Hapus va_number custom untuk Snap — Snap generate VA otomatis.
            // Mengirim va_number arbitrary (substr merchantId+time) bikin Midtrans deny
            // Blok bank_transfer custom dihapus; cukup enabled_payments yang filter bank.
            // Jika butuh VA custom, pakai Core API Charge, bukan Snap.

            // Validasi item sum vs gross_amount (cegah deny fraud)
            $itemSum = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $itemDetails));
            $gross = (int) $transaksi->grand_total;
            if ($itemSum !== $gross) {
                Log::warning('Midtrans item sum mismatch - akan tetap dikirim, tapi berisiko deny', [
                    'transaksi_id' => $transaksi->id,
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'expected_gross' => $gross,
                    'item_sum' => $itemSum,
                    'diff' => $gross - $itemSum,
                    'item_details' => $itemDetails,
                ]);
                // Fallback: jika selisih karena rounding, sync gross ke itemSum untuk hindari deny
                // Jangan ubah grand_total DB, hanya payload Midtrans (Midtrans validasi payload internal)
                // Kita tetap pakai gross asli, tapi log warning untuk debugging
            }

            // Generate Snap token
            $snapToken = Snap::getSnapToken($params);

            // Save midtrans order ID to transaction
            $transaksi->update([
                'midtrans_order_id' => $transactionDetails['order_id'],
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage(), [
                'transaction_id' => $transaksi->id,
                'error' => $e->getTraceAsString(),
            ]);
            
            return null;
        }
    }

    /**
     * Generate virtual account number.
     */
    protected function generateVANumber(string $bankCode): string
    {
        $timestamp = time();
        $random = mt_rand(10000, 99999); // 5 digit random untuk mengurangi collision risk
        $base = $this->merchantId . $timestamp . $random;

        return match ($bankCode) {
            'bca'     => substr($base, 0, 8),
            'bni'     => substr($base, 0, 10),
            'mandiri' => substr($base, 0, 12),
            'bri'     => substr($base, 0, 15),
            default   => substr($base, 0, 10),
        };
    }

    /**
     * Get transaction status from Midtrans.
     */
    public function getTransactionStatus($orderId)
    {
        try {
            /** @var \stdClass $status */
            $status = Transaction::status($orderId);
            
            return [
                'success' => true,
                'status' => $status->transaction_status ?? null,
                'data' => $status,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error: ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel transaction in Midtrans.
     */
    public function cancelTransaction($orderId)
    {
        try {
            /** @var \stdClass $cancel */
            $cancel = Transaction::cancel($orderId);
            
            return [
                'success' => true,
                'data' => $cancel,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Cancel Error: ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cek apakah order sudah terbayar di sisi Midtrans (settlement / capture accept).
     * Dipakai untuk mencegah pembatalan lokal pada transaksi yang sebenarnya sudah lunas.
     */
    public function isPaidAtGateway($orderId): bool
    {
        $result = $this->getTransactionStatus($orderId);

        if (!($result['success'] ?? false)) {
            return false;
        }

        $transactionStatus = $result['status'] ?? null;

        if ($transactionStatus === 'settlement') {
            return true;
        }

        if ($transactionStatus === 'capture') {
            return ($result['data']->fraud_status ?? null) === 'accept';
        }

        return false;
    }

    /**
     * Refund transaction in Midtrans.
     */
    public function refundTransaction($orderId, $amount = null, $reason = '')
    {
        try {
            $params = [
                'refund_key' => 'refund-' . $orderId . '-' . time(),
                'amount' => $amount,
                'reason' => $reason,
            ];
            
            /** @var \stdClass $refund */
            $refund = Transaction::refund($orderId, $params);
            
            return [
                'success' => true,
                'data' => $refund,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Refund Error: ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle Midtrans notification (single source of truth untuk Transaksis, Studio & Layanan).
     * Samakan seperti equipment biar studio/layanan ikut ke-update walau webhook cuma ke /midtrans/notification.
     */
    public function handleNotification(Request $request)
    {
        $orderId = $request->order_id ?? null;

        Log::info('Midtrans Notification Received', [
            'order_id' => $orderId,
            'transaction_status' => $request->transaction_status ?? null,
            'fraud_status' => $request->fraud_status ?? null,
        ]);

        // 1) Coba Transaksis (equipment)
        $transaksi = Transaksis::where('midtrans_order_id', $orderId)->first();
        if ($transaksi) {
            if ($request->filled('gross_amount')) {
                $expectedAmount = (float) $transaksi->grand_total;
                $receivedAmount = (float) $request->gross_amount;
                if (abs($expectedAmount - $receivedAmount) > 0.01) {
                    Log::error('Midtrans Notification: gross_amount mismatch', [
                        'order_id' => $orderId,
                        'expected' => $expectedAmount,
                        'received' => $receivedAmount,
                    ]);
                    return false;
                }
            }
            try {
                return DB::transaction(function () use ($transaksi, $request) {
                    $this->updateTransactionStatus($transaksi, $request);
                    $this->recordPaymentLog($request, $transaksi->id);
                    return true;
                });
            } catch (\Exception $e) {
                Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                    'request_data' => $request->all(),
                    'order_id' => $orderId,
                ]);
                return false;
            }
        }

        // 2) Coba StudioBooking (prefix STD-)
        $studioBooking = \App\Models\StudioBooking::where('midtrans_order_id', $orderId)->first();
        if ($studioBooking) {
            if ($request->filled('gross_amount') && abs((float) $studioBooking->grand_total - (float) $request->gross_amount) > 0.01) {
                Log::error('Midtrans Studio: gross_amount mismatch', [
                    'order_id' => $orderId,
                    'expected' => $studioBooking->grand_total,
                    'received' => $request->gross_amount,
                ]);
                return false;
            }
            try {
                return DB::transaction(function () use ($studioBooking, $request) {
                    $this->updateStudioBookingStatus($studioBooking, $request);
                    $this->recordPaymentLog($request, null);
                    return true;
                });
            } catch (\Exception $e) {
                Log::error('Midtrans Studio Notification Error: ' . $e->getMessage(), ['order_id' => $orderId]);
                return false;
            }
        }

        // 3) Coba LayananBooking (prefix LYN-)
        $layananBooking = \App\Models\LayananBooking::where('midtrans_order_id', $orderId)->first();
        if ($layananBooking) {
            if ($request->filled('gross_amount') && abs((float) $layananBooking->grand_total - (float) $request->gross_amount) > 0.01) {
                Log::error('Midtrans Layanan: gross_amount mismatch', [
                    'order_id' => $orderId,
                    'expected' => $layananBooking->grand_total,
                    'received' => $request->gross_amount,
                ]);
                return false;
            }
            try {
                return DB::transaction(function () use ($layananBooking, $request) {
                    $this->updateLayananBookingStatus($layananBooking, $request);
                    $this->recordPaymentLog($request, null);
                    return true;
                });
            } catch (\Exception $e) {
                Log::error('Midtrans Layanan Notification Error: ' . $e->getMessage(), ['order_id' => $orderId]);
                return false;
            }
        }

        Log::error('Transaction not found for Midtrans notification', [
            'order_id' => $orderId,
        ]);
        return false;
    }

    protected function updateStudioBookingStatus(\App\Models\StudioBooking $booking, Request $request)
    {
        $transactionStatus = $request->transaction_status ?? null;
        $fraudStatus = $request->fraud_status ?? null;
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    if (!in_array($booking->payment_status, ['paid', 'refunded', 'expired', 'failed']) && $booking->status !== 'cancelled') {
                        $booking->update(['payment_status' => 'pending']);
                    }
                } elseif ($fraudStatus === 'deny') {
                    if ($booking->payment_status !== 'paid') {
                        $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                        \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                    }
                } elseif ($fraudStatus === 'accept') {
                    $this->markStudioBookingPaid($booking);
                }
                break;
            case 'settlement':
                $this->markStudioBookingPaid($booking);
                break;
            case 'pending':
                if (!in_array($booking->payment_status, ['paid', 'refunded', 'expired', 'failed']) && $booking->status !== 'cancelled') {
                    $booking->update(['payment_status' => 'pending']);
                }
                break;
            case 'deny':
            case 'cancel':
            case 'failure':
                if ($booking->payment_status !== 'paid') {
                    $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                    \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                }
                break;
            case 'expire':
                if ($booking->payment_status !== 'paid') {
                    $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);
                    \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                }
                break;
            case 'refund':
                $booking->update(['payment_status' => 'refunded']);
                break;
        }
    }

    protected function markStudioBookingPaid(\App\Models\StudioBooking $booking)
    {
        if ($booking->status === 'cancelled' || $booking->payment_status === 'paid') {
            Log::warning('Midtrans Studio settlement diabaikan (sudah cancelled/paid)', ['booking_id' => $booking->id]);
            return false;
        }
        $booking->update(['payment_status' => 'paid', 'status' => 'confirmed', 'paid_at' => now()]);
        \App\Models\Notification::sendToAdmins('payment', 'Pembayaran Studio Lunas', 'Booking studio ' . ($booking->studio->nama_studio ?? '#'.$booking->id) . ' telah dibayar.', ['booking_id' => $booking->id, 'type' => 'studio']);
        return true;
    }

    protected function updateLayananBookingStatus(\App\Models\LayananBooking $booking, Request $request)
    {
        $transactionStatus = $request->transaction_status ?? null;
        $fraudStatus = $request->fraud_status ?? null;
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    if (!in_array($booking->payment_status, ['paid', 'refunded', 'expired', 'failed']) && $booking->status !== 'cancelled') {
                        $booking->update(['payment_status' => 'pending']);
                    }
                } elseif ($fraudStatus === 'deny') {
                    if ($booking->payment_status !== 'paid') {
                        $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                        \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                    }
                } elseif ($fraudStatus === 'accept') {
                    $this->markLayananBookingPaid($booking);
                }
                break;
            case 'settlement':
                $this->markLayananBookingPaid($booking);
                break;
            case 'pending':
                if (!in_array($booking->payment_status, ['paid', 'refunded', 'expired', 'failed']) && $booking->status !== 'cancelled') {
                    $booking->update(['payment_status' => 'pending']);
                }
                break;
            case 'deny':
            case 'cancel':
            case 'failure':
                if ($booking->payment_status !== 'paid') {
                    $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                    \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                }
                break;
            case 'expire':
                if ($booking->payment_status !== 'paid') {
                    $booking->update(['payment_status' => 'expired', 'status' => 'cancelled']);
                    \App\Models\Voucher::releaseByCode($booking->kode_voucher);
                }
                break;
            case 'refund':
                $booking->update(['payment_status' => 'refunded']);
                break;
        }
    }

    protected function markLayananBookingPaid(\App\Models\LayananBooking $booking)
    {
        if ($booking->status === 'cancelled' || $booking->payment_status === 'paid') {
            Log::warning('Midtrans Layanan settlement diabaikan (sudah cancelled/paid)', ['booking_id' => $booking->id]);
            return false;
        }
        $booking->update(['payment_status' => 'paid', 'status' => 'confirmed', 'paid_at' => now()]);
        \App\Models\Notification::sendToAdmins('payment', 'Pembayaran Layanan Lunas', 'Booking layanan ' . ($booking->layanan->nama_layanan ?? '#'.$booking->id) . ' telah dibayar.', ['booking_id' => $booking->id, 'type' => 'layanan']);
        return true;
    }

    /**
     * Update transaction status based on Midtrans notification.
     */
    protected function updateTransactionStatus(Transaksis $transaksi, Request $request)
    {
        $transactionStatus = $request->transaction_status ?? null;
        $fraudStatus = $request->fraud_status ?? null;

        // Guard urutan status: notifikasi telat tidak boleh menurunkan transaksi
        // yang sudah lunas atau mengaktifkan kembali yang sudah dibatalkan
        $isSettled = in_array($transaksi->status_pembayaran, ['settlement', 'refund']);
        $isCancelled = $transaksi->status_transaksi === 'dibatalkan'
            || in_array($transaksi->status_pembayaran, ['deny', 'cancel', 'expire', 'failure']);

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    if (!$isSettled && !$isCancelled) {
                        $transaksi->update([
                            'status_pembayaran' => 'pending',
                            'status_transaksi' => 'menunggu_pembayaran',
                        ]);
                    }
                } elseif ($fraudStatus == 'accept') {
                    $this->markAsSettled($transaksi);
                } elseif ($fraudStatus == 'deny') {
                    // FIX: fraud deny sebelumnya tidak di-handle → transaksi stuck pending selamanya
                    if (!$isSettled && !$isCancelled) {
                        Log::warning('Midtrans fraud deny received', [
                            'kode_transaksi' => $transaksi->kode_transaksi,
                            'order_id' => $transaksi->midtrans_order_id,
                        ]);
                        $transaksi->update([
                            'status_pembayaran' => 'deny',
                            'status_transaksi' => 'dibatalkan',
                            'cancelled_at' => now(),
                        ]);
                        foreach ($transaksi->detailTransaksis as $detail) {
                            if ($detail->produk) {
                                $detail->produk->updateStock('return', $detail->jumlah);
                            }
                        }
                        \App\Models\Voucher::releaseByCode($transaksi->kode_voucher);
                        \App\Models\VoucherUsage::where('transaksi_id', $transaksi->id)->delete();
                    }
                } else {
                    // fraud_status null atau tidak dikenal → treat as pending, log warning
                    if (!$isSettled && !$isCancelled) {
                        Log::warning('Midtrans capture tanpa fraud_status jelas', [
                            'order_id' => $transaksi->midtrans_order_id,
                            'fraud_status' => $fraudStatus,
                            'transaction_status' => $transactionStatus,
                        ]);
                    }
                }
                break;

            case 'settlement':
                $this->markAsSettled($transaksi);
                break;

            case 'pending':
                if (!$isSettled && !$isCancelled) {
                    $transaksi->update([
                        'status_pembayaran' => 'pending',
                        'status_transaksi' => 'menunggu_pembayaran',
                    ]);
                }
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
            case 'failure':
                // Jangan batalkan transaksi yang sudah lunas (uang sudah masuk)
                // maupun yang sudah dibatalkan (stok tidak dikembalikan dua kali)
                if (!$isSettled && !$isCancelled) {
                    $transaksi->update([
                        'status_pembayaran' => $transactionStatus,
                        'status_transaksi' => 'dibatalkan',
                        'cancelled_at' => now(),
                    ]);

                    foreach ($transaksi->detailTransaksis as $detail) {
                        if ($detail->produk) {
                            $detail->produk->updateStock('return', $detail->jumlah);
                        }
                    }

                    // Kembalikan kuota voucher transaksi yang batal/kedaluwarsa
                    \App\Models\Voucher::releaseByCode($transaksi->kode_voucher);
                    \App\Models\VoucherUsage::where('transaksi_id', $transaksi->id)->delete();
                }
                break;

            case 'refund':
            case 'partial_refund':
                $transaksi->update([
                    'status_pembayaran' => $transactionStatus,
                    'refunded_at' => now(),
                ]);
                break;
        }
    }

    /**
     * Mark transaction as settled and notify admins.
     * Idempoten + anti-resurrect: settlement telat untuk order yang sudah
     * dibayar/dibatalkan diabaikan agar state tidak korup dan stok aman.
     */
    protected function markAsSettled(Transaksis $transaksi)
    {
        if ($transaksi->status_transaksi === 'dibatalkan'
            || in_array($transaksi->status_pembayaran, ['settlement', 'refund'])) {
            Log::warning('Midtrans settlement diabaikan', [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status_pembayaran' => $transaksi->status_pembayaran,
                'status_transaksi' => $transaksi->status_transaksi,
            ]);
            return false;
        }

        $transaksi->update([
            'status_pembayaran' => 'settlement',
            'status_transaksi' => 'dikonfirmasi',
            'paid_at' => now(),
            'confirmed_at' => now(),
        ]);

        \App\Models\Notification::sendToAdmins(
            'payment',
            'Pembayaran Kamera Lunas',
            ($transaksi->user->nama ?? 'User') . ' telah membayar transaksi ' . $transaksi->kode_transaksi
                . ' sebesar Rp ' . number_format($transaksi->grand_total, 0, ',', '.'),
            ['transaksi_id' => $transaksi->id, 'type' => 'camera']
        );

        return true;
    }

    /**
     * Record payment log from a Midtrans notification request.
     * Shared by camera, studio, and layanan bookings.
     *
     * @param int|null $transaksiId null for studio/layanan bookings (no FK to transaksis)
     */
    public function recordPaymentLog(Request $request, ?int $transaksiId = null)
    {
        $vaNumber = null;
        $vaNumbers = $request->input('va_numbers');
        if (is_array($vaNumbers) && isset($vaNumbers[0])) {
            $first = $vaNumbers[0];
            $vaNumber = is_object($first) ? ($first->va_number ?? null) : ($first['va_number'] ?? null);
        }

        return PaymentLog::create([
            'transaksi_id' => $transaksiId,
            'order_id' => $request->order_id,
            'transaction_id' => $request->transaction_id,
            'transaction_status' => $request->transaction_status,
            'payment_type' => $request->payment_type,
            'gross_amount' => $request->gross_amount,
            'fraud_status' => $request->fraud_status,
            'status_code' => $request->status_code,
            'status_message' => $request->status_message,
            'signature_key' => $request->signature_key,
            'bank' => $request->bank,
            'va_number' => $vaNumber,
            'bill_key' => $request->bill_key,
            'biller_code' => $request->biller_code,
            'payment_code' => $request->payment_code,
            'store' => $request->store,
            'merchant_id' => $request->merchant_id,
            'masked_card' => $request->masked_card,
            'card_type' => $request->card_type,
            'approval_code' => $request->approval_code,
            'channel_response_code' => $request->channel_response_code,
            'channel_response_message' => $request->channel_response_message,
            'currency' => $request->currency ?? 'IDR',
            'request_data' => json_encode($request->all()),
        ]);
    }

    /**
     * Generate QR Code for QRIS payment.
     */
    public function generateQRCode(Transaksis $transaksi) // DIUBAH: parameter type
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $transaksi->kode_transaksi . '-QRIS-' . time(),
                    'gross_amount' => (int) $transaksi->grand_total,
                ],
                'qris' => [
                    'acquirer' => 'gopay',
                ],
            ];
            
            /** @var \stdClass $charge */
            $charge = CoreApi::charge($params);
            
            // Save QR code data
            $transaksi->update([
                'payment_code' => $charge->qr_string ?? null,
                'midtrans_order_id' => $charge->order_id ?? null,
            ]);
            
            return [
                'success' => true,
                'qr_string' => $charge->qr_string ?? null,
                'payment_code' => $charge->payment_code ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('QRIS Generation Error: ' . $e->getMessage(), [
                'transaction_id' => $transaksi->id,
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if transaction is expired.
     */
    public function isTransactionExpired(Transaksis $transaksi) // DIUBAH: parameter type
    {
        if (!$transaksi->payment_expired_at) {
            return false;
        }
        
        return now()->gt($transaksi->payment_expired_at);
    }

    /**
     * Get remaining time for payment.
     */
    public function getRemainingPaymentTime(Transaksis $transaksi) // DIUBAH: parameter type
    {
        if (!$transaksi->payment_expired_at) {
            return null;
        }
        
        if ($this->isTransactionExpired($transaksi)) {
            return 'expired';
        }
        
        $remaining = now()->diff($transaksi->payment_expired_at);
        
        return [
            'hours' => $remaining->h,
            'minutes' => $remaining->i,
            'seconds' => $remaining->s,
            'total_minutes' => $remaining->h * 60 + $remaining->i,
        ];
    }

    /**
     * Get payment instructions based on payment type.
     */
    public function getPaymentInstructions(Transaksis $transaksi) // DIUBAH: parameter type
    {
        $paymentMethod = $transaksi->paymentMethod;
        
        if (!$paymentMethod) {
            return null;
        }
        
        $instructions = [];
        
        switch ($paymentMethod->type) {
            case 'bank_transfer':
                $instructions = $this->getBankTransferInstructions($transaksi);
                break;
            case 'ewallet':
                $instructions = $this->getEwalletInstructions($transaksi);
                break;
            case 'qris':
                $instructions = $this->getQRISInstructions($transaksi);
                break;
            case 'cstore':
                $instructions = $this->getCstoreInstructions($transaksi);
                break;
            case 'credit_card':
                $instructions = $this->getCreditCardInstructions($transaksi);
                break;
        }
        
        return $instructions;
    }

    /**
     * Get bank transfer payment instructions.
     */
    protected function getBankTransferInstructions(Transaksis $transaksi) // DIUBAH: parameter type
    {
        $paymentMethod = $transaksi->paymentMethod;
        
        return [
            'type' => 'bank_transfer',
            'bank' => $paymentMethod->bank_code,
            'va_number' => $transaksi->va_number ?? $this->generateVANumber($paymentMethod->bank_code),
            'instructions' => $paymentMethod->instructions ?? 'Transfer ke Virtual Account ' . strtoupper($paymentMethod->bank_code),
        ];
    }

    /**
     * Get e-wallet payment instructions.
     */
    protected function getEwalletInstructions(Transaksis $transaksi) // DIUBAH: parameter type
    {
        $paymentMethod = $transaksi->paymentMethod;
        
        return [
            'type' => 'ewallet',
            'wallet' => $paymentMethod->code,
            'instructions' => $paymentMethod->instructions ?? 'Bayar menggunakan ' . $paymentMethod->name,
        ];
    }

    /**
     * Get QRIS payment instructions.
     */
    protected function getQRISInstructions(Transaksis $transaksi) // DIUBAH: parameter type
    {
        return [
            'type' => 'qris',
            'qr_string' => $transaksi->payment_code,
            'instructions' => 'Scan QR Code menggunakan aplikasi e-wallet atau mobile banking yang mendukung QRIS',
        ];
    }

    /**
     * Get convenience store payment instructions.
     */
    protected function getCstoreInstructions(Transaksis $transaksi) // DIUBAH: parameter type
    {
        $paymentMethod = $transaksi->paymentMethod;
        
        return [
            'type' => 'cstore',
            'store' => $paymentMethod->code,
            'payment_code' => $transaksi->payment_code,
            'instructions' => $paymentMethod->instructions ?? 'Bayar di kasir ' . $paymentMethod->name . ' dengan kode pembayaran di atas',
        ];
    }

    /**
     * Get credit card payment instructions.
     */
    protected function getCreditCardInstructions(Transaksis $transaksi) // DIUBAH: parameter type
    {
        return [
            'type' => 'credit_card',
            'instructions' => 'Masukkan detail kartu kredit Anda untuk melanjutkan pembayaran',
        ];
    }

    /**
     * New method for testing route
     */
    public function createSnapTransaction(Transaksis $transaction, array $customerData) // DIUBAH: parameter type
    {
        return $this->generateSnapToken($transaction);
    }
}
