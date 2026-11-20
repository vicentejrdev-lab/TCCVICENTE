<?php

namespace App\Models;

use Database\Factories\UnpaidBillFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'associate_id',
    'consultant_id',
    'reference',
    'due_date',
    'amount',
    'status',
    'has_pdf',
])]
class UnpaidBill extends Model
{
    /** @use HasFactory<UnpaidBillFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:2',
            'has_pdf' => 'boolean',
        ];
    }

    public function associate(): BelongsTo
    {
        return $this->belongsTo(Associate::class);
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}
