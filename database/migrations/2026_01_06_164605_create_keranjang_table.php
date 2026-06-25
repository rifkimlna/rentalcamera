<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('produk_id')
                ->constrained('produk')
                ->cascadeOnDelete();

            // Data
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_sewa');
            $table->date('tanggal_kembali');

            $table->integer('lama_sewa')
                ->storedAs("DATEDIFF(tanggal_kembali, tanggal_sewa) + 1");

            $table->timestamps();

            // Constraints & Index
            $table->unique([
                'user_id',
                'produk_id',
                'tanggal_sewa',
                'tanggal_kembali'
            ]);

            $table->index('produk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
