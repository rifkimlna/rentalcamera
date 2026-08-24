<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'saldo_deposit')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('saldo_deposit');
            });
        }

        foreach (['deposit_amount', 'status_deposit'] as $col) {
            if (Schema::hasColumn('transaksis', $col)) {
                Schema::table('transaksis', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }

        if (Schema::hasColumn('detail_transaksi', 'deposit_amount')) {
            Schema::table('detail_transaksi', function (Blueprint $table) {
                $table->dropColumn('deposit_amount');
            });
        }

        Schema::dropIfExists('deposit_transactions');

        DB::table('payment_methods')->where('type', 'deposit')->delete();

        // Sintaks ALTER ... MODIFY hanya ada di MySQL
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE payment_methods MODIFY COLUMN type ENUM('bank_transfer','ewallet','qris','credit_card','cstore','cod') NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('saldo_deposit', 12, 2)->default(0);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->enum('status_deposit', ['pending', 'dibayar', 'dikembalikan', 'dipotong'])->default('pending');
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->decimal('deposit_amount', 12, 2)->default(0);
        });

        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaksi_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kode_transaksi', 30)->unique();
            $table->enum('type', ['topup', 'withdraw', 'payment', 'refund', 'penalty', 'reward']);
            $table->decimal('amount', 12, 2);
            $table->decimal('previous_balance', 12, 2)->default(0);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('midtrans_order_id', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
        });
    }
};
