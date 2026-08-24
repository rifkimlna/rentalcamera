<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaksis extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'kode_produk',
        'nama_produk',
        'harga_per_hari',
        'jumlah',
        'lama_sewa',
        'subtotal',
        'catatan',
    ];

    protected $casts = [
        'harga_per_hari' => 'decimal:2',
        'subtotal' => 'decimal:2',

        'jumlah' => 'integer',
        'lama_sewa' => 'integer',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(Transaksis::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Methods
    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getHargaPerHariFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_hari, 0, ',', '.');
    }

    public function getTotalHariAttribute()
    {
        return $this->lama_sewa * $this->jumlah;
    }

    public function getTotalHargaAttribute()
    {
        return $this->harga_per_hari * $this->lama_sewa * $this->jumlah;
    }

    public function hasReview()
    {
        return $this->transaksi && $this->transaksi->reviews()->where('produk_id', $this->produk_id)->exists();
    }
}