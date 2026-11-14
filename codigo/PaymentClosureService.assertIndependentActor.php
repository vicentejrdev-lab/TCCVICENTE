    private function assertIndependentActor(PaymentLot $lot, User $actor): void
    {
        if ($this->approvalPolicy->requiresIndependentApproval() && $lot->prepared_by_user_id === $actor->getKey()) {
            throw new DomainException('Com a confirmação dupla ativa, outra conta Master deve fechar o lote ou o consultor.');
        }
    }
