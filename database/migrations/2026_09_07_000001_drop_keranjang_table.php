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
        // Hapus tabel keranjang yang sudah tidak dipakai
        // (alur cart sudah dihapus, checkout langsung tanpa keranjang).
        Schema::dropIfExists('keranjang');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('keranjang')) {
            return;
        }

        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_sewa');
            $table->date('tanggal_kembali');
            $table->timestamps();

            $table->unique(['user_id', 'produk_id', 'tanggal_sewa', 'tanggal_kembali']);
            $table->index('produk_id');
        });
    }
};
