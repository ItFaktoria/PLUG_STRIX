<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

interface SpingoPaymentVatIdCheckServiceInterface
{
    public function isAvailableByVatId(string $vatId): bool;
}
