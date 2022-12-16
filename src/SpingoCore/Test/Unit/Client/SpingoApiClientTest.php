<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\ClientException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Spingo\SpingoAdminUi\Provider\SpingoConnectionConfigProvider;
use Spingo\SpingoCore\Client\SpingoApiClient;
use Spingo\SpingoCore\Exception\SpingoApiException;

class SpingoApiClientTest extends TestCase
{
    /**
     * @var MockObject|SpingoConnectionConfigProvider
     */
    private $spingoConnectionConfigProvider;

    /**
     * @var MockObject|ClientFactory
     */
    private $clientFactory;

    /**
     * @var MockObject|Client
     */
    private $client;

    /**
     * @var MockObject|StoreManager
     */
    private $storeManager;

    /**
     * @var MockObject|Store
     */
    private $store;

    /**
     * @var MockObject|ResponseInterface
     */
    private $response;

    /**
     * @var MockObject|StreamInterface
     */
    private $stream;

    /**
     * @var MockObject|Logger
     */
    private $logger;

    /**
     * @var SpingoApiClient
     */
    private $spingoApiClient;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->spingoConnectionConfigProvider = $this->createMock(SpingoConnectionConfigProvider::class);
        $this->clientFactory = $this->createMock(ClientFactory::class);
        $this->client = $this->createMock(Client::class);
        $this->storeManager = $this->createMock(StoreManager::class);
        $this->store = $this->createMock(Store::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
        $this->logger = $this->createMock(Logger::class);
        $this->spingoApiClient = $objectManager->getObject(
            SpingoApiClient::class,
            [
                'spingoConnectionConfigProvider'=> $this->spingoConnectionConfigProvider,
                'clientFactory' => $this->clientFactory,
                'storeManager' => $this->storeManager,
                'logger' => $this->logger
            ]
        );
    }

    public function testSubmit(): void
    {
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isLogCartRequest');
        $this->clientFactory->expects($this->once())->method('create')->willReturn($this->client);
        $this->storeManager->expects($this->once())->method('getStore')->willReturn($this->store);
        $this->response->expects($this->once())->method('getBody')->willReturn($this->stream);
        $this->client->expects($this->once())->method('request')->willReturn($this->response);
        $this->logger->expects($this->never())->method('notice');
        $submitResult = $this->spingoApiClient->submit([]);
        $this->assertIsString($submitResult);
    }

    public function testSubmitWithCartRequestLog(): void
    {
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isLogCartRequest')->willReturn(true);
        $this->clientFactory->expects($this->once())->method('create')->willReturn($this->client);
        $this->storeManager->expects($this->once())->method('getStore')->willReturn($this->store);
        $this->response->expects($this->once())->method('getBody')->willReturn($this->stream);
        $this->client->expects($this->once())->method('request')->willReturn($this->response);
        $this->logger->expects($this->once())->method('notice');
        $submitResult = $this->spingoApiClient->submit([]);
        $this->assertIsString($submitResult);
    }

    public function testSubmitException(): void
    {
        $clientException = $this->createMock(ClientException::class);
        $this->expectExceptionObject(new SpingoApiException());
        $this->expectExceptionMessage('Something went wrong with your request. Please try again later.');
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isLogCartRequest');
        $this->clientFactory->expects($this->once())->method('create')->willReturn($this->client);
        $this->storeManager->expects($this->once())->method('getStore')->willReturn($this->store);
        $this->response->expects($this->never())->method('getBody')->willReturn($this->stream);
        $this->client->expects($this->once())->method('request')->willThrowException($clientException);
        $this->logger->expects($this->once())->method('notice');
        $submitResult = $this->spingoApiClient->submit([]);
        $this->assertIsString($submitResult);
    }
}
