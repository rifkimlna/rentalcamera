<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id',
        'kurir',
        'no_resi',
        'metode',
        'biaya',
        'alamat_asal',
        'alamat_tujuan',
        'status',
        'estimated_delivery',
        'actual_delivery',
        'tracking_data',
        'catatan',
    ];

    protected $casts = [
        'biaya' => 'decimal:2',
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'tracking_data' => 'array',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(Transaksis::class, 'transaksi_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', ['picked_up', 'in_transit']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Methods
    public function getBiayaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->biaya, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'picked_up' => 'Diambil',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Terkirim',
            'returned' => 'Dikembalikan',
            'cancelled' => 'Dibatalkan',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getMetodeLabelAttribute()
    {
        $methods = [
            'pickup' => 'Ambil di Toko',
            'delivery' => 'Dikirim',
            'express' => 'Express',
        ];
        return $methods[$this->metode] ?? $this->metode;
    }

    public function isDelayed()
    {
        if (!$this->estimated_delivery || $this->status === 'delivered') {
            return false;
        }

        return now()->gt($this->estimated_delivery);
    }

    public function getDelayDays()
    {
        if (!$this->isDelayed()) {
            return 0;
        }

        return now()->diffInDays($this->estimated_delivery);
    }

    public function updateTracking($status, $data = null)
    {
        $this->status = $status;
        
        if ($data) {
            $trackingData = $this->tracking_data ?? [];
            $trackingData[] = [
                'status' => $status,
                'timestamp' => now()->toDateTimeString(),
                'data' => $data,
            ];
            $this->tracking_data = $trackingData;
        }

        if ($status === 'delivered') {
            $this->actual_delivery = now();
        }

        return $this->save();
    }
}
