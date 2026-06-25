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
        'gambar_utama',
        'status',
    ];

    protected $casts = [
        'harga_per_jam' => 'decimal:2',
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
