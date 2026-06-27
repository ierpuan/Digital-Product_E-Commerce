<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_uses',
        'used_count',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_uses'     => 'integer',
        'used_count'   => 'integer',
        'is_active'    => 'boolean',
        'expired_at'   => 'datetime',
    ];

    // Cek apakah voucher masih bisa dipakai
    public function isUsable(): bool
    {
        if (!$this->is_active) return false;
        if ($this->expired_at && $this->expired_at->isPast()) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;

        return true;
    }

    // Hitung nominal diskon berdasarkan subtotal
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_purchase) return 0;

        if ($this->type === 'percent') {
            return round($subtotal * ($this->value / 100), 2);
        }

        return min((float) $this->value, $subtotal);
    }

    // Scope: voucher yang masih aktif dan belum expired
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('expired_at')
                           ->orWhere('expired_at', '>', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('max_uses')
                           ->orWhereColumn('used_count', '<', 'max_uses');
                     });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
