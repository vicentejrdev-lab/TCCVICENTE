<?php

namespace Database\Factories;

use App\Models\Consultant;
use App\Models\Production;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Production>
 */
class ProductionFactory extends Factory
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
            'external_code' => 'DEMO-VEH-'.Str::upper(Str::random(8)),
            'contract_code' => 'DEMO-CTR-'.Str::upper(Str::random(8)),
            'competence' => '2026-11',
            'gross_amount' => 150.00,
            'bonus_amount' => 15.00,
            'status' => 'eligible',
        ];
    }
}
