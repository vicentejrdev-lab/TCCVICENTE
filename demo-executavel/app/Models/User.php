<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'document', 'role', 'active', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function closedLots(): HasMany
    {
        return $this->hasMany(PaymentLot::class, 'closed_by_user_id');
    }

    public function closedPeriods(): HasMany
    {
        return $this->hasMany(FinancialPeriod::class, 'closed_by_user_id');
    }

    public function paymentLotEvents(): HasMany
    {
        return $this->hasMany(PaymentLotEvent::class);
    }

    public function isMaster(): bool
    {
        return $this->active && $this->role === 'master';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'active' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
