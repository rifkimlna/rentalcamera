<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop pengiriman table
        Schema::dropIfExists('pengiriman');

        // Remove shipping columns from transaksis (hanya jika kolomnya ada)
        $shippingColumns = [
            'alamat_pengiriman',
            'kota_pengiriman',
            'provinsi_pengiriman',
            'kode_pos_pengiriman',
            'biaya_pengiriman',
            'shipped_at',
        ];

        foreach ($shippingColumns as $column) {
            if (Schema::hasColumn('transaksis', $column)) {
                Schema::table('transaksis', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        // Rebuild status_transaksi enum without shipping values
        // (blok ALTER ... MODIFY hanya berlaku untuk MySQL)
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE transaksis MODIFY COLUMN status_transaksi ENUM('draft', 'menunggu_pembayaran', 'diproses', 'dikonfirmasi', 'siap_diambil', 'selesai', 'dibatalkan', 'ditolak') NOT NULL DEFAULT 'draft'");

            // Simplify metode_pengambilan and metode_pengembalian to string
            DB::statement("ALTER TABLE transaksis MODIFY COLUMN metode_pengambilan VARCHAR(20) NOT NULL DEFAULT 'pickup'");
            DB::statement("ALTER TABLE transaksis MODIFY COLUMN metode_pengembalian VARCHAR(20) NOT NULL DEFAULT 'return'");

            // Remove 'shipping' from vouchers type enum
            DB::statement("ALTER TABLE vouchers MODIFY COLUMN type ENUM('percentage', 'fixed') NOT NULL");

            // Remove 'shipping' from notifications type enum
            DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('transaction', 'payment', 'system', 'promotion') NOT NULL");
        }
    }

    public function down(): void
    {
        // Would require recreating pengiriman table and columns - not practical to reverse
    }
};
