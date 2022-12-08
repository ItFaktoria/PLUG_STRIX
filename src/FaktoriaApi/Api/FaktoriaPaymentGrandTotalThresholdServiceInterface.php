<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaPaymentGrandTotalThresholdServiceInterface
{
    public function isAvailableByConfigurationThreshold(float $grandTotal, int $storeId): bool;
}
