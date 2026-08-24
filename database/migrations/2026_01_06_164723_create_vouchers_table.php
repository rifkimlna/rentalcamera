<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_voucher', 50)->unique();
            $table->string('nama_voucher', 100);
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 12, 2);
            $table->decimal('min_purchase', 12, 2)->default(0);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->integer('kuota')->nullable();
            $table->integer('kuota_terpakai')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Untuk voucher khusus user');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_produk')->nullOnDelete()->comment('Untuk voucher khusus kategori');
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete()->comment('Untuk voucher khusus produk');
            $table->timestamps();
            
            $table->index(['user_id', 'kategori_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};