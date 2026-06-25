<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_transaksi', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_customer', 100);
            $table->string('telepon_customer', 20);
            $table->string('email_customer', 100);
            $table->text('alamat_pengiriman')->nullable();
            $table->string('kota_pengiriman', 100)->nullable();
            $table->string('provinsi_pengiriman', 100)->nullable();
            $table->string('kode_pos_pengiriman', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->string('kode_voucher', 50)->nullable();
            $table->decimal('biaya_pengiriman', 12, 2)->default(0);
            $table->decimal('biaya_asuransi', 12, 2)->default(0);
            $table->decimal('biaya_lainnya', 12, 2)->default(0);
            $table->decimal('total_sewa', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('admin_fee', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_type', 50)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->string('payment_code', 100)->nullable();
            $table->string('pdf_url', 500)->nullable();
            $table->string('midtrans_order_id', 100)->nullable()->unique();
            $table->string('midtrans_token', 255)->nullable();
            $table->string('midtrans_redirect_url', 500)->nullable();
            $table->enum('status_pembayaran', ['pending', 'capture', 'settlement', 'deny', 'cancel', 'expire', 'failure', 'refund', 'partial_refund', 'chargeback'])->default('pending');
            $table->enum('status_transaksi', ['draft', 'menunggu_pembayaran', 'diproses', 'dikonfirmasi', 'dikemas', 'dikirim', 'dalam_perjalanan', 'selesai', 'dibatalkan', 'ditolak'])->default('draft');
            $table->enum('status_deposit', ['pending', 'dibayar', 'dikembalikan', 'dipotong'])->default('pending');
            $table->dateTime('tanggal_pengambilan')->nullable();
            $table->dateTime('tanggal_pengembalian')->nullable();
            $table->integer('lama_sewa');
            $table->enum('metode_pengambilan', ['pickup', 'delivery', 'both'])->default('pickup');
            $table->enum('metode_pengembalian', ['return', 'pickup', 'both'])->default('return');
            $table->text('catatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->dateTime('payment_expired_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->dateTime('refunded_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status_pembayaran');
            $table->index('status_transaksi');
            $table->index('payment_method_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};