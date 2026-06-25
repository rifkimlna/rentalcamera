<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket_studio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained('studio')->cascadeOnDelete();
            $table->string('nama_paket', 200);
            $table->string('slug', 200);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('durasi_jam');
            $table->text('include_alat')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['studio_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket_studio');
    }
};
