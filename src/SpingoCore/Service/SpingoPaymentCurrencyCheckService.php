<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Service;

use Spingo\SpingoApi\Api\SpingoPaymentCurrencyCheckServiceInterface;

class SpingoPaymentCurrencyCheckService implements SpingoPaymentCurrencyCheckServiceInterface
{
    public function isAvailableByCurrency(string $currencyCode): bool
    {
        return $currencyCode === 'PLN';
    }
}
