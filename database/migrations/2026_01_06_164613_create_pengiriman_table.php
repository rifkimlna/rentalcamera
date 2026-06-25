<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->unique()->constrained('transaksis')->cascadeOnDelete();
            $table->string('kurir', 100)->nullable();
            $table->string('no_resi', 100)->nullable();
            $table->enum('metode', ['pickup', 'delivery', 'express'])->default('delivery');
            $table->decimal('biaya', 12, 2)->default(0);
            $table->text('alamat_asal')->nullable();
            $table->text('alamat_tujuan')->nullable();
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'returned', 'cancelled'])->default('pending');
            $table->dateTime('estimated_delivery')->nullable();
            $table->dateTime('actual_delivery')->nullable();
            $table->json('tracking_data')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};