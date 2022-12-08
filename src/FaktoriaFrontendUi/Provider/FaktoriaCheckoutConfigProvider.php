<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaFrontendUi\Provider;

use Faktoria\FaktoriaApi\Api\FaktoriaConnectionConfigProviderInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class FaktoriaCheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var FaktoriaConnectionConfigProviderInterface
     */
    private $faktoriaConnectionConfigProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        FaktoriaConnectionConfigProviderInterface $faktoriaConnectionConfigProvider,
        StoreManagerInterface $storeManager
    ) {
        $this->faktoriaConnectionConfigProvider = $faktoriaConnectionConfigProvider;
        $this->storeManager = $storeManager;
    }

    public function getConfig(): array
    {
        try {
            $isActive = $this->faktoriaConnectionConfigProvider->isActive(
                (int)$this->storeManager->getStore()->getId()
            );
        } catch (NoSuchEntityException $e) {
            $isActive = false;
        }

        return [
            'payment' => [
                'faktoriaPayment' => [
                    'isActive' => $isActive
                ]
            ]
        ];
    }
}
