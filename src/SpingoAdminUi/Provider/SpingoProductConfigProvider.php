<?php

declare(strict_types=1);

namespace Spingo\SpingoAdminUi\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Spingo\SpingoApi\Api\SpingoProductConfigProviderInterface;

class SpingoProductConfigProvider implements SpingoProductConfigProviderInterface
{
    private const BANNER_URL = 'spingo_payment/product/banner_url';
    private const BANNER_IMAGE = 'spingo_payment/product/product_banner_image';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getBannerUrl(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::BANNER_URL,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }

    public function getBannerImage(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::BANNER_IMAGE,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }
}
