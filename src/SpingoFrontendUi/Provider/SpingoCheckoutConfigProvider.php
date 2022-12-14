<?php

declare(strict_types=1);

namespace Spingo\SpingoFrontendUi\Provider;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;
use Spingo\SpingoApi\Api\SpingoConnectionConfigProviderInterface;

class SpingoCheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var SpingoConnectionConfigProviderInterface
     */
    private $spingoConnectionConfigProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Repository
     */
    private $assetRepository;

    public function __construct(
        SpingoConnectionConfigProviderInterface $spingoConnectionConfigProvider,
        StoreManagerInterface $storeManager,
        Repository $assetRepository
    ) {
        $this->spingoConnectionConfigProvider = $spingoConnectionConfigProvider;
        $this->storeManager = $storeManager;
        $this->assetRepository = $assetRepository;
    }

    public function getConfig(): array
    {
        try {
            $isActive = $this->spingoConnectionConfigProvider->isActive(
                (int)$this->storeManager->getStore()->getId()
            );
        } catch (NoSuchEntityException $e) {
            $isActive = false;
        }

        return [
            'payment' => [
                'spingoPayment' => [
                    'isActive' => $isActive,
                    'logoSrc' => $this->assetRepository->getUrl('Spingo_SpingoFrontendUi::images/logo_spingo.png')
                ]
            ]
        ];
    }
}
