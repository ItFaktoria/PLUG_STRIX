<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Resolver;

use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoApi\Api\SpingoConnectionConfigProviderInterface;
use Spingo\SpingoCore\Resolver\SpingoSubmitParameterResolver;

class SpingoSubmitParameterResolverTest extends TestCase
{
    /**
     * @dataProvider testData
     */
    public function testResolve(
        string $reservedOrderId,
        float $grandTotalAmount,
        ?string $vatId,
        int $storeId,
        bool $isSendNotification,
        array $result
    ): void{
        $spingoConnectionConfigProvider = $this->createMock(SpingoConnectionConfigProviderInterface::class);
        $spingoConnectionConfigProvider->expects($this->once())->method('getContractId')->willReturn('123456');
        $spingoConnectionConfigProvider->expects($this->once())->method('getMerchantId')->willReturn('123456');
        $spingoConnectionConfigProvider->expects($this->exactly(2))->method('isSendNotification')->willReturn(
            $isSendNotification
        );
        $spingoConnectionConfigProvider->expects($this->once())->method('getReturnUrl');
        $spingoConnectionConfigProvider->expects($this->once())->method('getCancelUrl');
        $url = $this->createMock(UrlInterface::class);
        $url->expects($this->atMost(3))->method('getDirectUrl')->willReturn('test/url');
        $spingoSubmitParameterResolver = new SpingoSubmitParameterResolver($spingoConnectionConfigProvider, $url);
        $spingoResult = $spingoSubmitParameterResolver->resolve($reservedOrderId, $grandTotalAmount, $vatId, $storeId);
        $this->assertEquals($result, $spingoResult);
    }

    private function testData(): array
    {
        return [
            [
                '1',
                123.0,
                null,
                1,
                true,
                [
                    'toSet' => [
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.ID',
                            'value' => '1'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.AMMOUNT',
                            'value' => '123,00'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.CURRENCY',
                            'value' => 'PLN'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_LOG.CONTRACT_ID',
                            'value' => '123456'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_LOG.MERCHANT_ID',
                            'value' => '123456'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.URL_RETURN',
                            'value' => 'test/url'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.URL_CANCEL',
                            'value' => 'test/url'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.SEND_NOTIFY',
                            'value' => '1'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.URL_NOTIFY',
                            'value' => 'test/url'
                        ]
                    ],
                    'toGet' => [
                        'FKT_FPAY_IN_URL.URL_APPLICATION'
                    ]
                ]
            ],
            [
                '2',
                222.0,
                '123456789',
                1,
                false,
                [
                    'toSet' => [
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.ID',
                            'value' => '2'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.AMMOUNT',
                            'value' => '222,00'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.CURRENCY',
                            'value' => 'PLN'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_LOG.CONTRACT_ID',
                            'value' => '123456'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_LOG.MERCHANT_ID',
                            'value' => '123456'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.URL_RETURN',
                            'value' => 'test/url'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.URL_CANCEL',
                            'value' => 'test/url'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_URL.SEND_NOTIFY',
                            'value' => '0'
                        ],
                        [
                            'name' => 'FKT_FPAY_IN_TRANSACTION.BUYER_ID',
                            'value' => '123456789'
                        ]
                    ],
                    'toGet' => [
                        'FKT_FPAY_IN_URL.URL_APPLICATION'
                    ]
                ]
            ]
        ];
    }
}
