<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Provider;

use Faktoria\FaktoriaApi\Api\FaktoriaNotifyOrderStatusProviderInterface;
use Magento\Sales\Model\Order;

class FaktoriaNotifyOrderStatusProvider implements FaktoriaNotifyOrderStatusProviderInterface
{
    public function provide(string $statusCode): ?string
    {
        switch ($statusCode) {
            case '200':
                return Order::STATE_PROCESSING;
            case '400':
            case '401':
            case '501':
            case '502':
                return Order::STATE_CLOSED;
            default:
                return null;
        }
    }
}
