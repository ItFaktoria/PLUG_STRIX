<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoOrderChangeStatusServiceInterface
{
    public function execute(int $orderId, string $statusCode): void;
}
