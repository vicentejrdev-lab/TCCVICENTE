<?php

namespace App\Console\Commands;

use App\Models\Associate;
use App\Models\Consultant;
use App\Models\Production;
use App\Models\UnpaidBill;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('demo:reset {--force : Restaura sem solicitar confirmação}')]
#[Description('Restaura os dados fictícios do demonstrador acadêmico')]
class ResetDemoScenario extends Command
{
    public function handle(DemoScenarioSeeder $seeder): int
    {
        if (! config('demo.enabled') || ! app()->environment(['local', 'testing'])) {
            $this->error('A restauração é permitida somente no modo demonstrativo local ou de testes.');

            return self::FAILURE;
        }

        if (! $this->option('force') && ! app()->runningUnitTests() && ! $this->confirm('Restaurar todo o cenário fictício?')) {
            $this->warn('Restauração cancelada.');

            return self::SUCCESS;
        }

        $seeder->run();

        $this->info('Cenário fictício restaurado.');
        $this->table(
            ['Entidade', 'Quantidade'],
            [
                ['Consultores', Consultant::query()->count()],
                ['Produções', Production::query()->count()],
                ['Associados', Associate::query()->count()],
                ['Boletos pendentes', UnpaidBill::query()->count()],
            ],
        );

        return self::SUCCESS;
    }
}
