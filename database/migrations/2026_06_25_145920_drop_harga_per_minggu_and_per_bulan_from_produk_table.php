<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['harga_per_minggu', 'harga_per_bulan']);
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('harga_per_minggu', 15, 2)->nullable()->after('harga_per_hari');
            $table->decimal('harga_per_bulan', 15, 2)->nullable()->after('harga_per_minggu');
        });
    }
};
