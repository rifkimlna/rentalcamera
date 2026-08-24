<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropUnique('ulasan_transaksi_id_unique');
            $table->unique(['transaksi_id', 'produk_id'], 'ulasan_transaksi_produk_unique');
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropUnique('ulasan_transaksi_produk_unique');
            $table->foreignId('transaksi_id')->unique()->constrained('transaksis')->cascadeOnDelete();
        });
    }
};
