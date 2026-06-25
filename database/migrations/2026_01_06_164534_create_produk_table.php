<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_produk')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('kode_produk', 50)->unique();
            $table->string('nama_produk', 200);
            $table->string('slug', 200)->unique();
            $table->text('deskripsi_singkat')->nullable();
            $table->text('deskripsi_lengkap')->nullable();
            $table->json('spesifikasi')->nullable();
            $table->text('fitur')->nullable();
            $table->decimal('harga_per_hari', 12, 2);
            $table->decimal('harga_per_minggu', 12, 2)->nullable();
            $table->decimal('harga_per_bulan', 12, 2)->nullable();
            $table->integer('stok_total')->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->integer('stok_rusak')->default(0);
            $table->integer('stok_dipinjam')->default(0);
            $table->integer('minimum_sewa')->default(1)->comment('Minimum hari sewa');
            $table->integer('maximum_sewa')->default(30)->comment('Maximum hari sewa');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('jumlah_ulasan')->default(0);
            $table->integer('jumlah_dipesan')->default(0);
            $table->string('gambar_utama', 255)->nullable();
            $table->json('gambar_tambahan')->nullable();
            $table->enum('status', ['available', 'unavailable', 'maintenance'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->decimal('berat', 6, 2)->nullable()->comment('Dalam gram');
            $table->string('dimensi', 100)->nullable()->comment('Panjang x Lebar x Tinggi (cm)');
            $table->string('serial_number', 100)->nullable();
            $table->year('tahun_pembuatan')->nullable();
            $table->enum('kondisi', ['baru', 'bekas_excellent', 'bekas_good', 'bekas_fair'])->default('baru');
            $table->timestamps();
            
            $table->index(['kategori_id', 'brand_id']);
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_recommended');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};