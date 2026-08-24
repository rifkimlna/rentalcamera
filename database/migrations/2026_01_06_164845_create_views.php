<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // View berisi sintaks khusus MySQL (CURDATE, GROUP_CONCAT ... SEPARATOR)
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared("
            CREATE OR REPLACE VIEW v_dashboard_summary AS
            SELECT 
                (SELECT COUNT(*) FROM users WHERE role = 'customer') AS total_customers,
                (SELECT COUNT(*) FROM produk) AS total_products,
                (SELECT COUNT(*) FROM transaksis WHERE DATE(created_at) = CURDATE()) AS today_transactions,
                (SELECT COALESCE(SUM(grand_total), 0) FROM transaksis WHERE DATE(created_at) = CURDATE() AND status_pembayaran = 'settlement') AS today_revenue,
                (SELECT COUNT(*) FROM transaksis WHERE status_pembayaran = 'pending') AS pending_payments,

                (SELECT COUNT(*) FROM produk WHERE stok_tersedia = 0) AS out_of_stock,
        ");

        DB::unprepared("
            CREATE OR REPLACE VIEW v_transaction_report AS
            SELECT 
                t.id,
                t.kode_transaksi,
                t.nama_customer,
                t.telepon_customer,
                t.grand_total,
                t.status_pembayaran,
                t.status_transaksi,
                pm.name AS payment_method,
                t.paid_at,
                t.created_at,
                GROUP_CONCAT(p.nama_produk SEPARATOR ', ') AS products
            FROM transaksis t
            LEFT JOIN payment_methods pm ON t.payment_method_id = pm.id
            LEFT JOIN detail_transaksi dt ON t.id = dt.transaksi_id
            LEFT JOIN produk p ON dt.produk_id = p.id
            GROUP BY 
                t.id, t.kode_transaksi, t.nama_customer, t.telepon_customer,
                t.grand_total, t.status_pembayaran, t.status_transaksi,
                pm.name, t.paid_at, t.created_at
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS v_dashboard_summary");
        DB::unprepared("DROP VIEW IF EXISTS v_transaction_report");
    }
};
