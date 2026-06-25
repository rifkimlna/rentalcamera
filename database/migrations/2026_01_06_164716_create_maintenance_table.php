<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('serial_number', 100)->nullable();
            $table->enum('jenis_maintenance', ['routine', 'repair', 'cleaning', 'calibration']);
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya', 12, 2)->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('teknisi', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index('produk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};