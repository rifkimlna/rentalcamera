<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Promosi extends Model
{
    use HasFactory;

    protected $table = 'promosi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'gambar',
        'type',
        'start_date',
        'end_date',
        'is_active',
        'priority',
        'click_count',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'click_count' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeBanner($query)
    {
        return $query->where('type', 'banner');
    }

    public function scopePopup($query)
    {
        return $query->where('type', 'popup');
    }

    public function scopeSidebar($query)
    {
        return $query->where('type', 'sidebar');
    }

    public function scopeEmail($query)
    {
        return $query->where('type', 'email');
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
    }

    // Methods
    public function getTypeLabelAttribute()
    {
        $types = [
            'banner' => 'Banner',
            'popup' => 'Popup',
            'sidebar' => 'Sidebar',
            'email' => 'Email',
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getIsExpiredAttribute()
    {
        return now()->gt($this->end_date);
    }

    public function getIsStartedAttribute()
    {
        return now()->gte($this->start_date);
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }
        return now()->diffInDays($this->end_date);
    }

    public function incrementClick()
    {
        $this->click_count++;
        return $this->save();
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($promosi) {
            if (empty($promosi->slug)) {
                $promosi->slug = Str::slug($promosi->judul);
            }
        });

        static::updating(function ($promosi) {
            if ($promosi->isDirty('judul')) {
                $promosi->slug = Str::slug($promosi->judul);
            }
        });
    }
}