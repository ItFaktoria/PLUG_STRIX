<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;

interface FaktoriaSubmitParameterResolverInterface
{
    public function resolve(string $reservedOrderId, float $grandTotalAmount, int $storeId): array;
}
