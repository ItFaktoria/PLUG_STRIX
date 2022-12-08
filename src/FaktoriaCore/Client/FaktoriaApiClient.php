<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Client;

use Faktoria\FaktoriaApi\Api\FaktoriaConnectionConfigProviderInterface;
use Faktoria\FaktoriaApi\Api\FaktoriaApiClientInterface;
use Faktoria\FaktoriaCore\Exception\FaktoriaApiException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class FaktoriaApiClient implements FaktoriaApiClientInterface
{
    private const API_URL = 'https://api.faktoria.pl';
    private const PRODUCTION_URI = 'fpay';
    private const SANDBOX_URI = 'fpay-sandbox';

    /**
     * @var FaktoriaConnectionConfigProviderInterface
     */
    private $faktoriaConnectionConfigProvider;

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
        FaktoriaConnectionConfigProviderInterface $faktoriaConnectionConfigProvider,
        ClientFactory $clientFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->faktoriaConnectionConfigProvider = $faktoriaConnectionConfigProvider;
        $this->clientFactory = $clientFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    public function submit(array $params): string
    {
        $client = $this->getClient();
        try {
            $result = $client->request(
                'POST',
                $this->getUri(),
                [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $this->getAuthorization()
                    ],
                    'json' => $params
                ]
            );

            return $result->getBody()->getContents();
        } catch (ClientException | GuzzleException | NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            throw new FaktoriaApiException(
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
    private function getUri(): string
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $uri = self::PRODUCTION_URI;
        if ($this->faktoriaConnectionConfigProvider->isSandbox($storeId)) {
            $uri = self::SANDBOX_URI;
        }

        return sprintf('%s/%s', $uri, 'Submit');
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getAuthorization(): string
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $authorizationKey = $this->faktoriaConnectionConfigProvider->getApiKey($storeId);
        if ($this->faktoriaConnectionConfigProvider->isSandbox($storeId)) {
            $authorizationKey = $this->faktoriaConnectionConfigProvider->getSandboxApiKey($storeId);
        }
        try {
            return $authorizationKey;
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            return '';
        }
    }
}
