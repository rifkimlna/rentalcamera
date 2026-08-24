<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PaketLayanan extends Model
{
    use HasFactory;

    protected $table = 'paket_layanan';

    protected $fillable = [
        'layanan_id',
        'nama_paket',
        'slug',
        'deskripsi',
        'harga',
        'durasi_jam',
        'include',
        'gambar',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'durasi_jam' => 'integer',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function bookings()
    {
        return $this->hasMany(LayananBooking::class, 'paket_layanan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paket) {
            if (empty($paket->slug)) {
                $paket->slug = Str::slug($paket->nama_paket);
            }
        });
    }
}
