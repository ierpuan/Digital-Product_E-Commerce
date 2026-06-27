<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'gateway',
        'transaction_id',
        'status',
        'amount',
        'payload',
        'signature_valid',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'payload'         => 'array',   // Auto encode/decode JSON
        'signature_valid' => 'boolean',
        'created_at'      => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
