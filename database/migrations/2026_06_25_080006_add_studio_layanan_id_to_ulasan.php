<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->foreignId('produk_id')->nullable()->change();
            $table->foreignId('studio_id')->nullable()->after('produk_id')->constrained('studio')->cascadeOnDelete();
            $table->foreignId('layanan_id')->nullable()->after('studio_id')->constrained('layanan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->dropForeign(['studio_id']);
            $table->dropForeign(['layanan_id']);
            $table->dropColumn(['studio_id', 'layanan_id']);
            $table->foreignId('produk_id')->nullable(false)->change();
        });
    }
};
