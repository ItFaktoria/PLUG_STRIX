<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Provider;

use Magento\Sales\Model\Order;
use Spingo\SpingoApi\Api\SpingoNotifyOrderStatusProviderInterface;

class SpingoNotifyOrderStatusProvider implements SpingoNotifyOrderStatusProviderInterface
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
                return Order::STATE_CANCELED;
            default:
                return null;
        }
    }
}
