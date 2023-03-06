<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoNotifyStatusMessageResolverInterface
{
    public function resolve(string $statusCode): string;
}
