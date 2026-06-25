<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudioBooking extends Model
{
    use HasFactory;

    protected $table = 'studio_bookings';

    protected $fillable = [
        'user_id',
        'studio_id',
        'paket_studio_id',
        'tipe_booking',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'durasi_jam',
        'total_harga',
        'payment_method_id',
        'admin_fee',
        'grand_total',
        'catatan',
        'status',
        'payment_status',
        'midtrans_order_id',
        'midtrans_token',
        'midtrans_redirect_url',
        'paid_at',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'total_harga' => 'decimal:2',
        'durasi_jam' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studio_id');
    }

    public function paketStudio()
    {
        return $this->belongsTo(PaketStudio::class, 'paket_studio_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getTipeBookingLabelAttribute()
    {
        return $this->tipe_booking === 'paket' ? 'Paket Studio' : 'Studio Saja';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            'refunded' => 'Dikembalikan',
        ];
        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'failed' => 'badge-error',
            'expired' => 'badge-ghost',
            'refunded' => 'badge-info',
        ];
        return $classes[$this->payment_status] ?? 'badge-ghost';
    }
}
