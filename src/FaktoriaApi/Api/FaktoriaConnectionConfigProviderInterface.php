<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaConnectionConfigProviderInterface
{
    public function isActive(int $storeId): bool;
    public function isSandbox(int $storeId): bool;
    public function isSendNotification(int $storeId): bool;
    public function getApiKey(int $storeId): string;
    public function getSandboxApiKey(int $storeId): string;
    public function getMerchantId(int $storeId): string;
    public function getContractId(int $storeId): string;
    public function getReturnUrl(int $storeId): string;
    public function getCancelUrl(int $storeId): string;
}
