<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanan_bookings', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('total_harga')->constrained('payment_methods')->nullOnDelete();
            $table->decimal('admin_fee', 12, 2)->default(0)->after('payment_method_id');
            $table->decimal('grand_total', 12, 2)->after('admin_fee');
        });
    }

    public function down(): void
    {
        Schema::table('layanan_bookings', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'admin_fee', 'grand_total']);
        });
    }
};
