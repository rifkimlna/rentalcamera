<?php

namespace App\Observers;

use App\Models\Transaksis;
use App\Models\DetailTransaksis;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class TransaksiStockObserver
{
    /**
     * Sinkronisasi stok produk saat status transaksi berubah.
     * Ini panggil di updating() supaya bisa rollback kalau ada error.
     */
    public function updating(Transaksis $transaksi): void
    {
        $oldStatus = $transaksi->getOriginal('status_transaksi');
        $newStatus = $transaksi->status_transaksi;

        // Only proceed if status actually changed
        if ($oldStatus === $newStatus) {
            return;
        }

        // Get all detail transaksis yang relate ke transaksi ini
        $details = DetailTransaksis::where('transaksi_id', $transaksi->id)->get();

        if ($details->isEmpty()) {
            return;
        }

        // Handle each status transition
        switch ($newStatus) {
            case 'dikonfirmasi':
                if ($oldStatus !== 'dikonfirmasi') {
                    $this->incrementBorrowedStock($details);
                }
                break;

            case 'selesai':
                if ($oldStatus !== 'selesai') {
                    $this->decrementBorrowedStock($details);
                    $this->incrementTotalRented($details);
                }
                break;

            case 'dibatalkan':
                if ($oldStatus !== 'dibatalkan') {
                    $this->decrementBorrowedStock($details);
                }
                break;
        }
    }

    /**
     * Tambah stok_dipinjam dan recompute stok_tersedia saat dikonfirmasi.
     */
    protected function incrementBorrowedStock($details): void
    {
        foreach ($details as $detail) {
            $product = Produk::lockForUpdate()->find($detail->produk_id);
            if (!$product) continue;

            $product->stok_dipinjam += $detail->jumlah;
            $product->stok_tersedia = $product->stok_total - $product->stok_dipinjam - $product->stok_rusak;
            $product->save();
        }
    }

    /**
     * Kurangi stok_dipinjam dan recompute stok_tersedia saat selesai/dibatalkan.
     */
    protected function decrementBorrowedStock($details): void
    {
        foreach ($details as $detail) {
            $product = Produk::lockForUpdate()->find($detail->produk_id);
            if (!$product) continue;

            $product->stok_dipinjam = max(0, $product->stok_dipinjam - $detail->jumlah);
            $product->stok_tersedia = $product->stok_total - $product->stok_dipinjam - $product->stok_rusak;
            $product->save();
        }
    }

    /**
     * Tambah jumlah_dipesan saat transaksi selesai.
     */
    protected function incrementTotalRented($details): void
    {
        foreach ($details as $detail) {
            $product = Produk::lockForUpdate()->find($detail->produk_id);
            if (!$product) continue;

            $product->jumlah_dipesan += $detail->jumlah;
            $product->save();
        }
    }
}
