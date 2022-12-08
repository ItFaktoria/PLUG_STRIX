<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Service;

use Faktoria\FaktoriaApi\Api\FaktoriaCartConfigProviderInterface;
use Faktoria\FaktoriaApi\Api\FaktoriaPaymentGrandTotalThresholdServiceInterface;

class FaktoriaPaymentGrandTotalThresholdService implements FaktoriaPaymentGrandTotalThresholdServiceInterface
{
    /**
     * @var FaktoriaCartConfigProviderInterface
     */
    private $faktoriaCartConfigProvider;

    public function __construct(FaktoriaCartConfigProviderInterface $faktoriaCartConfigProvider)
    {
        $this->faktoriaCartConfigProvider = $faktoriaCartConfigProvider;
    }

    public function isAvailableByConfigurationThreshold(float $grandTotal, int $storeId): bool
    {
        $min = $this->faktoriaCartConfigProvider->getMinOrderTotal($storeId);
        $max = $this->faktoriaCartConfigProvider->getMaxOrderTotal($storeId);

        return $grandTotal >= $min && $grandTotal <= $max;
    }
}
