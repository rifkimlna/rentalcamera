<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->unique()->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->integer('rating');
            $table->string('judul', 200)->nullable();
            $table->text('komentar')->nullable();
            $table->json('foto_ulasan')->nullable();
            $table->text('balasan')->nullable();
            $table->timestamp('balasan_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            $table->index(['user_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};