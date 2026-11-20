<?php

namespace Database\Factories;

use App\Models\Associate;
use App\Models\Consultant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Associate>
 */
class AssociateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'consultant_id' => Consultant::factory(),
            'external_code' => 'DEMO-ASSOC-'.Str::upper(Str::random(8)),
            'full_name' => 'Associado fictício '.fake()->unique()->numberBetween(1000, 9999),
            'status' => 'regular',
        ];
    }
}
