<?php

namespace Tests\Feature;

use App\Models\PaymentLot;
use App\Models\Production;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ResetDemoScenarioCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_command_restores_deterministic_scenario(): void
    {
        $this->seed(DemoScenarioSeeder::class);

        Production::query()->firstOrFail()->delete();
        PaymentLot::query()->sole()->update(['status' => 'closed']);

        $this->artisan('demo:reset', ['--force' => true])
            ->assertSuccessful();

        $this->assertSame(170, Production::query()->count());
        $this->assertSame('open', PaymentLot::query()->sole()->status);
    }

    public function test_reset_command_refuses_when_demo_mode_is_disabled(): void
    {
        Config::set('demo.enabled', false);

        $this->artisan('demo:reset', ['--force' => true])
            ->assertFailed();
    }
}
