<?php

namespace App\Exceptions;

use DomainException;

class PaymentLotAlreadyClosed extends DomainException
{
    public function __construct()
    {
        parent::__construct('Este lote já foi fechado e não pode ser processado novamente.');
    }
}
