<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_brand',
        'slug',
        'deskripsi',
        'logo',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Produk::class, 'brand_id');
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

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->nama_brand);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('nama_brand')) {
                $brand->slug = Str::slug($brand->nama_brand);
            }
        });
    }
}