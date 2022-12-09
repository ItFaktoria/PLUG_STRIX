<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Service;

use Faktoria\FaktoriaApi\Api\FaktoriaPaymentCurrencyCheckServiceInterface;

class FaktoriaPaymentCurrencyCheckService implements FaktoriaPaymentCurrencyCheckServiceInterface
{
    public function isAvailableByCurrency(string $currencyCode): bool
    {
        return $currencyCode === 'PLN';
    }
}
