<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LayananBooking extends Model
{
    use HasFactory;

    protected $table = 'layanan_bookings';

    protected $fillable = [
        'user_id',
        'layanan_id',
        'paket_layanan_id',
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
        'kode_voucher',
        'diskon_voucher',
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
        'diskon_voucher' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'durasi_jam' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function paketLayanan()
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_layanan_id');
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

    public function getDiskonVoucherFormattedAttribute()
    {
        return $this->diskon_voucher > 0 ? 'Rp ' . number_format($this->diskon_voucher, 0, ',', '.') : null;
    }

    public function getGrandTotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
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
        return $this->tipe_booking === 'paket' ? 'Paket Layanan' : 'Layanan Saja';
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
