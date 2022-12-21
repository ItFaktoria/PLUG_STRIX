<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Provider;

use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoCore\Provider\SpingoNotifyOrderStatusProvider;

class SpingoNotifyOrderStatusProviderTest extends TestCase
{
    /**
     * @dataProvider testData
     */
    public function testProvide(string $code, ?string $status): void
    {
        $spingoNotifyOrderStatusProvider = new SpingoNotifyOrderStatusProvider();
        $result = $spingoNotifyOrderStatusProvider->provide($code);
        $this->assertEquals($status, $result);
    }

    private function testData(): array
    {
        return [
            [
                '200',
                Order::STATE_PROCESSING
            ],
            [
                '400',
                Order::STATE_CANCELED
            ],
            [
                '401',
                Order::STATE_CANCELED
            ],
            [
                '501',
                Order::STATE_CANCELED
            ],
            [
                '502',
                Order::STATE_CANCELED
            ],
            [
                'undefined',
                null
            ]
        ];
    }
}
