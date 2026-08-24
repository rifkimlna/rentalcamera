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
        // Defense-in-depth anti double-booking: kombinasi slot yang identik
        // tidak boleh ada dua baris (overlap rentang tetap dicek di aplikasi).
        if (Schema::hasTable('studio_bookings')) {
            Schema::table('studio_bookings', function (Blueprint $table) {
                $table->unique(['studio_id', 'tanggal_booking', 'jam_mulai'], 'studio_bookings_slot_unique');
            });
        }

        if (Schema::hasTable('layanan_bookings')) {
            Schema::table('layanan_bookings', function (Blueprint $table) {
                $table->unique(['layanan_id', 'tanggal_booking', 'jam_mulai'], 'layanan_bookings_slot_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('studio_bookings')) {
            Schema::table('studio_bookings', function (Blueprint $table) {
                $table->dropUnique('studio_bookings_slot_unique');
            });
        }

        if (Schema::hasTable('layanan_bookings')) {
            Schema::table('layanan_bookings', function (Blueprint $table) {
                $table->dropUnique('layanan_bookings_slot_unique');
            });
        }
    }
};
