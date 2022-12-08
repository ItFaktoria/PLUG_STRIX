<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaCartConfigProviderInterface
{
    public function getMinOrderTotal(int $storeId): float;
    public function getMaxOrderTotal(int $storeId): float;
}
