<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id',
        'user_id',
        'produk_id',
        'rating',
        'judul',
        'komentar',
        'foto_ulasan',
        'balasan',
        'balasan_at',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'foto_ulasan' => 'array',
        'balasan_at' => 'datetime',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(Transaksis::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Methods
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="bi bi-star-fill text-warning"></i>';
            } else {
                $stars .= '<i class="bi bi-star text-warning"></i>';
            }
        }
        return $stars;
    }

    public function hasPhotos()
    {
        return !empty($this->foto_ulasan) && count($this->foto_ulasan) > 0;
    }

    public function getPhotoCount()
    {
        return $this->hasPhotos() ? count($this->foto_ulasan) : 0;
    }

    public function hasReply()
    {
        return !empty($this->balasan);
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->save();

        // Update product rating
        $this->produk->refreshRating();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function reply($message)
    {
        $this->balasan = $message;
        $this->balasan_at = now();
        $this->save();
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($ulasan) {
            if ($ulasan->isDirty('status') && $ulasan->status === 'approved') {
                // Update product rating when review is approved
                $ulasan->produk->refreshRating();
            }
        });
    }
}