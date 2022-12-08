<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaNotifyOrderStatusProviderInterface
{
    public function provide(string $statusCode): ?string;
}
