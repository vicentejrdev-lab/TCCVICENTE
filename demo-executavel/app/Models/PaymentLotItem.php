<?php

namespace App\Models;

use Database\Factories\PaymentLotItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'payment_lot_id',
    'consultant_id',
    'production_count',
    'gross_amount',
    'bonus_amount',
    'adjustment_amount',
    'official_amount',
    'status',
    'snapshot',
    'closed_at',
])]
class PaymentLotItem extends Model
{
    /** @use HasFactory<PaymentLotItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'production_count' => 'integer',
            'gross_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'adjustment_amount' => 'decimal:2',
            'official_amount' => 'decimal:2',
            'snapshot' => 'array',
            'closed_at' => 'datetime',
        ];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(PaymentLot::class, 'payment_lot_id');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}
