<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolios';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'url',
        'embed_url',
        'gambar',
        'platform',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFoto($query)
    {
        return $query->where('tipe', 'foto');
    }

    public function scopeVideo($query)
    {
        return $query->where('tipe', 'video');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    public function getTipeLabelAttribute()
    {
        return $this->tipe === 'foto' ? 'Foto' : 'Video';
    }

    public function getPlatformLabelAttribute()
    {
        $labels = [
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
        ];
        return $labels[$this->platform] ?? $this->platform ?? '-';
    }

    public static function generateEmbedUrl($url)
    {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $match)) {
            return 'https://www.youtube.com/embed/' . $match[1] . '?rel=0&modestbranding=1';
        }

        if (preg_match('/instagram\.com\/(p|reel|tv)\/([a-zA-Z0-9_-]+)/', $url, $match)) {
            return 'https://www.instagram.com/' . $match[1] . '/' . $match[2] . '/embed/';
        }

        return null;
    }

    public static function detectTipeFromUrl($url)
    {
        if (preg_match('/(?:youtube\.com|youtu\.be)/', $url)) {
            return 'video';
        }

        if (preg_match('/instagram\.com\/reel/', $url)) {
            return 'video';
        }

        if (preg_match('/instagram\.com\/p/', $url)) {
            return 'foto';
        }

        return null;
    }

    public static function detectPlatform($url)
    {
        if (preg_match('/instagram\.com/', $url)) {
            return 'instagram';
        }
        if (preg_match('/(youtube\.com|youtu\.be)/', $url)) {
            return 'youtube';
        }
        return null;
    }
}
