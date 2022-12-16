<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Spingo\SpingoApi\Api\SpingoConnectionConfigProviderInterface;
use Spingo\SpingoApi\Api\SpingoApiClientInterface;
use Spingo\SpingoCore\Exception\SpingoApiException;
use Throwable;

class SpingoApiClient implements SpingoApiClientInterface
{
    private const API_URL = 'https://api.faktoria.pl';
    private const PRODUCTION_URI = 'fpay';
    private const SANDBOX_URI = 'fpay-sandbox';

    /**
     * @var SpingoConnectionConfigProviderInterface
     */
    private $spingoConnectionConfigProvider;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        SpingoConnectionConfigProviderInterface $spingoConnectionConfigProvider,
        ClientFactory $clientFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->spingoConnectionConfigProvider = $spingoConnectionConfigProvider;
        $this->clientFactory = $clientFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    public function submit(array $params): string
    {
        $client = $this->getClient();
        try {
            $storeId = (int)$this->storeManager->getStore()->getId();
            if ($this->spingoConnectionConfigProvider->isLogCartRequest($storeId)) {
                $this->logger->notice(json_encode($params));
            }
            $result = $client->request(
                'POST',
                $this->getUri($storeId),
                [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $this->getAuthorization($storeId)
                    ],
                    'json' => $params
                ]
            );

            return (string)$result->getBody()->getContents();
        } catch (ClientException | GuzzleException | NoSuchEntityException $e) {
            $this->logger->notice($e->getMessage());
            throw new SpingoApiException(
                (string)__('Something went wrong with your request. Please try again later.')
            );
        }
    }

    private function getClient(): Client
    {
        return $this->clientFactory->create([
            'config' => [
                'base_uri' => self::API_URL,
                'verify' => true,
                'connect_timeout' => 30,
                'timeout' => 30
            ]
        ]);
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getUri(int $storeId): string
    {
        $uri = self::PRODUCTION_URI;
        if ($this->spingoConnectionConfigProvider->isSandbox($storeId)) {
            $uri = self::SANDBOX_URI;
        }

        return sprintf('%s/%s', $uri, 'Submit');
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getAuthorization(int $storeId): string
    {
        $authorizationKey = $this->spingoConnectionConfigProvider->getApiKey($storeId);
        if ($this->spingoConnectionConfigProvider->isSandbox($storeId)) {
            $authorizationKey = $this->spingoConnectionConfigProvider->getSandboxApiKey($storeId);
        }
        try {
            return $authorizationKey;
        } catch (Throwable $e) {
            $this->logger->notice($e->getMessage());

            return '';
        }
    }
}
