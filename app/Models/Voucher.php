<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_voucher',
        'nama_voucher',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'kuota',
        'kuota_terpakai',
        'start_date',
        'end_date',
        'is_active',
        'user_id',
        'kategori_id',
        'produk_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'kuota' => 'integer',
        'kuota_terpakai' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function ($q) {
                        $q->whereNull('kuota')
                          ->orWhereColumn('kuota_terpakai', '<', 'kuota');
                    });
    }

    public function scopePercentage($query)
    {
        return $query->where('type', 'percentage');
    }

    public function scopeFixed($query)
    {
        return $query->where('type', 'fixed');
    }

    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')
              ->orWhere('user_id', $userId);
        });
    }

    // Methods
    public function getValueFormattedAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }

    public function getMinPurchaseFormattedAttribute()
    {
        return 'Rp ' . number_format($this->min_purchase, 0, ',', '.');
    }

    public function getMaxDiscountFormattedAttribute()
    {
        return $this->max_discount ? 'Rp ' . number_format($this->max_discount, 0, ',', '.') : null;
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'percentage' => 'Persentase',
            'fixed' => 'Nominal',
            'shipping' => 'Gratis Ongkir',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getRemainingQuotaAttribute()
    {
        if ($this->kuota === null) {
            return null;
        }
        return $this->kuota - $this->kuota_terpakai;
    }

    public function getIsExpiredAttribute()
    {
        return now()->gt($this->end_date);
    }

    public function getIsStartedAttribute()
    {
        return now()->gte($this->start_date);
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }
        return now()->diffInDays($this->end_date);
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValidForAmount($amount)) {
            return 0;
        }

        $discount = 0;

        switch ($this->type) {
            case 'percentage':
                $discount = $amount * ($this->value / 100);
                if ($this->max_discount && $discount > $this->max_discount) {
                    $discount = $this->max_discount;
                }
                break;
            case 'fixed':
                $discount = $this->value;
                break;
            case 'shipping':
                // For shipping vouchers, discount is applied separately
                $discount = 0;
                break;
        }

        return $discount;
    }

    public function isValidForAmount($amount)
    {
        // Check if voucher is active
        if (!$this->is_active) {
            return false;
        }

        // Check date validity
        if ($this->is_expired || !$this->is_started) {
            return false;
        }

        // Check minimum purchase
        if ($amount < $this->min_purchase) {
            return false;
        }

        // Check quota
        if ($this->kuota !== null && $this->kuota_terpakai >= $this->kuota) {
            return false;
        }

        return true;
    }

    public function isValidForProduct($productId, $kategoriId)
    {
        // If voucher is for specific product
        if ($this->produk_id && $this->produk_id != $productId) {
            return false;
        }

        // If voucher is for specific category
        if ($this->kategori_id && $this->kategori_id != $kategoriId) {
            return false;
        }

        return true;
    }

    public function isValidForUser($userId)
    {
        // If voucher is for specific user
        if ($this->user_id && $this->user_id != $userId) {
            return false;
        }

        return true;
    }

    public function incrementUsage()
    {
        $this->kuota_terpakai++;
        return $this->save();
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voucher) {
            if (empty($voucher->kode_voucher)) {
                $voucher->kode_voucher = strtoupper(\Illuminate\Support\Str::random(8));
            }
        });
    }
}