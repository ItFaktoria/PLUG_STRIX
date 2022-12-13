<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Resolver;

use Magento\Framework\UrlInterface;
use Spingo\SpingoApi\Api\SpingoConnectionConfigProviderInterface;
use Spingo\SpingoApi\Api\SpingoSubmitParameterResolverInterface;

class SpingoSubmitParameterResolver implements SpingoSubmitParameterResolverInterface
{
    /**
     * @var SpingoConnectionConfigProviderInterface
     */
    private $spingoConnectionConfigProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        SpingoConnectionConfigProviderInterface $spingoConnectionConfigProvider,
        UrlInterface $urlBuilder
    ) {
        $this->spingoConnectionConfigProvider = $spingoConnectionConfigProvider;
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
                'value' => $this->spingoConnectionConfigProvider->getContractId($storeId)
            ],
            [
                'name' => 'FKT_FPAY_IN_LOG.MERCHANT_ID',
                'value' => $this->spingoConnectionConfigProvider->getMerchantId($storeId)
            ],
            [
                'name' => 'FKT_FPAY_IN_URL.URL_RETURN',
                'value' => $this->urlBuilder->getUrl($this->spingoConnectionConfigProvider->getReturnUrl($storeId)),
            ],
            [
                'name' => 'FKT_FPAY_IN_URL.URL_CANCEL',
                'value' => $this->urlBuilder->getUrl($this->spingoConnectionConfigProvider->getCancelUrl($storeId)),
            ],
            [
                'name' => 'FKT_FPAY_IN_URL.SEND_NOTIFY',
                'value' => $this->spingoConnectionConfigProvider->isSendNotification($storeId) ? '1' : '0'
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
        if ($this->spingoConnectionConfigProvider->isSendNotification($storeId)) {
            $toSet[] = [
                'name' => 'FKT_FPAY_IN_URL.URL_NOTIFY',
                'value' => $this->urlBuilder->getUrl('rest/V1/spingo/notify')
            ];
        }

        return ['toSet' => $toSet, 'toGet' => $toGet];
    }
}
