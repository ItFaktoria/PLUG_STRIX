<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Gateway\Http;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Spingo\SpingoApi\Api\SpingoApiClientInterface;
use Spingo\SpingoCore\Exception\SpingoApiException;

class SpingoClient implements ClientInterface
{
    /**
     * @var SpingoApiClientInterface
     */
    private $spingoApiClient;

    public function __construct(SpingoApiClientInterface $spingoApiClient)
    {
        $this->spingoApiClient = $spingoApiClient;
    }

    public function placeRequest(TransferInterface $transferObject)
    {
        try {
            $responseRaw = $this->spingoApiClient->submit($transferObject->getBody());
        } catch (SpingoApiException $e) {
            return [];
        }

        return json_decode($responseRaw, true);
    }
}
