<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();
            $table->string('kode_produk', 50);
            $table->string('nama_produk', 200);
            $table->decimal('harga_per_hari', 12, 2);
            $table->integer('jumlah')->default(1);
            $table->integer('lama_sewa');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index(['transaksi_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};