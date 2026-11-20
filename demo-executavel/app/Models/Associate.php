<?php

namespace App\Models;

use Database\Factories\AssociateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['consultant_id', 'external_code', 'full_name', 'status'])]
class Associate extends Model
{
    /** @use HasFactory<AssociateFactory> */
    use HasFactory;

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function unpaidBills(): HasMany
    {
        return $this->hasMany(UnpaidBill::class);
    }
}
