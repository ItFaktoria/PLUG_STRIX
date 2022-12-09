<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaSubmitParameterResolverInterface
{
    public function resolve(string $reservedOrderId, float $grandTotalAmount, ?string $vatId, int $storeId): array;
}
