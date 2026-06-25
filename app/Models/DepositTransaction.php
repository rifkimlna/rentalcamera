<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class DepositTransaction extends Model
{
    use HasFactory;

    protected $table = 'deposit_transactions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'transaksi_id',
        'kode_transaksi',
        'type',
        'amount',
        'previous_balance',
        'current_balance',
        'payment_method_id',
        'status',
        'midtrans_order_id',
        'description',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // Scopes
    public function scopeTopup($query)
    {
        return $query->where('type', 'topup');
    }

    public function scopeWithdraw($query)
    {
        return $query->where('type', 'withdraw');
    }

    public function scopePayment($query)
    {
        return $query->where('type', 'payment');
    }

    public function scopeRefund($query)
    {
        return $query->where('type', 'refund');
    }

    public function scopePenalty($query)
    {
        return $query->where('type', 'penalty');
    }

    public function scopeReward($query)
    {
        return $query->where('type', 'reward');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Methods
    public function getAmountFormattedAttribute()
    {
        $prefix = in_array($this->type, ['topup', 'refund', 'reward']) ? '+' : '-';
        return $prefix . 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getPreviousBalanceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->previous_balance, 0, ',', '.');
    }

    public function getCurrentBalanceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->current_balance, 0, ',', '.');
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'topup' => 'Top Up',
            'withdraw' => 'Withdraw',
            'payment' => 'Pembayaran',
            'refund' => 'Pengembalian',
            'penalty' => 'Denda',
            'reward' => 'Reward',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'success' => 'Sukses',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function isPositive()
    {
        return in_array($this->type, ['topup', 'refund', 'reward']);
    }

    public function isNegative()
    {
        return in_array($this->type, ['withdraw', 'payment', 'penalty']);
    }

    public function markAsSuccess()
    {
        $this->status = 'success';
        $this->save();

        // Update user balance
        if ($this->user && $this->isPositive()) {
            $this->user->addDeposit($this->amount);
        }
    }

    public function markAsFailed()
    {
        $this->status = 'failed';
        $this->save();
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deposit) {
            if (empty($deposit->kode_transaksi)) {
                $deposit->kode_transaksi = 'DEP' . date('Ymd') . strtoupper(\Illuminate\Support\Str::random(4));
            }

            // Set previous balance
            if ($deposit->user) {
                $deposit->previous_balance = $deposit->user->saldo_deposit;
                
                // Calculate current balance
                if ($deposit->isPositive()) {
                    $deposit->current_balance = $deposit->previous_balance + $deposit->amount;
                } else {
                    $deposit->current_balance = $deposit->previous_balance - $deposit->amount;
                }
            }
        });

        static::created(function ($deposit) {
            // Update user balance if transaction is successful
            if ($deposit->status === 'success' && $deposit->user) {
                if ($deposit->isPositive()) {
                    $deposit->user->addDeposit($deposit->amount);
                } else {
                    $deposit->user->deductDeposit($deposit->amount);
                }
            }
        });
    }
}