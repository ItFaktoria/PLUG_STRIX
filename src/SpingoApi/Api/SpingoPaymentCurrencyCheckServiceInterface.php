<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoPaymentCurrencyCheckServiceInterface
{
    public function isAvailableByCurrency(string $currencyCode): bool;
}
