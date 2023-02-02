<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoProductConfigProviderInterface
{
    public function getBannerUrl(int $storeId): string;
    public function getBannerImage(int $storeId): string;
}
