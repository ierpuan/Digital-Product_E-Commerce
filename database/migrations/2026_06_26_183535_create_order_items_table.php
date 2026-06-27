<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            // Snapshot data produk saat transaksi (harga/nama tidak berubah walau produk diedit)
            $table->string('product_name', 200)->comment('Snapshot nama produk saat beli');
            $table->decimal('price', 12, 2)->comment('Snapshot harga produk saat beli');
            $table->timestamp('created_at')->useCurrent();

            // Pastikan satu produk hanya muncul sekali per order
            $table->unique(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
