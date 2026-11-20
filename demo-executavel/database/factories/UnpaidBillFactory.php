<?php

namespace Database\Factories;

use App\Models\Associate;
use App\Models\Consultant;
use App\Models\UnpaidBill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<UnpaidBill>
 */
class UnpaidBillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'associate_id' => Associate::factory(),
            'consultant_id' => Consultant::factory(),
            'reference' => 'DEMO-BILL-'.Str::upper(Str::random(8)),
            'due_date' => '2026-10-15',
            'amount' => 120.00,
            'status' => 'pending',
            'has_pdf' => true,
        ];
    }
}
