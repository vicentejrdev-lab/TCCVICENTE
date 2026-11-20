<?php

namespace App\Models;

use Database\Factories\PaymentLotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'uuid',
    'financial_period_id',
    'code',
    'status',
    'item_count',
    'production_count',
    'gross_amount',
    'bonus_amount',
    'adjustment_amount',
    'official_amount',
    'checksum',
    'lock_version',
    'closed_by_user_id',
    'closed_at',
    'close_reason',
])]
class PaymentLot extends Model
{
    /** @use HasFactory<PaymentLotFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'item_count' => 'integer',
            'production_count' => 'integer',
            'gross_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'adjustment_amount' => 'decimal:2',
            'official_amount' => 'decimal:2',
            'lock_version' => 'integer',
            'closed_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(FinancialPeriod::class, 'financial_period_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PaymentLotItem::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PaymentLotEvent::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_user_id');
    }
}
