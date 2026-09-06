<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kategori_id',
        'brand_id',
        'kode_produk',
        'nama_produk',
        'slug',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'spesifikasi',
        'fitur',
        'harga_per_hari',
        'stok_total',
        'stok_tersedia',
        'stok_rusak',
        'stok_dipinjam',
        'minimum_sewa',
        'maximum_sewa',
        'rating',
        'jumlah_ulasan',
        'jumlah_dipesan',
        'gambar_utama',
        'gambar_tambahan',
        'status',
        'is_featured',
        'is_recommended',
        'berat',
        'dimensi',
        'serial_number',
        'tahun_pembuatan',
        'kondisi',
    ];

    protected $casts = [
        'spesifikasi' => 'array',
        'gambar_tambahan' => 'array',
        'harga_per_hari' => 'decimal:2',
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer',
        'stok_rusak' => 'integer',
        'stok_dipinjam' => 'integer',
        'minimum_sewa' => 'integer',
        'maximum_sewa' => 'integer',
        'rating' => 'decimal:2',
        'jumlah_ulasan' => 'integer',
        'jumlah_dipesan' => 'integer',
        'is_featured' => 'boolean',
        'is_recommended' => 'boolean',
        'berat' => 'decimal:2',
        'tahun_pembuatan' => 'integer',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksis::class, 'produk_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'produk_id');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'produk_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('stok_tersedia', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%")
                    ->orWhere('deskripsi_singkat', 'like', "%{$search}%");
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('harga_per_hari', [$min, $max]);
    }

    public function scopeSortBy($query, $sortBy)
    {
        switch ($sortBy) {
            case 'harga_terendah':
                return $query->orderBy('harga_per_hari', 'asc');
            case 'harga_tertinggi':
                return $query->orderBy('harga_per_hari', 'desc');
            case 'rating':
                return $query->orderBy('rating', 'desc');
            case 'terbaru':
                return $query->orderBy('created_at', 'desc');
            case 'terlaris':
                return $query->orderBy('jumlah_dipesan', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }

    // Methods
    public function getHargaPerHariFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_hari, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'available' => 'Tersedia',
            'unavailable' => 'Tidak Tersedia',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getKondisiLabelAttribute()
    {
        $kondisis = [
            'baru' => 'Baru',
            'bekas_excellent' => 'Bekas (Excellent)',
            'bekas_good' => 'Bekas (Good)',
            'bekas_fair' => 'Bekas (Fair)',
        ];
        return $kondisis[$this->kondisi] ?? $this->kondisi;
    }

    public function getRatingStarsAttribute()
    {
        $stars = '';
        $fullStars = floor($this->rating);
        $hasHalfStar = ($this->rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

        for ($i = 0; $i < $fullStars; $i++) {
            $stars .= '<i class="bi bi-star-fill text-warning"></i>';
        }
        
        if ($hasHalfStar) {
            $stars .= '<i class="bi bi-star-half text-warning"></i>';
        }
        
        for ($i = 0; $i < $emptyStars; $i++) {
            $stars .= '<i class="bi bi-star text-warning"></i>';
        }
        
        return $stars;
    }

    public function calculateTotalHarga($lamaSewa, $jumlah = 1)
    {
        return $this->harga_per_hari * $lamaSewa * $jumlah;
    }

    public function calculateDeposit($lamaSewa, $jumlah = 1)
    {
        // Deposit biasanya 50% dari total harga
        return $this->calculateTotalHarga($lamaSewa, $jumlah) * 0.5;
    }

    public function isAvailableForRental($tanggalSewa, $tanggalKembali, $jumlah = 1)
    {
        // Check if product is available
        if ($this->status !== 'available' || $this->stok_tersedia < $jumlah) {
            return false;
        }

        // Check if rental period is within limits
        $lamaSewa = \Carbon\Carbon::parse($tanggalSewa)->diffInDays(\Carbon\Carbon::parse($tanggalKembali));
        if ($lamaSewa < 1) $lamaSewa = 1;
        if ($lamaSewa < $this->minimum_sewa || $lamaSewa > $this->maximum_sewa) {
            return false;
        }

        return true;
    }

    public function updateStock($type, $quantity)
    {
        $q = max(0, (int) $quantity);
        $id = $this->getKey();
        $ok = true;

        // Semua mutasi stok dilakukan atomik di level database (bukan read-modify-write)
        // agar aman dari race condition saat checkout paralel.
        switch ($type) {
            case 'increase':
                static::whereKey($id)->increment('stok_total', $q);
                static::whereKey($id)->increment('stok_tersedia', $q);
                break;
            case 'decrease':
                $ok = (bool) static::whereKey($id)
                    ->where('stok_tersedia', '>=', $q)
                    ->where('stok_total', '>=', $q)
                    ->decrement('stok_total', $q);
                if ($ok) {
                    static::whereKey($id)->decrement('stok_tersedia', $q);
                }
                break;
            case 'rent':
                // Kurangi hanya jika stok cukup; gagal jika sudah habis (race lost)
                $ok = (bool) static::whereKey($id)
                    ->where('stok_tersedia', '>=', $q)
                    ->decrement('stok_tersedia', $q);
                if ($ok) {
                    static::whereKey($id)->increment('stok_dipinjam', $q);
                }
                break;
            case 'return':
                // Kembalikan maksimal sejumlah unit yang benar-benar sedang dipinjam
                $dipinjamSekarang = (int) static::whereKey($id)->value('stok_dipinjam');
                $dikembalikan = min($q, max(0, $dipinjamSekarang));
                static::whereKey($id)->increment('stok_tersedia', $dikembalikan);
                static::whereKey($id)->decrement('stok_dipinjam', $dikembalikan);
                break;
            case 'damage':
                $ok = (bool) static::whereKey($id)
                    ->where('stok_tersedia', '>=', $q)
                    ->decrement('stok_tersedia', $q);
                if ($ok) {
                    static::whereKey($id)->increment('stok_rusak', $q);
                }
                break;
            case 'repair':
                // Perbaiki maksimal sejumlah unit yang benar-benar rusak
                $rusakSekarang = (int) static::whereKey($id)->value('stok_rusak');
                $diperbaiki = min($q, max(0, $rusakSekarang));
                static::whereKey($id)->decrement('stok_rusak', $diperbaiki);
                static::whereKey($id)->increment('stok_tersedia', $diperbaiki);
                break;
        }

        // Sinkronkan state in-memory dengan nilai terbaru di database
        $this->refresh();

        return $ok;
    }

    public function incrementOrderCount($quantity = 1)
    {
        static::whereKey($this->getKey())->increment('jumlah_dipesan', max(0, (int) $quantity));
        $this->refresh();

        return true;
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

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama_produk);
            }
            if (empty($produk->kode_produk)) {
                $produk->kode_produk = 'PROD' . strtoupper(Str::random(8));
            }
            if (empty($produk->stok_tersedia)) {
                $produk->stok_tersedia = $produk->stok_total;
            }
        });

        static::updating(function ($produk) {
            if ($produk->isDirty('nama_produk')) {
                $produk->slug = Str::slug($produk->nama_produk);
            }
        });
    }
}