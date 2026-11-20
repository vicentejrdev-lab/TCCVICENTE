<?php

namespace Database\Factories;

use App\Models\FinancialPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinancialPeriod>
 */
class FinancialPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => sprintf('2099-%02d', fake()->unique()->numberBetween(1, 12)),
            'label' => 'Competência fictícia',
            'starts_on' => '2099-11-01',
            'ends_on' => '2099-11-30',
            'status' => 'open',
            'closed_by_user_id' => null,
            'closed_at' => null,
        ];
    }
}
