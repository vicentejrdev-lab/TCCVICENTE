<?php

namespace Database\Factories;

use App\Models\Consultant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Consultant>
 */
class ConsultantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'external_code' => 'DEMO-TEST-'.Str::upper(Str::random(8)),
            'full_name' => 'Consultor fictício '.fake()->unique()->numberBetween(1000, 9999),
            'profile' => 'consultant',
            'regional' => 'Regional Demonstração',
            'personal_production_count' => 1,
            'active' => true,
        ];
    }
}
