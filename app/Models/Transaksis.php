<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Transaksis extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'uuid',
        'kode_transaksi',
        'user_id',
        'nama_customer',
        'telepon_customer',
        'email_customer',
        'latitude',
        'longitude',
        'subtotal',
        'diskon',
        'kode_voucher',
        'biaya_asuransi',
        'biaya_lainnya',
        'total_sewa',
        'admin_fee',
        'grand_total',
        'payment_method_id',
        'payment_type',
        'bank',
        'va_number',
        'payment_code',
        'pdf_url',
        'midtrans_order_id',
        'midtrans_token',
        'midtrans_redirect_url',
        'status_pembayaran',
        'status_transaksi',
        'tanggal_pengambilan',
        'tanggal_pengembalian',
        'lama_sewa',
        'metode_pengambilan',
        'metode_pengembalian',
        'catatan',
        'catatan_admin',
        'deposit_amount',
        'payment_expired_at',
        'paid_at',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'refunded_at',
    ];

    protected $casts = [
        'uuid' => 'string',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',

        'biaya_asuransi' => 'decimal:2',
        'biaya_lainnya' => 'decimal:2',
        'total_sewa' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'payment_expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',

        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
        'tanggal_pengambilan' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'lama_sewa' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function detailTransaksis()
{
    return $this->hasMany(DetailTransaksis::class, 'transaksi_id');
}



    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class, 'transaksi_id');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'transaksi_id');
    }

    public function reviews()
    {
        return $this->hasMany(Ulasan::class, 'transaksi_id');
    }

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class, 'transaksi_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'transaksi_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->whereIn('status_pembayaran', ['settlement', 'capture']);
    }

    public function scopePending($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->whereIn('status_pembayaran', ['cancel', 'expire', 'failure', 'deny']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status_transaksi', 'selesai');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status_transaksi', ['dikonfirmasi', 'siap_diambil', 'diproses']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('kode_transaksi', 'like', "%{$search}%")
                    ->orWhere('nama_customer', 'like', "%{$search}%")
                    ->orWhere('email_customer', 'like', "%{$search}%")
                    ->orWhere('telepon_customer', 'like', "%{$search}%");
    }

    // Methods
    public function getGrandTotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getStatusPembayaranLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'capture' => 'Terkonfirmasi',
            'settlement' => 'Lunas',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kadaluarsa',
            'failure' => 'Gagal',
            'refund' => 'Dikembalikan',
            'partial_refund' => 'Pengembalian Sebagian',
            'chargeback' => 'Chargeback',
        ];
        return $statuses[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    public function getStatusTransaksiLabelAttribute()
    {
        $statuses = [
            'draft' => 'Draft',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Diproses',
            'dikonfirmasi' => 'Dikonfirmasi',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'ditolak' => 'Ditolak',
        ];
        return $statuses[$this->status_transaksi] ?? $this->status_transaksi;
    }


    public function getMetodePengambilanLabelAttribute()
    {
        return 'Ambil di Toko';
    }

    public function getMetodePengembalianLabelAttribute()
    {
        return 'Kembali ke Toko';
    }

    public function getProductsAttribute()
    {
        return $this->detailTransaksis->map(function ($detail) {
            return $detail->produk;
        });
    }

    public function getProductNamesAttribute()
    {
        return $this->detailTransaksis->map(function ($detail) {
            return $detail->nama_produk;
        })->implode(', ');
    }

    public function getTotalQuantityAttribute()
    {
        return $this->detailTransaksis->sum('jumlah');
    }

    public function canBeReviewed()
    {
        return $this->status_transaksi === 'selesai' && !$this->reviews()->exists();
    }

    public function hasReviewForProduct($produkId)
    {
        return $this->reviews()->where('produk_id', $produkId)->exists();
    }

    public function canBeCancelled()
    {
        return in_array($this->status_pembayaran, ['pending']) &&
               in_array($this->status_transaksi, ['menunggu_pembayaran', 'diproses']);
    }

    public function canPickup()
    {
        return $this->status_transaksi === 'siap_diambil' &&
               in_array($this->status_pembayaran, ['settlement', 'capture']);
    }

    public function canReturn()
    {
        return in_array($this->status_transaksi, ['dikonfirmasi', 'siap_diambil', 'diproses']) &&
               in_array($this->status_pembayaran, ['settlement', 'capture']);
    }

    public function isOverdue()
    {
        if (!$this->tanggal_pengembalian) {
            return false;
        }

        return in_array($this->status_transaksi, ['dikonfirmasi', 'siap_diambil', 'diproses']) &&
               now()->gt($this->tanggal_pengembalian);
    }

    public function calculateOverdueDays()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->tanggal_pengembalian);
    }

    public function calculateOverdueFee()
    {
        $days = $this->calculateOverdueDays();
        // 10% per day from total sewa
        return $this->total_sewa * 0.1 * $days;
    }

    public function markAsPaid()
    {
        $this->update([
            'status_pembayaran' => 'settlement',
            'paid_at' => now(),
            'status_transaksi' => 'dikonfirmasi',
            'confirmed_at' => now(),
        ]);
    }

    public function markAsCancelled($reason = null)
    {
        $this->update([
            'status_pembayaran' => 'cancel',
            'status_transaksi' => 'dibatalkan',
            'cancelled_at' => now(),
            'catatan_admin' => $reason ?: $this->catatan_admin,
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status_transaksi' => 'selesai',
            'completed_at' => now(),

        ]);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (empty($transaksi->uuid)) {
                $transaksi->uuid = Str::uuid();
            }
            if (empty($transaksi->kode_transaksi)) {
                $transaksi->kode_transaksi = 'TRX' . date('Ymd') . strtoupper(Str::random(4));
            }
        });
    }
}