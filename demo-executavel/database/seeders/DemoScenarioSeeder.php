<?php

namespace Database\Seeders;

use App\Models\Associate;
use App\Models\Consultant;
use App\Models\FinancialPeriod;
use App\Models\PaymentLot;
use App\Models\PaymentLotEvent;
use App\Models\PaymentLotItem;
use App\Models\Production;
use App\Models\UnpaidBill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoScenarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->clearScenario();

            User::query()->forceCreate([
                'name' => (string) config('demo.master.name'),
                'email' => (string) config('demo.master.email'),
                'document' => (string) config('demo.master.document'),
                'role' => 'master',
                'active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make((string) config('demo.master.password')),
            ]);

            $consultants = $this->createConsultants();
            $this->createProductions($consultants);
            $delinquentAssociates = $this->createAssociates($consultants);
            $this->createUnpaidBills($delinquentAssociates);
            $this->createPaymentScenario($consultants);
        });
    }

    private function clearScenario(): void
    {
        PaymentLotEvent::query()->delete();
        PaymentLotItem::query()->delete();
        PaymentLot::query()->delete();
        UnpaidBill::query()->delete();
        Production::query()->delete();
        Associate::query()->delete();
        Consultant::query()->delete();
        FinancialPeriod::query()->delete();

        if (Schema::hasTable('sessions')) {
            DB::table('sessions')->delete();
        }

        User::query()->delete();
    }

    /**
     * @return Collection<string, Consultant>
     */
    private function createConsultants(): Collection
    {
        $definitions = collect([
            ['DEMO-CONSULTOR', 'Alex Monteiro', null, 'consultant', 12],
            ['DEMO-LIDER', 'Alex Monteiro', null, 'leader', 8],
            ['DEMO-L-C01', 'Bianca Aurora', 'DEMO-LIDER', 'leader', 10],
            ['DEMO-L-C02', 'Caio Vereda', 'DEMO-LIDER', 'leader', 10],
            ['DEMO-L-C03', 'Diana Horizonte', 'DEMO-LIDER', 'leader', 10],
            ['DEMO-GESTOR', 'Alex Monteiro', null, 'manager', 8],
            ['DEMO-G-M01', 'Eduardo Nascente', 'DEMO-GESTOR', 'manager', 6],
            ['DEMO-G-M01-C01', 'Fernanda Vale', 'DEMO-G-M01', 'manager', 10],
            ['DEMO-G-M01-C02', 'Gabriel Prado', 'DEMO-G-M01', 'manager', 9],
            ['DEMO-G-M01-C03', 'Helena Brisa', 'DEMO-G-M01', 'manager', 8],
            ['DEMO-G-M01-C04', 'Igor Campelo', 'DEMO-G-M01', 'manager', 8],
            ['DEMO-G-M01-C05', 'Júlia Serena', 'DEMO-G-M01', 'manager', 9],
            ['DEMO-G-L01', 'Leonardo Campos', 'DEMO-GESTOR', 'manager', 5],
            ['DEMO-G-L01-C01', 'Marina Sol', 'DEMO-G-L01', 'manager', 10],
            ['DEMO-G-L01-C02', 'Nicolas Ribeiro', 'DEMO-G-L01', 'manager', 10],
            ['DEMO-G-L01-C03', 'Olívia Jardim', 'DEMO-G-L01', 'manager', 5],
            ['DEMO-G-C01', 'Paulo Estrela', 'DEMO-GESTOR', 'manager', 9],
            ['DEMO-G-C02', 'Renata Lago', 'DEMO-GESTOR', 'manager', 8],
            ['DEMO-G-C03', 'Samuel Rocha', 'DEMO-GESTOR', 'manager', 8],
            ['DEMO-G-C04', 'Talita Flores', 'DEMO-GESTOR', 'manager', 7],
        ]);

        $consultants = $definitions->mapWithKeys(function (array $definition): array {
            [$code, $name, $parentCode, $profile, $productionCount] = $definition;

            $consultant = Consultant::query()->create([
                'external_code' => $code,
                'full_name' => $name,
                'profile' => $profile,
                'regional' => 'Regional Demonstração',
                'personal_production_count' => $productionCount,
                'active' => true,
            ]);

            return [$code => $consultant];
        });

        $definitions->each(function (array $definition) use ($consultants): void {
            [$code, , $parentCode] = $definition;

            if ($parentCode !== null) {
                $consultants->get($code)?->update([
                    'parent_id' => $consultants->get($parentCode)?->getKey(),
                ]);
            }
        });

        return $consultants;
    }

    /**
     * @param  Collection<string, Consultant>  $consultants
     */
    private function createProductions(Collection $consultants): void
    {
        $productionNumber = 1;

        foreach ($consultants as $consultant) {
            for ($index = 0; $index < $consultant->personal_production_count; $index++) {
                $grossAmount = 110 + (($productionNumber * 13) % 90);
                $bonusRate = match ($consultant->profile) {
                    'manager' => 0.16,
                    'leader' => 0.12,
                    default => 0.08,
                };

                Production::query()->create([
                    'consultant_id' => $consultant->getKey(),
                    'external_code' => sprintf('DEMO-VEH-%04d', $productionNumber),
                    'contract_code' => sprintf('DEMO-CTR-%04d', $productionNumber),
                    'competence' => (string) config('demo.reference'),
                    'gross_amount' => $grossAmount,
                    'bonus_amount' => round($grossAmount * $bonusRate, 2),
                    'status' => 'eligible',
                ]);

                $productionNumber++;
            }
        }
    }

    /**
     * @param  Collection<string, Consultant>  $consultants
     * @return Collection<int, Associate>
     */
    private function createAssociates(Collection $consultants): Collection
    {
        $firstNames = [
            'Adriana', 'Afonso', 'Aline', 'André', 'Beatriz', 'Bruno', 'Camila', 'César', 'Clarice', 'Daniel',
            'Débora', 'Diego', 'Elisa', 'Fábio', 'Flávia', 'Gustavo', 'Isabela', 'João', 'Larissa', 'Lucas',
            'Márcia', 'Mateus', 'Natália', 'Otávio', 'Patrícia', 'Rafael', 'Sabrina', 'Tiago', 'Valéria', 'Vitor',
        ];
        $lastNames = ['Aurora', 'Bosque', 'Campos', 'Dourado', 'Estrela', 'Flores', 'Horizonte', 'Jardim', 'Lago', 'Monte'];
        $consultantList = $consultants->values();
        $delinquent = collect();

        for ($index = 0; $index < 300; $index++) {
            $status = match (true) {
                $index < 225 => 'regular',
                $index < 270 => 'delinquent',
                default => 'inactive',
            };
            $consultant = $consultantList->get($index % $consultantList->count());
            $associate = Associate::query()->create([
                'consultant_id' => $consultant->getKey(),
                'external_code' => sprintf('DEMO-ASSOC-%04d', $index + 1),
                'full_name' => $firstNames[$index % count($firstNames)].' '.$lastNames[intdiv($index, count($firstNames))],
                'status' => $status,
            ]);

            if ($status === 'delinquent') {
                $delinquent->push($associate);
            }
        }

        return $delinquent;
    }

    /**
     * @param  Collection<int, Associate>  $associates
     */
    private function createUnpaidBills(Collection $associates): void
    {
        $associates->values()->each(function (Associate $associate, int $index): void {
            UnpaidBill::query()->create([
                'associate_id' => $associate->getKey(),
                'consultant_id' => $associate->consultant_id,
                'reference' => sprintf('DEMO-BILL-%04d', $index + 1),
                'due_date' => now()->setDate(2026, 10, 5)->addDays($index % 20)->toDateString(),
                'amount' => 95 + (($index + 1) * 3.50),
                'status' => 'pending',
                'has_pdf' => $index < 40,
            ]);
        });
    }

    /**
     * @param  Collection<string, Consultant>  $consultants
     */
    private function createPaymentScenario(Collection $consultants): void
    {
        $period = FinancialPeriod::query()->create([
            'reference' => (string) config('demo.reference'),
            'label' => 'Novembro de 2026',
            'starts_on' => '2026-11-01',
            'ends_on' => '2026-11-30',
            'status' => 'open',
        ]);

        $lot = PaymentLot::query()->create([
            'uuid' => '11111111-1111-4111-8111-111111111111',
            'financial_period_id' => $period->getKey(),
            'code' => 'LOTE-DEMO-2026-11',
            'status' => 'open',
            'lock_version' => 1,
        ]);

        $totals = [
            'production_count' => 0,
            'gross_amount' => 0.0,
            'bonus_amount' => 0.0,
            'adjustment_amount' => 0.0,
            'official_amount' => 0.0,
        ];

        $consultants->values()->each(function (Consultant $consultant, int $index) use ($lot, &$totals): void {
            $productions = $consultant->productions()->orderBy('external_code')->get();
            $grossAmount = round((float) $productions->sum('gross_amount'), 2);
            $bonusAmount = round((float) $productions->sum('bonus_amount'), 2);
            $adjustmentAmount = (($index % 5) - 2) * 2.50;
            $officialAmount = round($grossAmount + $bonusAmount + $adjustmentAmount, 2);

            PaymentLotItem::query()->create([
                'payment_lot_id' => $lot->getKey(),
                'consultant_id' => $consultant->getKey(),
                'production_count' => $productions->count(),
                'gross_amount' => $grossAmount,
                'bonus_amount' => $bonusAmount,
                'adjustment_amount' => $adjustmentAmount,
                'official_amount' => $officialAmount,
                'status' => 'open',
                'snapshot' => [
                    'consultant_code' => $consultant->external_code,
                    'consultant_name' => $consultant->full_name,
                    'profile' => $consultant->profile,
                    'regional' => $consultant->regional,
                ],
            ]);

            $totals['production_count'] += $productions->count();
            $totals['gross_amount'] += $grossAmount;
            $totals['bonus_amount'] += $bonusAmount;
            $totals['adjustment_amount'] += $adjustmentAmount;
            $totals['official_amount'] += $officialAmount;
        });

        $lot->update([
            'item_count' => $consultants->count(),
            'production_count' => $totals['production_count'],
            'gross_amount' => round($totals['gross_amount'], 2),
            'bonus_amount' => round($totals['bonus_amount'], 2),
            'adjustment_amount' => round($totals['adjustment_amount'], 2),
            'official_amount' => round($totals['official_amount'], 2),
        ]);
    }
}
