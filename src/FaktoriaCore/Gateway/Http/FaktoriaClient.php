<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Gateway\Http;

use Faktoria\FaktoriaApi\Api\FaktoriaApiClientInterface;
use Faktoria\FaktoriaCore\Exception\FaktoriaApiException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;

class FaktoriaClient implements ClientInterface
{
    /**
     * @var FaktoriaApiClientInterface
     */
    private $faktoriaApiClient;

    public function __construct(FaktoriaApiClientInterface $faktoriaApiClient)
    {
        $this->faktoriaApiClient = $faktoriaApiClient;
    }

    public function placeRequest(TransferInterface $transferObject)
    {
        try {
            $responseRaw = $this->faktoriaApiClient->submit($transferObject->getBody());
        } catch (FaktoriaApiException $e) {
            return [];
        }

        return json_decode($responseRaw, true);
    }
}
