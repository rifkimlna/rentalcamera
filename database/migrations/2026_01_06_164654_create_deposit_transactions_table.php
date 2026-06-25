<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->nullOnDelete();
            $table->string('kode_transaksi', 20)->unique();
            $table->enum('type', ['topup', 'withdraw', 'payment', 'refund', 'penalty', 'reward']);
            $table->decimal('amount', 12, 2);
            $table->decimal('previous_balance', 12, 2);
            $table->decimal('current_balance', 12, 2);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('midtrans_order_id', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'transaksi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_transactions');
    }
};