<?php

namespace App\Models;

use Database\Factories\ProductionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'consultant_id',
    'external_code',
    'contract_code',
    'competence',
    'gross_amount',
    'bonus_amount',
    'status',
])]
class Production extends Model
{
    /** @use HasFactory<ProductionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
        ];
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}
