<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoNotifyOrderStatusProviderInterface
{
    public function provide(string $statusCode): ?string;
}
