<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studio', function (Blueprint $table) {
            $table->id();
            $table->string('nama_studio', 200);
            $table->string('slug', 200)->unique();
            $table->text('deskripsi')->nullable();
            $table->text('fasilitas')->nullable();
            $table->decimal('harga_per_jam', 12, 2);
            $table->string('gambar_utama', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studio');
    }
};
