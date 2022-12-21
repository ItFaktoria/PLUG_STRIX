<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\ClientException;
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
     * @var MockObject|Client
     */
    private $client;

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
        $this->spingoConnectionConfigProvider = $this->createMock(SpingoConnectionConfigProvider::class);
        $this->client = $this->createMock(Client::class);
        $clientFactory = $this->createMock(ClientFactory::class);
        $clientFactory->expects($this->once())->method('create')->willReturn($this->client);
        $storeManager = $this->createMock(StoreManager::class);
        $store = $this->createMock(Store::class);
        $storeManager->expects($this->once())->method('getStore')->willReturn($store);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
        $this->logger = $this->createMock(Logger::class);
        $this->spingoApiClient = new SpingoApiClient(
            $this->spingoConnectionConfigProvider,
            $clientFactory,
            $storeManager,
            $this->logger
        );
    }

    /**
     * @throws SpingoApiException
     */
    public function testSubmit(): void
    {
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isLogCartRequest');
        $this->response->expects($this->once())->method('getBody')->willReturn($this->stream);
        $this->client->expects($this->once())->method('request')->willReturn($this->response);
        $this->logger->expects($this->never())->method('notice');
        $submitResult = $this->spingoApiClient->submit([]);
        $this->assertIsString($submitResult);
    }

    /**
     * @throws SpingoApiException
     */
    public function testSubmitWithCartRequestLog(): void
    {
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isLogCartRequest')->willReturn(true);
        $this->response->expects($this->once())->method('getBody')->willReturn($this->stream);
        $this->client->expects($this->once())->method('request')->willReturn($this->response);
        $this->logger->expects($this->once())->method('notice');
        $submitResult = $this->spingoApiClient->submit([]);
        $this->assertIsString($submitResult);
    }

    /**
     * @throws SpingoApiException
     */
    public function testSubmitException(): void
    {
        $clientException = $this->createMock(ClientException::class);
        $this->expectExceptionObject(new SpingoApiException());
        $this->expectExceptionMessage('Something went wrong with your request. Please try again later.');
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isLogCartRequest');
        $this->response->expects($this->never())->method('getBody')->willReturn($this->stream);
        $this->client->expects($this->once())->method('request')->willThrowException($clientException);
        $this->logger->expects($this->once())->method('notice');
        $submitResult = $this->spingoApiClient->submit([]);
        $this->assertIsString($submitResult);
    }
}
