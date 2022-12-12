<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Resolver;

use Faktoria\FaktoriaApi\Api\FaktoriaConnectionConfigProviderInterface;
use Faktoria\FaktoriaApi\Api\FaktoriaSubmitParameterResolverInterface;
use Magento\Framework\UrlInterface;

class FaktoriaSubmitParameterResolver implements FaktoriaSubmitParameterResolverInterface
{
    /**
     * @var FaktoriaConnectionConfigProviderInterface
     */
    private $faktoriaConnectionConfigProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        FaktoriaConnectionConfigProviderInterface $faktoriaConnectionConfigProvider,
        UrlInterface $urlBuilder
    ) {
        $this->faktoriaConnectionConfigProvider = $faktoriaConnectionConfigProvider;
        $this->urlBuilder = $urlBuilder;
    }

    public function resolve(string $reservedOrderId, float $grandTotalAmount, ?string $vatId, int $storeId): array
    {
        $toSet = [
            [
                'name' => 'FKT_FPAY_IN_TRANSACTION.ID',
                'value' => $reservedOrderId
            ],
            [
                'name' => 'FKT_FPAY_IN_TRANSACTION.AMMOUNT',
                'value' => number_format($grandTotalAmount, 2, ',', '')
            ],
            [
                'name' => 'FKT_FPAY_IN_TRANSACTION.CURRENCY',
                'value' => 'PLN'
            ],
            [
                'name' => 'FKT_FPAY_IN_LOG.CONTRACT_ID',
                'value' => $this->faktoriaConnectionConfigProvider->getContractId($storeId)
            ],
            [
                'name' => 'FKT_FPAY_IN_LOG.MERCHANT_ID',
                'value' => $this->faktoriaConnectionConfigProvider->getMerchantId($storeId)
            ],
            [
                'name' => 'FKT_FPAY_IN_URL.URL_RETURN',
                'value' => $this->urlBuilder->getUrl($this->faktoriaConnectionConfigProvider->getReturnUrl($storeId)),
            ],
            [
                'name' => 'FKT_FPAY_IN_URL.URL_CANCEL',
                'value' => $this->urlBuilder->getUrl($this->faktoriaConnectionConfigProvider->getCancelUrl($storeId)),
            ],
            [
                'name' => 'FKT_FPAY_IN_URL.SEND_NOTIFY',
                'value' => $this->faktoriaConnectionConfigProvider->isSendNotification($storeId) ? '1' : '0'
            ]
        ];
        $toGet = [
            'FKT_FPAY_IN_URL.URL_APPLICATION'
        ];
        if (!empty($vatId)) {
            $toSet[] = [
                'name' => 'FKT_FPAY_IN_TRANSACTION.BUYER_ID',
                'value' => $vatId
            ];
        }
        if ($this->faktoriaConnectionConfigProvider->isSendNotification($storeId)) {
            $toSet[] = [
                'name' => 'FKT_FPAY_IN_URL.URL_NOTIFY',
                'value' => $this->urlBuilder->getUrl('rest/V1/faktoria/notify')
            ];
        }

        return ['toSet' => $toSet, 'toGet' => $toGet];
    }
}
