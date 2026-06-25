<?php

namespace App\Services;

use App\Models\Transaksis; // DIUBAH: dari Transaksi menjadi Transaksis
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\CoreApi;
use Midtrans\Notification as MidtransNotification;

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
        try {
            // Prepare transaction details
            $transactionDetails = [
                'order_id' => $transaksi->kode_transaksi . '-' . time(),
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

            // Add shipping fee if exists
            if ($transaksi->biaya_pengiriman > 0) {
                $itemDetails[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $transaksi->biaya_pengiriman,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman',
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
                    'address' => $transaksi->alamat_pengiriman,
                    'city' => $transaksi->kota_pengiriman,
                    'postal_code' => $transaksi->kode_pos_pengiriman,
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'first_name' => $transaksi->nama_customer,
                    'address' => $transaksi->alamat_pengiriman,
                    'city' => $transaksi->kota_pengiriman,
                    'postal_code' => $transaksi->kode_pos_pengiriman,
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
                    'unit' => 'hour',
                    'duration' => config('midtrans.payment_expiry_hours', 24),
                ],
            ];

            if (!empty($enabledPayments)) {
                $params['enabled_payments'] = $enabledPayments;
            }

            // Add specific payment method configurations
            if ($paymentMethod && $paymentMethod->bank_code) {
                $params['bank_transfer'] = [
                    'bank' => $paymentMethod->bank_code,
                    'va_number' => $this->generateVANumber($paymentMethod->bank_code),
                ];
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
    protected function generateVANumber($bankCode)
    {
        // This should generate a unique VA number based on your business logic
        // For example: merchant_id + timestamp + random number
        $timestamp = time();
        $random = rand(1000, 9999);
        
        // Different banks have different VA number formats
        switch ($bankCode) {
            case 'bca':
                // BCA VA: 8 digits
                return substr($this->merchantId . $timestamp, 0, 8);
            case 'bni':
                // BNI VA: 10 digits
                return substr($this->merchantId . $timestamp, 0, 10);
            case 'mandiri':
                // Mandiri VA: 12 digits
                return substr($this->merchantId . $timestamp, 0, 12);
            case 'bri':
                // BRI VA: 15 digits
                return substr($this->merchantId . $timestamp . $random, 0, 15);
            default:
                // Default 10 digits
                return substr($this->merchantId . $timestamp, 0, 10);
        }
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
     * Handle Midtrans notification.
     */
    public function handleNotification($requestData)
    {
        try {
            /** @var \stdClass $notification */
            $notification = new MidtransNotification();
            
            $transactionStatus = $notification->transaction_status ?? null;
            $fraudStatus = $notification->fraud_status ?? null;
            $orderId = $notification->order_id ?? null;
            
            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);
            
            // Find transaction - DIUBAH: Transaksis
            $transaksi = Transaksis::where('midtrans_order_id', $orderId)->first();
            
            if (!$transaksi) {
                Log::error('Transaction not found for Midtrans notification', [
                    'order_id' => $orderId,
                ]);
                return false;
            }
            
            // Handle transaction status
            $this->updateTransactionStatus($transaksi, $transactionStatus, $fraudStatus, $notification);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'request_data' => $requestData,
            ]);
            
            return false;
        }
    }

    /**
     * Update transaction status based on Midtrans notification.
     * 
     * @param Transaksis $transaksi - DIUBAH: parameter type
     * @param string $transactionStatus
     * @param string $fraudStatus
     * @param \stdClass $notification
     */
    protected function updateTransactionStatus(Transaksis $transaksi, $transactionStatus, $fraudStatus, $notification) // DIUBAH: parameter type
    {
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'challenge') {
                    $transaksi->update([
                        'status_pembayaran' => 'pending',
                        'status_transaksi' => 'menunggu_pembayaran',
                    ]);
                } else if ($fraudStatus == 'accept') {
                    $transaksi->update([
                        'status_pembayaran' => 'settlement',
                        'status_transaksi' => 'dikonfirmasi',
                        'paid_at' => now(),
                        'confirmed_at' => now(),
                    ]);
                }
                break;
                
            case 'settlement':
                $transaksi->update([
                    'status_pembayaran' => 'settlement',
                    'status_transaksi' => 'dikonfirmasi',
                    'paid_at' => now(),
                    'confirmed_at' => now(),
                ]);
                break;
                
            case 'pending':
                $transaksi->update([
                    'status_pembayaran' => 'pending',
                    'status_transaksi' => 'menunggu_pembayaran',
                ]);
                break;
                
            case 'deny':
                $transaksi->update([
                    'status_pembayaran' => 'deny',
                    'status_transaksi' => 'ditolak',
                    'cancelled_at' => now(),
                ]);
                break;
                
            case 'cancel':
            case 'expire':
                $transaksi->update([
                    'status_pembayaran' => $transactionStatus,
                    'status_transaksi' => 'dibatalkan',
                    'cancelled_at' => now(),
                ]);
                break;
                
            case 'refund':
            case 'partial_refund':
                $transaksi->update([
                    'status_pembayaran' => $transactionStatus,
                    'refunded_at' => now(),
                ]);
                break;
        }
        
        // Save payment log
        $this->savePaymentLog($transaksi, $notification);
    }

    /**
     * Save payment log.
     * 
     * @param Transaksis $transaksi - DIUBAH: parameter type
     * @param \stdClass $notification
     */
    protected function savePaymentLog(Transaksis $transaksi, $notification) // DIUBAH: parameter type
    {
        $paymentLogData = [
            'transaksi_id' => $transaksi->id,
            'order_id' => $notification->order_id ?? null,
            'transaction_id' => $notification->transaction_id ?? null,
            'transaction_status' => $notification->transaction_status ?? null,
            'payment_type' => $notification->payment_type ?? null,
            'gross_amount' => $notification->gross_amount ?? null,
            'fraud_status' => $notification->fraud_status ?? null,
            'status_code' => $notification->status_code ?? null,
            'status_message' => $notification->status_message ?? null,
            'signature_key' => $notification->signature_key ?? null,
            'bank' => $notification->bank ?? null,
            'va_number' => isset($notification->va_numbers[0]) ? $notification->va_numbers[0]->va_number : null,
            'bill_key' => $notification->bill_key ?? null,
            'biller_code' => $notification->biller_code ?? null,
            'payment_code' => $notification->payment_code ?? null,
            'store' => $notification->store ?? null,
            'merchant_id' => $notification->merchant_id ?? null,
            'masked_card' => $notification->masked_card ?? null,
            'card_type' => $notification->card_type ?? null,
            'approval_code' => $notification->approval_code ?? null,
            'channel_response_code' => $notification->channel_response_code ?? null,
            'channel_response_message' => $notification->channel_response_message ?? null,
            'currency' => $notification->currency ?? 'IDR',
            'request_data' => json_encode($notification),
        ];
        
        PaymentLog::create($paymentLogData);
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
