<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->timestamp('payment_expired_at')->nullable()->after('paid_at');
        });
        Schema::table('layanan_bookings', function (Blueprint $table) {
            $table->timestamp('payment_expired_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->dropColumn('payment_expired_at');
        });
        Schema::table('layanan_bookings', function (Blueprint $table) {
            $table->dropColumn('payment_expired_at');
        });
    }
};
