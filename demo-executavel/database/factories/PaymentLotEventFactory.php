<?php

namespace Database\Factories;

use App\Models\PaymentLot;
use App\Models\PaymentLotEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentLotEvent>
 */
class PaymentLotEventFactory extends Factory
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
            'user_id' => User::factory(),
            'event_type' => 'lot_closed',
            'from_status' => 'open',
            'to_status' => 'closed',
            'description' => 'Evento fictício de teste.',
            'context' => ['source' => 'factory'],
            'created_at' => now(),
        ];
    }
}
