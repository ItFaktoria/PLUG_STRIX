<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaPaymentCurrencyCheckServiceInterface
{
    public function isAvailableByCurrency(string $currencyCode): bool;
}
