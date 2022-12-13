<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoCartConfigProviderInterface
{
    public function getMinOrderTotal(int $storeId): float;
    public function getMaxOrderTotal(int $storeId): float;
}
