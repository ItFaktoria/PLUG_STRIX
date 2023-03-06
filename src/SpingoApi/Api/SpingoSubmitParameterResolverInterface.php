<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoSubmitParameterResolverInterface
{
    public function resolve(string $reservedOrderId, float $grandTotalAmount, ?string $vatId, int $storeId): array;
}
