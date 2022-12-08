<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaNotifyStatusMessageResolverInterface
{
    public function resolve(string $statusCode): string;
}
