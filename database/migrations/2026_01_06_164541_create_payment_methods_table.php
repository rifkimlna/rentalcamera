<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->enum('type', ['bank_transfer', 'ewallet', 'qris', 'credit_card', 'cstore', 'cod', 'deposit']);
            $table->string('bank_code', 20)->nullable();
            $table->decimal('fee_percentage', 5, 2)->default(0);
            $table->decimal('fee_flat', 12, 2)->default(0);
            $table->decimal('minimum_amount', 12, 2)->default(0);
            $table->decimal('maximum_amount', 12, 2)->default(999999999.99);
            $table->boolean('is_active')->default(true);
            $table->string('icon', 255)->nullable();
            $table->text('instructions')->nullable();
            $table->string('midtrans_payment_type', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};