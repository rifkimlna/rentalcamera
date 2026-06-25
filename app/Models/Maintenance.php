<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenance';
    protected $primaryKey = 'id';

    protected $fillable = [
        'produk_id',
        'serial_number',
        'jenis_maintenance',
        'deskripsi',
        'biaya',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'teknisi',
        'catatan',
    ];

    protected $casts = [
        'biaya' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relationships
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress']);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('jenis_maintenance', $type);
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
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getJenisMaintenanceLabelAttribute()
    {
        $jenis = [
            'routine' => 'Rutin',
            'repair' => 'Perbaikan',
            'cleaning' => 'Pembersihan',
            'calibration' => 'Kalibrasi',
        ];
        return $jenis[$this->jenis_maintenance] ?? $this->jenis_maintenance;
    }

    public function getDuration()
    {
        if (!$this->tanggal_mulai) {
            return 0;
        }

        $endDate = $this->tanggal_selesai ? Carbon::parse($this->tanggal_selesai) : now();
        return Carbon::parse($this->tanggal_mulai)->diffInDays($endDate);
    }

    public function isOverdue()
    {
        if (!$this->tanggal_selesai || $this->status === 'completed') {
            return false;
        }

        return now()->gt($this->tanggal_selesai);
    }

    public function getOverdueDays()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->tanggal_selesai);
    }

    public function start()
    {
        $this->status = 'in_progress';
        $this->tanggal_mulai = now();
        
        // Set product to maintenance status if it's a repair
        if ($this->jenis_maintenance === 'repair') {
            $this->produk->update(['status' => 'maintenance']);
        }
        
        return $this->save();
    }

    public function complete()
    {
        $this->status = 'completed';
        $this->tanggal_selesai = now();
        
        // Set product back to available if it was under repair
        if ($this->jenis_maintenance === 'repair') {
            $this->produk->update(['status' => 'available']);
        }
        
        return $this->save();
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        
        // Set product back to available if it was under repair
        if ($this->jenis_maintenance === 'repair' && $this->produk->status === 'maintenance') {
            $this->produk->update(['status' => 'available']);
        }
        
        return $this->save();
    }
}