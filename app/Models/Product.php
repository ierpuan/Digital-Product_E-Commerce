<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'thumbnail',
        'file_path',
        'file_size',
        'file_type',
        'is_active',
        'total_sales',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'is_active'   => 'boolean',
        'total_sales' => 'integer',
        'file_size'   => 'integer',
    ];

    // Auto-generate slug dari name
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Scope untuk produk yang aktif saja
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor: harga format Rupiah
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Accessor: ukuran file yang readable
    public function getFileSizeLabelAttribute(): string
    {
        if (!$this->file_size) return '-';
        return $this->file_size >= 1024
            ? round($this->file_size / 1024, 1) . ' MB'
            : $this->file_size . ' KB';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
