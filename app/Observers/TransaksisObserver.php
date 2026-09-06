<?php

namespace App\Observers;

use App\Models\Transaksis;
use Illuminate\Support\Facades\DB;

class TransaksisObserver
{
    /**
     * Sinkronisasi status_transaksi dan status_pembayaran.
     * 
     * Aturan:
     * - Jika status_pembayaran = settlement/capture → status_transaksi minimal dikonfirmasi
     * - Jika status_transaksi = selesai → status_pembayaran harus settlement/capture (bukan pending)
     * - Jika status_transaksi = dibatalkan → status_pembayaran harus cancel/expire/deny
     * - Jika status_pembayaran = cancel/expire/deny → status_transaksi = dibatalkan
     */
    public function updating(Transaksis $transaksi): void
    {
        $oldStatusTransaksi = $transaksi->getOriginal('status_transaksi');
        $oldStatusPembayaran = $transaksi->getOriginal('status_pembayaran');
        $newStatusTransaksi = $transaksi->status_transaksi;
        $newStatusPembayaran = $transaksi->status_pembayaran;

        // 1. Jika status_pembayaran settled/capture, tapi status_transaksi belum dikonfirmasi
        if (in_array($newStatusPembayaran, ['settlement', 'capture']) && 
            !in_array($newStatusTransaksi, ['dikonfirmasi', 'siap_diambil', 'diproses', 'selesai'])) {
            // Lookup manual already handled by admin updateStatus - don't override
            // Tapi kalau memang dari Midtrans notification, ini otomatis handled di MidtransService
        }

        // 2. STATUS TRANSaksi = selesai tapi status_pembayaran = pending → INKONSISTEN
        // Ini bug yang sedang terjadi. Kita koreksi: set status_pembayaran ke settlement
        // KECUALI kalau status_pembayaran memang sengaja di-set ke pending oleh sistem
        // (misal: untuk transaksi yang selesai tapi masih perlu konfirmasi manual)
        if ($newStatusTransaksi === 'selesai' && $newStatusPembayaran === 'pending') {
            // Hanya koreksi kalau transaksi memang sudah dibayar (miliki paid_at)
            if ($transaksi->paid_at) {
                $transaksi->status_pembayaran = 'settlement';
            }
        }

        // 3. STATUS TRANSaksi = dibatalkan tapi status_pembayaran bukan cancelled family
        if ($newStatusTransaksi === 'dibatalkan' && 
            !in_array($newStatusPembayaran, ['cancel', 'expire', 'deny', 'failure'])) {
            // Kalau memang ada paid_at, berarti udah dibayar → jangan ubah ke cancel
            // Biarkan sebagai cancelled transaksi tapi payment tetap settlement
            if (!$transaksi->paid_at) {
                $transaksi->status_pembayaran = 'cancel';
            }
        }

        // 4. STATUS Pembayaran = cancel/expire/deny tapi status_transaksi bukan dibatalkan
        if (in_array($newStatusPembayaran, ['cancel', 'expire', 'deny', 'failure']) && 
            $newStatusTransaksi !== 'dibatalkan') {
            // Kalau transaksi belum dikonfirmasi, batalkan
            if (!in_array($oldStatusTransaksi, ['dikonfirmasi', 'siap_diambil', 'diproses', 'selesai'])) {
                $transaksi->status_transaksi = 'dibatalkan';
                $transaksi->cancelled_at = now();
            }
        }
    }

    /**
     * Setelah update, pastikan tidak ada inkonsistensi.
     * Ini catch-all untuk kalau updating tidak menangani sesuatu.
     */
    public function updated(Transaksis $transaksi): void
    {
        // Jika transaksi selesai tapi belum ada completed_at, set sekarang
        if ($transaksi->status_transaksi === 'selesai' && !$transaksi->completed_at) {
            $transaksi->completed_at = now();
            $transaksi->saveQuietly();
        }

        // Jika transaksi dibatalkan tapi belum ada cancelled_at, set sekarang
        if ($transaksi->status_transaksi === 'dibatalkan' && !$transaksi->cancelled_at) {
            $transaksi->cancelled_at = now();
            $transaksi->saveQuietly();
        }
    }
}
