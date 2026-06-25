<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS before_transaction_insert");
        DB::unprepared("
        CREATE TRIGGER before_transaction_insert
        BEFORE INSERT ON transaksis
        FOR EACH ROW
        BEGIN
            IF NEW.kode_transaksi IS NULL THEN
                SET NEW.kode_transaksi = CONCAT('TRX', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(FLOOR(RAND() * 10000), 4, '0'));
            END IF;

            IF NEW.uuid IS NULL THEN
                SET NEW.uuid = UUID();
            END IF;
        END;
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS after_transaction_confirmed");
        DB::unprepared("
        CREATE TRIGGER after_transaction_confirmed
        AFTER UPDATE ON transaksis
        FOR EACH ROW
        BEGIN
            IF NEW.status_transaksi = 'dikonfirmasi' AND (OLD.status_transaksi IS NULL OR OLD.status_transaksi != 'dikonfirmasi') THEN
                UPDATE produk p
                INNER JOIN detail_transaksi dt ON p.id = dt.produk_id
                SET p.stok_dipinjam = p.stok_dipinjam + dt.jumlah,
                    p.stok_tersedia = p.stok_total - p.stok_dipinjam - p.stok_rusak
                WHERE dt.transaksi_id = NEW.id;
            END IF;

            IF NEW.status_transaksi = 'selesai' AND (OLD.status_transaksi IS NULL OR OLD.status_transaksi != 'selesai') THEN
                UPDATE produk p
                INNER JOIN detail_transaksi dt ON p.id = dt.produk_id
                SET p.stok_dipinjam = p.stok_dipinjam - dt.jumlah,
                    p.stok_tersedia = p.stok_total - p.stok_dipinjam - p.stok_rusak,
                    p.jumlah_dipesan = p.jumlah_dipesan + dt.jumlah
                WHERE dt.transaksi_id = NEW.id;
            END IF;

            IF NEW.status_transaksi = 'dibatalkan' AND (OLD.status_transaksi IS NULL OR OLD.status_transaksi != 'dibatalkan') THEN
                UPDATE produk p
                INNER JOIN detail_transaksi dt ON p.id = dt.produk_id
                SET p.stok_dipinjam = p.stok_dipinjam - dt.jumlah,
                    p.stok_tersedia = p.stok_total - p.stok_dipinjam - p.stok_rusak
                WHERE dt.transaksi_id = NEW.id;
            END IF;
        END;
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS before_deposit_transaction_insert");
        DB::unprepared("
        CREATE TRIGGER before_deposit_transaction_insert
        BEFORE INSERT ON deposit_transactions
        FOR EACH ROW
        BEGIN
            IF NEW.kode_transaksi IS NULL THEN
                SET NEW.kode_transaksi = CONCAT('DEP', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(FLOOR(RAND() * 10000), 4, '0'));
            END IF;
        END;
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS after_review_approved");
        DB::unprepared("
        CREATE TRIGGER after_review_approved
        AFTER UPDATE ON ulasan
        FOR EACH ROW
        BEGIN
            IF NEW.status = 'approved' AND (OLD.status IS NULL OR OLD.status != 'approved') THEN
                UPDATE produk
                SET rating = (
                        SELECT COALESCE(AVG(rating), 0)
                        FROM ulasan
                        WHERE produk_id = NEW.produk_id
                          AND status = 'approved'
                    ),
                    jumlah_ulasan = (
                        SELECT COUNT(*)
                        FROM ulasan
                        WHERE produk_id = NEW.produk_id
                          AND status = 'approved'
                    )
                WHERE id = NEW.produk_id;
            END IF;
        END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS before_transaction_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_transaction_confirmed");
        DB::unprepared("DROP TRIGGER IF EXISTS before_deposit_transaction_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS after_review_approved");
    }
};
