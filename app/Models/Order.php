<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_PAID     = 'paid';
    const STATUS_FAILED   = 'failed';
    const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'voucher_id',
        'invoice_no',
        'subtotal',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_channel',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'subtotal'   => 'decimal:2',
        'discount'   => 'decimal:2',
        'total'      => 'decimal:2',
        'paid_at'    => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Auto-generate invoice_no & expired_at saat order dibuat
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->invoice_no = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $order->expired_at = now()->addHours(24); // Batas bayar 24 jam
        });

        // Setelah status berubah jadi paid, buat download token otomatis
        static::updated(function (Order $order) {
            if ($order->isDirty('status') && $order->status === self::STATUS_PAID) {
                foreach ($order->orderItems as $item) {
                    DownloadToken::create([
                        'order_item_id' => $item->id,
                        'user_id'       => $order->user_id,
                        'token'         => Str::random(64),
                        'max_downloads' => 5,
                        'expired_at'    => now()->addDays(30),
                    ]);
                }

                // Tambah total_sales di setiap produk
                foreach ($order->orderItems as $item) {
                    $item->product()->increment('total_sales');
                }

                // Tambah used_count voucher jika ada
                if ($order->voucher_id) {
                    $order->voucher()->increment('used_count');
                }
            }
        });
    }

    // Helper: cek apakah order sudah dibayar
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    // Helper: cek apakah order sudah kedaluwarsa
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_PENDING
            && $this->expired_at
            && $this->expired_at->isPast();
    }

    // Accessor: format total Rupiah
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }
}
