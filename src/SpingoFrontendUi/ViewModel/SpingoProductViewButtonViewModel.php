<?php

declare(strict_types=1);

namespace Spingo\SpingoFrontendUi\ViewModel;

use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Spingo\SpingoApi\Api\SpingoImageConfigInterface;
use Spingo\SpingoApi\Api\SpingoProductConfigProviderInterface;

/**
 * @api
 */
class SpingoProductViewButtonViewModel implements ArgumentInterface
{
    /**
     * @var Repository
     */
    private $assetRepository;

    /**
     * @var SpingoProductConfigProviderInterface
     */
    private $spingoProductConfigProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var int
     */
    private $storeId = null;

    public function __construct(
        Repository $assetRepository,
        SpingoProductConfigProviderInterface $spingoProductConfigProvider,
        StoreManagerInterface $storeManager
    ){
        $this->assetRepository = $assetRepository;
        $this->spingoProductConfigProvider = $spingoProductConfigProvider;
        $this->storeManager = $storeManager;
    }

    public function getImageTitle(): string
    {
        return (string)__('Spingo - defer payment');
    }

    public function getBannerUrl(): string
    {
        return $this->spingoProductConfigProvider->getBannerUrl($this->getStoreId());
    }

    public function getBannerImage(): ?string
    {
        $image = $this->spingoProductConfigProvider->getBannerImage($this->getStoreId());
        if (empty($image)) {
            return null;
        }

        return sprintf('%s/%s/%s', UrlInterface::URL_TYPE_MEDIA, SpingoImageConfigInterface::UPLOAD_DIR, $image);
    }

    private function getStoreId(): int
    {
        if ($this->storeId === null) {
            $this->storeId = (int)$this->storeManager->getStore()->getId();
        }

        return $this->storeId;
    }
}
