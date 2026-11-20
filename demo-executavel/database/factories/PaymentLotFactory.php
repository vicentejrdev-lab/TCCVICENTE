<?php

namespace Database\Factories;

use App\Models\FinancialPeriod;
use App\Models\PaymentLot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PaymentLot>
 */
class PaymentLotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'financial_period_id' => FinancialPeriod::factory(),
            'code' => 'DEMO-LOT-'.Str::upper(Str::random(8)),
            'status' => 'open',
            'item_count' => 0,
            'production_count' => 0,
            'gross_amount' => 0,
            'bonus_amount' => 0,
            'adjustment_amount' => 0,
            'official_amount' => 0,
            'checksum' => null,
            'lock_version' => 1,
            'closed_by_user_id' => null,
            'closed_at' => null,
            'close_reason' => null,
        ];
    }
}
