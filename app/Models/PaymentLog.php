<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentLog extends Model
{
    use HasFactory;

    protected $table = 'payment_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id',
        'order_id',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'fraud_status',
        'status_code',
        'status_message',
        'signature_key',
        'bank',
        'va_number',
        'bill_key',
        'biller_code',
        'payment_code',
        'store',
        'merchant_id',
        'masked_card',
        'card_type',
        'approval_code',
        'channel_response_code',
        'channel_response_message',
        'currency',
        'request_data',
        'response_data',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(Transaksis::class, 'transaksi_id');
    }

    // Scopes
    public function scopeSuccess($query)
    {
        return $query->whereIn('transaction_status', ['settlement', 'capture']);
    }

    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('transaction_status', ['cancel', 'expire', 'failure', 'deny']);
    }

    // Methods
    public function getGrossAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->gross_amount, 0, ',', '.');
    }

    public function getTransactionStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'capture' => 'Terkonfirmasi',
            'settlement' => 'Lunas',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kadaluarsa',
            'failure' => 'Gagal',
        ];
        return $statuses[$this->transaction_status] ?? $this->transaction_status;
    }

    public function getFraudStatusLabelAttribute()
    {
        $statuses = [
            'accept' => 'Diterima',
            'challenge' => 'Tantangan',
            'deny' => 'Ditolak',
        ];
        return $statuses[$this->fraud_status] ?? $this->fraud_status;
    }

    public function isSuccessful()
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    public function isFailed()
    {
        return in_array($this->transaction_status, ['cancel', 'expire', 'failure', 'deny']);
    }
}
