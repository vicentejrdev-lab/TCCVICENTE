<?php

namespace Tests\Feature;

use App\Exceptions\PaymentLotAlreadyClosed;
use App\Models\FinancialPeriod;
use App\Models\PaymentLot;
use App\Models\PaymentLotEvent;
use App\Models\PaymentLotItem;
use App\Models\User;
use App\Services\PaymentClosureService;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentClosureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DemoScenarioSeeder::class);
    }

    public function test_master_closes_lot_preserving_values_and_audit_trail(): void
    {
        $master = User::query()->sole();
        $lot = PaymentLot::query()->with('items')->sole();
        $expectedAmount = round((float) $lot->items->sum('official_amount'), 2);

        $closedLot = app(PaymentClosureService::class)->close(
            $lot,
            $master,
            'Fechamento realizado durante a demonstração acadêmica.',
        );

        $closedLot->refresh();

        $this->assertSame('closed', $closedLot->status);
        $this->assertSame($master->getKey(), $closedLot->closed_by_user_id);
        $this->assertNotNull($closedLot->closed_at);
        $this->assertSame(2, $closedLot->lock_version);
        $this->assertSame($expectedAmount, round((float) $closedLot->official_amount, 2));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', (string) $closedLot->checksum);
        $this->assertSame(20, PaymentLotItem::query()->where('status', 'closed')->count());
        $this->assertSame(1, PaymentLotEvent::query()->where('event_type', 'lot_closed')->count());
        $this->assertSame('closed', FinancialPeriod::query()->sole()->status);

        $this->assertDatabaseHas('payment_lot_events', [
            'payment_lot_id' => $closedLot->getKey(),
            'user_id' => $master->getKey(),
            'from_status' => 'open',
            'to_status' => 'closed',
        ]);
    }

    public function test_closed_lot_cannot_be_closed_again(): void
    {
        $master = User::query()->sole();
        $service = app(PaymentClosureService::class);
        $lot = PaymentLot::query()->sole();

        $service->close($lot, $master, 'Primeiro fechamento acadêmico do lote.');

        $version = $lot->fresh()->lock_version;
        $events = PaymentLotEvent::query()->count();
        $amount = $lot->fresh()->official_amount;

        try {
            $service->close($lot, $master, 'Tentativa repetida de fechamento acadêmico.');
            $this->fail('Era esperada a recusa do segundo fechamento.');
        } catch (PaymentLotAlreadyClosed) {
            $this->assertSame($version, $lot->fresh()->lock_version);
            $this->assertSame($events, PaymentLotEvent::query()->count());
            $this->assertSame($amount, $lot->fresh()->official_amount);
        }
    }
}
