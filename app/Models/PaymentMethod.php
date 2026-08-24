<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'name',
        'type',
        'bank_code',
        'fee_percentage',
        'fee_flat',
        'minimum_amount',
        'maximum_amount',
        'is_active',
        'icon',
        'instructions',
        'midtrans_payment_type',
        'sort_order',
    ];

    protected $casts = [
        'fee_percentage' => 'decimal:2',
        'fee_flat' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function transaksis()
    {
        return $this->hasMany(Transaksis::class, 'payment_method_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('type', 'bank_transfer');
    }

    public function scopeEwallet($query)
    {
        return $query->where('type', 'ewallet');
    }

    public function scopeQris($query)
    {
        return $query->where('type', 'qris');
    }

    public function scopeCreditCard($query)
    {
        return $query->where('type', 'credit_card');
    }

    public function scopeCstore($query)
    {
        return $query->where('type', 'cstore');
    }

    public function scopeCod($query)
    {
        return $query->where('type', 'cod');
    }

    // Methods
    public function getTypeLabelAttribute()
    {
        $types = [
            'bank_transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'qris' => 'QRIS',
            'credit_card' => 'Kartu Kredit',
            'cstore' => 'Convenience Store',
            'cod' => 'Cash on Delivery',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getFeePercentageFormattedAttribute()
    {
        return $this->fee_percentage . '%';
    }

    public function getFeeFlatFormattedAttribute()
    {
        return 'Rp ' . number_format($this->fee_flat, 0, ',', '.');
    }

    public function getMinimumAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->minimum_amount, 0, ',', '.');
    }

    public function getMaximumAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->maximum_amount, 0, ',', '.');
    }

    public function calculateFee($amount)
    {
        $fee = $this->fee_flat;
        $fee += ($amount * $this->fee_percentage / 100);
        return $fee;
    }

    public function getTotalAmount($amount)
    {
        return $amount + $this->calculateFee($amount);
    }

    public function isAvailableForAmount($amount)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($amount < $this->minimum_amount) {
            return false;
        }

        if ($amount > $this->maximum_amount) {
            return false;
        }

        return true;
    }

    public function getInstructionsArray()
    {
        if (empty($this->instructions)) {
            return [];
        }

        return explode("\n", $this->instructions);
    }
}
