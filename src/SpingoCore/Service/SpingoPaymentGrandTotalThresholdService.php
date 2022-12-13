<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Service;

use Spingo\SpingoApi\Api\SpingoCartConfigProviderInterface;
use Spingo\SpingoApi\Api\SpingoPaymentGrandTotalThresholdServiceInterface;

class SpingoPaymentGrandTotalThresholdService implements SpingoPaymentGrandTotalThresholdServiceInterface
{
    /**
     * @var SpingoCartConfigProviderInterface
     */
    private $spingoCartConfigProvider;

    public function __construct(SpingoCartConfigProviderInterface $spingoCartConfigProvider)
    {
        $this->spingoCartConfigProvider = $spingoCartConfigProvider;
    }

    public function isAvailableByConfigurationThreshold(float $grandTotal, int $storeId): bool
    {
        $min = $this->spingoCartConfigProvider->getMinOrderTotal($storeId);
        $max = $this->spingoCartConfigProvider->getMaxOrderTotal($storeId);

        return $grandTotal >= $min && $grandTotal <= $max;
    }
}
