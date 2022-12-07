<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

use Magento\Framework\App\Config\ScopeConfigInterface;

interface FaktoriaConfigProviderInterface
{
    public function isActive(int $storeId): bool;
    public function isSandbox(int $storeId): bool;
    public function getApiKey(int $storeId): string;
    public function getMerchantId(int $storeId): string;
    public function getContractId(int $storeId): string;
}
