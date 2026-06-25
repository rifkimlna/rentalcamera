<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'icon',
        'urutan',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
        'urutan' => 'integer',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'kategori_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama_kategori');
    }

    // Methods
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }

    public function getAvailableProductCountAttribute()
    {
        return $this->products()->where('status', 'available')->count();
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama_kategori')) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });
    }
}