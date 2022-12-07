<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Gateway\Http;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class FaktoriaClient implements ClientInterface
{
    public function placeRequest(TransferInterface $transferObject)
    {

    }
}
