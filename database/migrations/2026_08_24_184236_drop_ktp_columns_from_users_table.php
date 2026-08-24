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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ktp_image')) {
                $table->dropColumn('ktp_image');
            }
            if (Schema::hasColumn('users', 'ktp_verified_at')) {
                $table->dropColumn('ktp_verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ktp_image', 255)->nullable();
            $table->timestamp('ktp_verified_at')->nullable();
        });
    }
};
