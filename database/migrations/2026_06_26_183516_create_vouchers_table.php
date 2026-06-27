<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->enum('type', ['percent', 'fixed'])->comment('percent = %, fixed = nominal Rupiah');
            $table->decimal('value', 10, 2)->comment('Nilai diskon (% atau Rupiah)');
            $table->decimal('min_purchase', 12, 2)->default(0)->comment('Minimum pembelian');
            $table->unsignedInteger('max_uses')->nullable()->comment('NULL = tidak terbatas');
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
