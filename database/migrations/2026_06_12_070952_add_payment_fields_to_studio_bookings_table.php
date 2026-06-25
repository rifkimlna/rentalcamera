<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->string('payment_status', 30)->default('pending')->after('catatan');
            $table->string('midtrans_order_id', 100)->nullable()->after('payment_status');
            $table->string('midtrans_token', 255)->nullable()->after('midtrans_order_id');
            $table->string('midtrans_redirect_url', 255)->nullable()->after('midtrans_token');
            $table->timestamp('paid_at')->nullable()->after('midtrans_redirect_url');
        });
    }

    public function down(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'midtrans_order_id', 'midtrans_token', 'midtrans_redirect_url', 'paid_at']);
        });
    }
};
