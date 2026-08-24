<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherUsage extends Model
{
    use HasFactory;

    protected $table = 'voucher_usage';
    protected $primaryKey = 'id';

    protected $fillable = [
        'voucher_id',
        'user_id',
        'transaksi_id',
        'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    // Relationships
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksis::class, 'transaksi_id');
    }

    // Methods
    public function getDiscountAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }
}