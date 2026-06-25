<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete();
            $table->string('order_id', 100);
            $table->string('transaction_id', 100)->nullable();
            $table->string('transaction_status', 50);
            $table->string('payment_type', 50)->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->string('fraud_status', 50)->nullable();
            $table->string('status_code', 10)->nullable();
            $table->text('status_message')->nullable();
            $table->text('signature_key')->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->string('bill_key', 100)->nullable();
            $table->string('biller_code', 50)->nullable();
            $table->string('payment_code', 100)->nullable();
            $table->string('store', 100)->nullable();
            $table->string('merchant_id', 100)->nullable();
            $table->string('masked_card', 100)->nullable();
            $table->string('card_type', 50)->nullable();
            $table->string('approval_code', 100)->nullable();
            $table->string('channel_response_code', 50)->nullable();
            $table->string('channel_response_message', 255)->nullable();
            $table->string('currency', 10)->default('IDR');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('transaksi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};