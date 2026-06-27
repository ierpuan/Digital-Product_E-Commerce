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
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('gateway', 50)->comment('midtrans, xendit, doku, dll');
            $table->string('transaction_id', 100)->nullable()->comment('ID transaksi dari payment gateway');
            $table->string('status', 30)->comment('Raw status dari webhook gateway');
            $table->decimal('amount', 12, 2);
            $table->json('payload')->nullable()->comment('Raw JSON webhook payload');
            $table->boolean('signature_valid')->nullable()
                  ->comment('Hasil validasi signature key dari gateway');
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
