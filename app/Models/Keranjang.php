<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'tanggal_sewa',
        'tanggal_kembali',
    ];

    protected $casts = [
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
        'jumlah' => 'integer',
    ];

    protected $appends = ['lama_sewa', 'subtotal', 'deposit_amount'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Accessors
    public function getLamaSewaAttribute()
    {
        if ($this->tanggal_sewa && $this->tanggal_kembali) {
            $days = Carbon::parse($this->tanggal_sewa)->diffInDays(Carbon::parse($this->tanggal_kembali));
            return $days > 0 ? $days : 1;
        }
        return 0;
    }

    public function getSubtotalAttribute()
    {
        if ($this->produk && $this->lama_sewa > 0) {
            return $this->produk->harga_per_hari * $this->lama_sewa * $this->jumlah;
        }
        return 0;
    }

    public function getDepositAmountAttribute()
    {
        // Deposit is 50% of subtotal
        return $this->subtotal * 0.5;
    }

    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getDepositAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->deposit_amount, 0, ',', '.');
    }

    // Methods
    public function isAvailable()
    {
        if (!$this->produk || !$this->tanggal_sewa || !$this->tanggal_kembali) {
            return false;
        }

        return $this->produk->isAvailableForRental($this->tanggal_sewa, $this->tanggal_kembali, $this->jumlah);
    }

    public function getAvailabilityMessage()
    {
        if (!$this->produk) {
            return 'Produk tidak ditemukan';
        }

        if ($this->produk->status !== 'available') {
            return 'Produk tidak tersedia';
        }

        if ($this->produk->stok_tersedia < $this->jumlah) {
            return 'Stok tidak mencukupi';
        }

        $lamaSewa = $this->lama_sewa;
        if ($lamaSewa < $this->produk->minimum_sewa) {
            return 'Minimum sewa ' . $this->produk->minimum_sewa . ' hari';
        }

        if ($lamaSewa > $this->produk->maximum_sewa) {
            return 'Maksimum sewa ' . $this->produk->maximum_sewa . ' hari';
        }

        return 'Tersedia';
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($keranjang) {
            // Ensure return date is after start date
            if ($keranjang->tanggal_sewa && $keranjang->tanggal_kembali) {
                if ($keranjang->tanggal_kembali <= $keranjang->tanggal_sewa) {
                    $keranjang->tanggal_kembali = Carbon::parse($keranjang->tanggal_sewa)->addDay();
                }
            }
        });
    }
}