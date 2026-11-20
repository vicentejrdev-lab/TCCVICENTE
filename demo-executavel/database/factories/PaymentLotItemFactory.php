<?php

namespace Database\Factories;

use App\Models\Consultant;
use App\Models\PaymentLot;
use App\Models\PaymentLotItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentLotItem>
 */
class PaymentLotItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_lot_id' => PaymentLot::factory(),
            'consultant_id' => Consultant::factory(),
            'production_count' => 1,
            'gross_amount' => 150.00,
            'bonus_amount' => 15.00,
            'adjustment_amount' => 0,
            'official_amount' => 165.00,
            'status' => 'open',
            'snapshot' => ['source' => 'factory'],
            'closed_at' => null,
        ];
    }
}
