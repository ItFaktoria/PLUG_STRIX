<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Spingo\SpingoApi\Api\SpingoCartConfigProviderInterface;
use Spingo\SpingoCore\Service\SpingoPaymentGrandTotalThresholdService;

class SpingoPaymentGrandTotalThresholdServiceTest extends TestCase
{
    /**
     * @dataProvider testData
     */
    public function testIsAvailableByConfigurationThreshold(
        float $grandTotal,
        float $minOrderTotal,
        float $maxOrderTotal,
        bool $result
    ): void {
        $spingoCartConfigProviderInterface = $this->createMock(SpingoCartConfigProviderInterface::class);
        $spingoCartConfigProviderInterface->expects($this->once())->method('getMinOrderTotal')->willReturn(
            $minOrderTotal
        );
        $spingoCartConfigProviderInterface->expects($this->once())->method('getMaxOrderTotal')->willReturn(
            $maxOrderTotal
        );
        $spingoPaymentGrandTotalThresholdService = new SpingoPaymentGrandTotalThresholdService(
            $spingoCartConfigProviderInterface
        );
        $spingoResult = $spingoPaymentGrandTotalThresholdService->isAvailableByConfigurationThreshold($grandTotal, 1);
        $this->assertEquals($result, $spingoResult);
    }

    private function testData(): array
    {
        return [
            [
                555.00,
                500.00,
                30000.00,
                true
            ],
            [
                200.00,
                500.00,
                30000.00,
                false
            ],
            [
                30000.20,
                500.00,
                30000.00,
                false
            ]
        ];
    }
}
