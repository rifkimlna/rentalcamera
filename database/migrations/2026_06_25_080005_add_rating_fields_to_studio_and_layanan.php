<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studio', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->default(0)->after('harga_per_jam');
            $table->integer('jumlah_ulasan')->default(0)->after('rating');
        });

        Schema::table('layanan', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->default(0)->after('harga_mulai');
            $table->integer('jumlah_ulasan')->default(0)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('studio', function (Blueprint $table) {
            $table->dropColumn(['rating', 'jumlah_ulasan']);
        });

        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn(['rating', 'jumlah_ulasan']);
        });
    }
};
