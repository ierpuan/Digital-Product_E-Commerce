<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DownloadToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_item_id',
        'user_id',
        'token',
        'download_count',
        'max_downloads',
        'expired_at',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'max_downloads'  => 'integer',
        'expired_at'     => 'datetime',
        'created_at'     => 'datetime',
    ];

    // Cek apakah token masih bisa dipakai untuk download
    public function isUsable(): bool
    {
        if ($this->expired_at->isPast()) return false;
        if ($this->download_count >= $this->max_downloads) return false;

        return true;
    }

    // Catat satu download dan kembalikan path file
    public function consume(): string
    {
        $this->increment('download_count');

        return $this->orderItem->product->file_path;
    }

    // Sisa jumlah download
    public function remainingDownloads(): int
    {
        return max(0, $this->max_downloads - $this->download_count);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
