<?php

namespace App\Services;

use App\Exceptions\PaymentLotAlreadyClosed;
use App\Models\PaymentLot;
use App\Models\User;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use JsonException;

class PaymentClosureService
{
    /**
     * @throws AuthorizationException
     * @throws JsonException
     */
    public function close(PaymentLot $lot, User $actor, string $reason): PaymentLot
    {
        if (! $actor->isMaster()) {
            throw new AuthorizationException('Somente um usuário Master ativo pode fechar o lote.');
        }

        return DB::transaction(function () use ($lot, $actor, $reason): PaymentLot {
            $lockedLot = PaymentLot::query()
                ->with('period')
                ->lockForUpdate()
                ->findOrFail($lot->getKey());

            if ($lockedLot->status !== 'open') {
                throw new PaymentLotAlreadyClosed;
            }

            $items = $lockedLot->items()
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw new DomainException('O lote não possui itens para fechamento.');
            }

            $grossCents = 0;
            $bonusCents = 0;
            $adjustmentCents = 0;
            $officialCents = 0;
            $checksumItems = [];

            foreach ($items as $item) {
                $itemGrossCents = $this->toCents($item->gross_amount);
                $itemBonusCents = $this->toCents($item->bonus_amount);
                $itemAdjustmentCents = $this->toCents($item->adjustment_amount);
                $itemOfficialCents = $this->toCents($item->official_amount);

                if ($itemOfficialCents !== $itemGrossCents + $itemBonusCents + $itemAdjustmentCents) {
                    throw new DomainException("O item {$item->getKey()} possui valores inconsistentes.");
                }

                $grossCents += $itemGrossCents;
                $bonusCents += $itemBonusCents;
                $adjustmentCents += $itemAdjustmentCents;
                $officialCents += $itemOfficialCents;
                $checksumItems[] = [
                    'id' => $item->getKey(),
                    'consultant_id' => $item->consultant_id,
                    'production_count' => $item->production_count,
                    'gross_cents' => $itemGrossCents,
                    'bonus_cents' => $itemBonusCents,
                    'adjustment_cents' => $itemAdjustmentCents,
                    'official_cents' => $itemOfficialCents,
                ];
            }

            $closedAt = now();
            $checksum = hash('sha256', json_encode($checksumItems, JSON_THROW_ON_ERROR));

            $lockedLot->items()->update([
                'status' => 'closed',
                'closed_at' => $closedAt,
                'updated_at' => $closedAt,
            ]);

            $lockedLot->update([
                'status' => 'closed',
                'item_count' => $items->count(),
                'production_count' => $items->sum('production_count'),
                'gross_amount' => $this->fromCents($grossCents),
                'bonus_amount' => $this->fromCents($bonusCents),
                'adjustment_amount' => $this->fromCents($adjustmentCents),
                'official_amount' => $this->fromCents($officialCents),
                'checksum' => $checksum,
                'lock_version' => $lockedLot->lock_version + 1,
                'closed_by_user_id' => $actor->getKey(),
                'closed_at' => $closedAt,
                'close_reason' => trim($reason),
            ]);

            $lockedLot->events()->create([
                'user_id' => $actor->getKey(),
                'event_type' => 'lot_closed',
                'from_status' => 'open',
                'to_status' => 'closed',
                'description' => 'Lote financeiro fechado no ambiente acadêmico.',
                'context' => [
                    'checksum' => $checksum,
                    'item_count' => $items->count(),
                    'production_count' => $items->sum('production_count'),
                    'official_amount' => $this->fromCents($officialCents),
                ],
                'created_at' => $closedAt,
            ]);

            $period = $lockedLot->period()->lockForUpdate()->firstOrFail();
            $hasOpenLots = $period->lots()->where('status', 'open')->exists();

            if (! $hasOpenLots) {
                $period->update([
                    'status' => 'closed',
                    'closed_by_user_id' => $actor->getKey(),
                    'closed_at' => $closedAt,
                ]);
            }

            return $lockedLot->fresh([
                'closedBy',
                'events.user',
                'items.consultant',
                'period',
            ]);
        }, attempts: 3);
    }

    private function toCents(int|float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }

    private function fromCents(int $amount): string
    {
        return number_format($amount / 100, 2, '.', '');
    }
}
