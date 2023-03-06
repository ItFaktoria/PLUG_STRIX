<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoPaymentGrandTotalThresholdServiceInterface
{
    public function isAvailableByConfigurationThreshold(float $grandTotal, int $storeId): bool;
}
