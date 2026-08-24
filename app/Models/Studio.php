<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Studio extends Model
{
    use HasFactory;

    protected $table = 'studio';

    protected $fillable = [
        'nama_studio',
        'slug',
        'deskripsi',
        'fasilitas',
        'harga_per_jam',
        'rating',
        'jumlah_ulasan',
        'gambar_utama',
        'status',
    ];

    protected $casts = [
        'harga_per_jam' => 'decimal:2',
        'rating' => 'decimal:1',
        'jumlah_ulasan' => 'integer',
        'fasilitas' => 'array',
    ];

    public function pakets()
    {
        return $this->hasMany(PaketStudio::class, 'studio_id');
    }

    public function bookings()
    {
        return $this->hasMany(StudioBooking::class, 'studio_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'studio_id');
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

    public function getHargaPerJamFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_jam, 0, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($studio) {
            if (empty($studio->slug)) {
                $studio->slug = Str::slug($studio->nama_studio);
            }
        });

        static::updating(function ($studio) {
            if ($studio->isDirty('nama_studio')) {
                $studio->slug = Str::slug($studio->nama_studio);
            }
        });
    }
}
