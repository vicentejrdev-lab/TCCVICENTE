    public function closeLot(PaymentLot $lot, User $actor, string $reason): PaymentLot
    {
        $this->assertPermission($actor, 'financeiro.lotes.aprovar');
        $this->assertPermission($actor, 'financeiro.fechamento.executar');
        $this->assertClosureWindowOpen($lot->period);
        $reason = $this->requiredReason($reason, 15);
        $this->readiness->evaluateAndPersist($lot->period, $actor);
        $this->readiness->assertReady($lot->period);

        return DB::transaction(function () use ($lot, $actor, $reason): PaymentLot {
            $lot = PaymentLot::query()->lockForUpdate()->findOrFail($lot->getKey());
            $this->assertClosureWindowOpen($lot->period);
            if (! in_array($lot->status, ['draft', 'partial'], true)) {
                throw new DomainException('Somente lote em preparação ou parcialmente fechado pode ser oficializado.');
            }
            $this->assertIndependentActor($lot, $actor);
            $this->assertLotUsesLatestRuns($lot);
            $this->assertLotIntegrity($lot);
            $closedAt = now();
            PaymentLotItem::query()
                ->where('payment_lot_id', $lot->getKey())
                ->where('status', 'draft')
                ->update(['status' => 'closed', 'closed_at' => $closedAt, 'updated_at' => $closedAt]);
            $approvalChecksum = $this->approvalChecksum($lot);
            $before = ['status' => $lot->status, 'closed_items' => $lot->items()->where('status', 'closed')->count()];
            $lot->forceFill([
                'status' => 'closed',
                'approved_by_user_id' => $actor->getKey(),
                'approved_at' => $closedAt,
                'approval_checksum' => $approvalChecksum,
                'closed_by_user_id' => $actor->getKey(),
                'closed_at' => $closedAt,
                'sent_by_user_id' => null,
                'sent_at' => null,
                'reason' => $reason,
                'lock_version' => $lot->lock_version + 1,
            ])->save();
            FinancialPeriod::query()->whereKey($lot->financial_period_id)->update([
                'status' => 'closed',
                'source_cutoff_at' => $closedAt,
                'updated_at' => $closedAt,
            ]);
            $after = ['status' => 'closed', 'closed_items' => $lot->item_count, 'closed_at' => $closedAt->toIso8601String()];
            $this->event($lot, null, $lot->calculation_run_id, 'lot.closed', $actor, $reason, $before, $after);
            $this->ledger->append('financial.payment_lot.closed', PaymentLot::class, $lot->getKey(), [
                'lot_uuid' => $lot->uuid,
                'official_total' => (string) $lot->official_total,
                'report_checksum' => $lot->report_checksum,
                'approval_checksum' => $approvalChecksum,
                'meaning' => 'frozen_for_review',
                'reason' => $reason,
            ], $actor, $lot->financial_period_id, $lot->uuid);

            return $lot->fresh(['items']);
        }, 3);
    }
