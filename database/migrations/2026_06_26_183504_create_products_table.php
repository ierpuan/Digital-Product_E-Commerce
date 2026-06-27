<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('thumbnail', 255)->nullable();
            $table->string('file_path', 255)->comment('Path file produk digital di storage');
            $table->unsignedInteger('file_size')->nullable()->comment('Ukuran file dalam KB');
            $table->string('file_type', 20)->nullable()->comment('pdf, zip, mp4, dll');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('total_sales')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
