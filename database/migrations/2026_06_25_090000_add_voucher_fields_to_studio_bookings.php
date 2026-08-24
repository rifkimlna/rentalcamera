<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->string('kode_voucher')->nullable()->after('catatan');
            $table->decimal('diskon_voucher', 12, 2)->default(0)->after('kode_voucher');
        });
    }

    public function down(): void
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->dropColumn(['kode_voucher', 'diskon_voucher']);
        });
    }
};
