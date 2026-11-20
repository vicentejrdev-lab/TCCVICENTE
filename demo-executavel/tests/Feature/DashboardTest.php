<?php

namespace Tests\Feature;

use App\Models\UnpaidBill;
use App\Models\User;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_can_view_operational_dashboard(): void
    {
        $this->seed(DemoScenarioSeeder::class);
        $master = User::query()->sole();

        $response = $this->actingAs($master)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard');
        $response->assertSee('20');
        $response->assertSee('170');
        $response->assertSee('300');
        $response->assertSee('45');
    }

    public function test_navigation_presents_operational_statuses_in_portuguese(): void
    {
        $this->seed(DemoScenarioSeeder::class);
        $master = User::query()->sole();

        $this->actingAs($master)
            ->get(route('productions.index'))
            ->assertOk()
            ->assertSee('Elegível')
            ->assertDontSee('Eligible');

        $billWithoutPdf = UnpaidBill::query()->where('has_pdf', false)->firstOrFail();

        $this->actingAs($master)
            ->get(route('unpaid-bills.index', ['q' => $billWithoutPdf->reference, 'status' => '']))
            ->assertOk()
            ->assertSee('Sem PDF');
    }
}
