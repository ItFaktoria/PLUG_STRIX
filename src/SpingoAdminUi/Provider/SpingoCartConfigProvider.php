<?php

declare(strict_types=1);

namespace Spingo\SpingoAdminUi\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Spingo\SpingoApi\Api\SpingoCartConfigProviderInterface;

class SpingoCartConfigProvider implements SpingoCartConfigProviderInterface
{
    private const MIN_ORDER_TOTAL = 'spingo_payment/cart/min_order_total';
    private const MAX_ORDER_TOTAL = 'spingo_payment/cart/max_order_total';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getMinOrderTotal(int $storeId): float
    {
        return (float)$this->scopeConfig->getValue(
            self::MIN_ORDER_TOTAL,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }
    
    public function getMaxOrderTotal(int $storeId): float
    {
        return (float)$this->scopeConfig->getValue(
            self::MAX_ORDER_TOTAL,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }
}
