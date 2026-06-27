<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('download_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 64)->unique()->comment('UUID atau hash acak yang aman');
            $table->unsignedSmallInteger('download_count')->default(0);
            $table->unsignedSmallInteger('max_downloads')->default(5)
                  ->comment('Maksimal jumlah download diizinkan');
            $table->timestamp('expired_at')->comment('Token kedaluwarsa setelah N hari');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_tokens');
    }
};
