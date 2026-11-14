# Recortes dos testes correspondentes

Arquivo de origem: `tests/Feature/Database/CoreOneSprint22PaymentClosureTest.php`.

## Fechamento, envio e confirmação bancária

Linhas 261–295 da origem:

```php
    public function test_closure_payment_submission_and_bank_confirmation_are_three_separate_steps(): void
    {
        [$actor, $period] = $this->scenario();
        app(FinancialApprovalPolicyService::class)->update($actor, false, 'Teste de fechamento por uma conta Master.');
        $service = app(PaymentClosureService::class);
        $lot = $service->prepare($period, $actor, 'Preparação completa para envio do relatório oficial.')['lot'];

        $closed = $service->closeLot($lot, $actor, 'Conferência encerrada para congelar os valores oficiais.');
        $this->assertSame('closed', $closed->status);
        $this->assertNull($closed->sent_at);
        $this->assertSame('closed', $period->fresh()->status);
        $this->assertSame(4, $closed->items()->where('status', 'closed')->count());

        try {
            $service->confirmBank($closed, $actor, 'BANCO-2026-07-001', 'Crédito ainda não poderia ser confirmado.');
            $this->fail('A confirmação bancária deveria exigir o envio para pagamento.');
        } catch (DomainException $exception) {
            $this->assertStringContainsString('Envie a competência para pagamento', $exception->getMessage());
        }

        $sent = $service->sendForPayment($closed, $actor, 'Conferência concluída e relatório oficialmente enviado ao pagamento.');
        $this->assertNotNull($sent->sent_at);
        $this->assertSame('closed', $sent->status);
        $this->assertSame('closed', $period->fresh()->status);

        $paid = $service->confirmBank($sent, $actor, 'BANCO-2026-07-001', 'Crédito confirmado pelo banco responsável.');
        $this->assertSame('paid', $paid->status);
        $this->assertSame('paid', $period->fresh()->status);
        $this->assertNotNull($paid->bank_confirmation_checksum);
        $this->assertSame(4, $paid->items()->where('status', 'paid')->count());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('sem confirmação bancária');
        $service->reopen($paid, null, $actor, 'Tentativa que deve ser bloqueada após confirmação bancária.');
    }
```

## Confirmação dupla

Linhas 388–406 da origem:

```php
    public function test_dual_approval_requires_another_master_to_close(): void
    {
        [$preparer, $period] = $this->scenario();
        $approver = $this->userWithRole('administrador_master');
        $service = app(PaymentClosureService::class);
        $lot = $service->prepare($period, $preparer, 'Preparação sujeita à confirmação dupla configurada.')['lot'];

        try {
            $service->closeLot($lot, $preparer, 'O próprio preparador não deve concluir este lote.');
            $this->fail('A confirmação dupla deveria bloquear o próprio preparador.');
        } catch (DomainException $exception) {
            $this->assertStringContainsString('outra conta Master', $exception->getMessage());
        }

        $closed = $service->closeLot($lot->fresh(), $approver, 'Segunda conta Master conferiu e enviou o lote.');
        $this->assertSame('closed', $closed->status);
        $this->assertSame($preparer->getKey(), $closed->prepared_by_user_id);
        $this->assertSame($approver->getKey(), $closed->approved_by_user_id);
    }
```
