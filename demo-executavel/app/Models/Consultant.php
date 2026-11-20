<?php

namespace App\Models;

use Database\Factories\ConsultantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id',
    'external_code',
    'full_name',
    'profile',
    'regional',
    'personal_production_count',
    'active',
])]
class Consultant extends Model
{
    /** @use HasFactory<ConsultantFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'personal_production_count' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function associates(): HasMany
    {
        return $this->hasMany(Associate::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    public function lotItems(): HasMany
    {
        return $this->hasMany(PaymentLotItem::class);
    }
}
