<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->string('invoice_no', 30)->unique()->comment('Format: INV-YYYYMMDD-XXXX');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')
                  ->comment('State machine: pending → paid | failed | refunded');
            $table->string('payment_method', 50)->nullable()->comment('bank_transfer, qris, dll');
            $table->string('payment_channel', 50)->nullable()->comment('bca, bni, gopay, dll');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable()->comment('Batas waktu pembayaran');
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
