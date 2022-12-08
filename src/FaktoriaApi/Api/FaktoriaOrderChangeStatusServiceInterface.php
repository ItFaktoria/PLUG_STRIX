<?php

namespace Faktoria\FaktoriaApi\Api;

interface FaktoriaOrderChangeStatusServiceInterface
{
    public function execute(int $orderId, string $statusCode): void;
}
