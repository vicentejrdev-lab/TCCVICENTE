<?php

namespace App\Models;

use Database\Factories\FinancialPeriodFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'reference',
    'label',
    'starts_on',
    'ends_on',
    'status',
    'closed_by_user_id',
    'closed_at',
])]
class FinancialPeriod extends Model
{
    /** @use HasFactory<FinancialPeriodFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function lots(): HasMany
    {
        return $this->hasMany(PaymentLot::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_user_id');
    }
}
