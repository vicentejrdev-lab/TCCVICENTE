<?php

namespace Tests\Feature;

use App\Models\Associate;
use App\Models\Consultant;
use App\Models\FinancialPeriod;
use App\Models\PaymentLot;
use App\Models\PaymentLotEvent;
use App\Models\PaymentLotItem;
use App\Models\Production;
use App\Models\UnpaidBill;
use App\Models\User;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoScenarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_recreates_expected_training_volumes(): void
    {
        $this->seed(DemoScenarioSeeder::class);

        $this->assertSame(1, User::query()->where('role', 'master')->where('active', true)->count());
        $this->assertSame(20, Consultant::query()->count());
        $this->assertSame(170, Production::query()->count());
        $this->assertSame(300, Associate::query()->count());
        $this->assertSame(225, Associate::query()->where('status', 'regular')->count());
        $this->assertSame(45, Associate::query()->where('status', 'delinquent')->count());
        $this->assertSame(30, Associate::query()->where('status', 'inactive')->count());
        $this->assertSame(45, UnpaidBill::query()->count());
        $this->assertSame(40, UnpaidBill::query()->where('has_pdf', true)->count());
        $this->assertSame(5, UnpaidBill::query()->where('has_pdf', false)->count());
        $this->assertSame(1, FinancialPeriod::query()->where('status', 'open')->count());
        $this->assertSame(1, PaymentLot::query()->where('status', 'open')->count());
        $this->assertSame(20, PaymentLotItem::query()->where('status', 'open')->count());
        $this->assertSame(170, (int) PaymentLotItem::query()->sum('production_count'));
        $this->assertSame(0, PaymentLotEvent::query()->count());
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(DemoScenarioSeeder::class);
        $this->seed(DemoScenarioSeeder::class);

        $this->assertSame(20, Consultant::query()->count());
        $this->assertSame(170, Production::query()->count());
        $this->assertSame(300, Associate::query()->count());
        $this->assertSame(45, UnpaidBill::query()->count());
        $this->assertSame(20, PaymentLotItem::query()->count());
    }
}
