<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Spingo\SpingoCore\Service\SpingoPaymentCurrencyCheckService;

class SpingoPaymentCurrencyCheckServiceTest extends TestCase
{
    /**
     * @dataProvider testData
     */
    public function testIsAvailableByCurrency(string $currencyCode, bool $result): void
    {
        $spingoPaymentCurrencyCheckService = new SpingoPaymentCurrencyCheckService();
        $spingoResult = $spingoPaymentCurrencyCheckService->isAvailableByCurrency($currencyCode);
        $this->assertEquals($result, $spingoResult);
    }

    private function testData(): array
    {
        return [
            [
                'PLN',
                true
            ],
            [
                'EUR',
                false
            ]
        ];
    }
}
