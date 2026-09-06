<?php

namespace App\Services;

use App\Models\User;
use App\Models\Voucher;
use App\Models\PaymentMethod;

class CheckoutService
{
    /**
     * Cari voucher yang berlaku untuk subtotal tertentu.
     */
    public static function findValidVoucher(string $code, User $user, float $subtotal): ?Voucher
    {
        if (empty($code)) {
            return null;
        }

        return Voucher::where('kode_voucher', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($q) use ($user) {
                $q->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->where(function ($q) use ($subtotal) {
                $q->whereNull('min_purchase')
                    ->orWhere('min_purchase', '<=', $subtotal);
            })
            ->where(function ($q) {
                $q->whereNull('kuota')
                    ->orWhereRaw('kuota_terpakai < kuota');
            })
            ->first();
    }

    /**
     * Hitung discount dari voucher.
     */
    public static function calculateVoucherDiscount(Voucher $voucher, float $subtotal): float
    {
        $discount = 0;

        if ($voucher->type === 'percentage') {
            $discount = ($subtotal * $voucher->value) / 100;
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        } elseif ($voucher->type === 'fixed') {
            $discount = $voucher->value;
        }

        // Clamp: diskon tidak boleh melebihi subtotal
        return min($discount, $subtotal);
    }

    /**
     * Hitung admin fee dari payment method.
     */
    public static function calculateAdminFee(PaymentMethod $paymentMethod, float $subtotalAfterDiscount): float
    {
        return $paymentMethod->calculateFee($subtotalAfterDiscount);
    }

    /**
     * Hitung grand total.
     */
    public static function calculateGrandTotal(float $subtotal, float $discount, float $adminFee): float
    {
        $totalSewa = max(0, $subtotal - $discount);
        return $totalSewa + $adminFee;
    }

    /**
     * Cek apakah slot booking dipakai orang lain.
     * Dipakai untuk studio dan layanan.
     */
    public static function isSlotOccupied(
        string $modelClass,
        int $resourceId,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai
    ): bool {
        return $modelClass::where('resource_id', $resourceId)  // placeholder - sesuaikan dengan nama kolom
            ->where('tanggal_booking', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                    ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                    ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                        $q2->where('jam_mulai', '<=', $jamMulai)
                            ->where('jam_selesai', '>=', $jamSelesai);
                    });
            })
            ->exists();
    }

    /**
     * Kirim notifikasi ke admin tentang booking/pembayaran baru.
     */
    public static function notifyAdmins(
        string $title,
        string $message,
        array $data = []
    ): void {
        \App\Models\Notification::sendToAdmins('transaction', $title, $message, $data);
    }
}
