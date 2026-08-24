<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'slug',
        'deskripsi',
        'kategori',
        'harga_mulai',
        'rating',
        'jumlah_ulasan',
        'ikon',
        'gambar_utama',
        'status',
    ];

    protected $casts = [
        'harga_mulai' => 'decimal:2',
        'rating' => 'decimal:1',
        'jumlah_ulasan' => 'integer',
    ];

    public function pakets()
    {
        return $this->hasMany(PaketLayanan::class, 'layanan_id');
    }

    public function bookings()
    {
        return $this->hasMany(LayananBooking::class, 'layanan_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'layanan_id');
    }

    public function refreshRating()
    {
        $approvedReviews = $this->ulasan()->where('status', 'approved');
        $count = $approvedReviews->count();
        $avgRating = $count > 0 ? $approvedReviews->avg('rating') : 0;

        $this->update([
            'rating' => round($avgRating, 1),
            'jumlah_ulasan' => $count,
        ]);
    }

    public function paketActive()
    {
        return $this->pakets()->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getHargaMulaiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_mulai, 0, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($layanan) {
            if (empty($layanan->slug)) {
                $layanan->slug = Str::slug($layanan->nama_layanan);
            }
        });

        static::updating(function ($layanan) {
            if ($layanan->isDirty('nama_layanan')) {
                $layanan->slug = Str::slug($layanan->nama_layanan);
            }
        });
    }
}
