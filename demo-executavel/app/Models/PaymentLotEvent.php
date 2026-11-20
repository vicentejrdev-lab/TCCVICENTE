<?php

namespace App\Models;

use Database\Factories\PaymentLotEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'payment_lot_id',
    'user_id',
    'event_type',
    'from_status',
    'to_status',
    'description',
    'context',
    'created_at',
])]
class PaymentLotEvent extends Model
{
    /** @use HasFactory<PaymentLotEventFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(PaymentLot::class, 'payment_lot_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
