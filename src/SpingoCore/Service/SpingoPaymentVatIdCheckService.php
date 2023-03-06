<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Service;

use Spingo\SpingoApi\Api\SpingoPaymentVatIdCheckServiceInterface;

class SpingoPaymentVatIdCheckService implements SpingoPaymentVatIdCheckServiceInterface
{
    public function isAvailableByVatId(string $vatId): bool
    {
        return !empty($vatId);
    }
}
