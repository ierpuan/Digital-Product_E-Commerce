<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned()->comment('Nilai 1 sampai 5 bintang');
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false)->comment('Moderasi admin sebelum tampil');
            $table->timestamp('created_at')->useCurrent();

            // Satu user hanya bisa review satu produk satu kali per pembelian
            $table->unique(['user_id', 'order_item_id']);
            $table->index(['product_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
