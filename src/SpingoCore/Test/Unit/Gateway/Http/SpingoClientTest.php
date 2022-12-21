<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Gateway\Http;

use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ConverterException;
use Magento\Payment\Gateway\Http\TransferInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoCore\Client\SpingoApiClient;
use Spingo\SpingoCore\Exception\SpingoApiException;
use Spingo\SpingoCore\Gateway\Http\SpingoClient;

class SpingoClientTest extends TestCase
{
    private const TEST_DATA = ['test' => 'test'];

    /**
     * @var MockObject|TransferInterface
     */
    private $transfer;

    /**
     * @var MockObject|SpingoApiClient
     */
    private $spingoApiClient;

    /**
     * @var SpingoClient
     */
    private $spingoClient;

    protected function setUp(): void
    {
        $this->transfer = $this->createMock(TransferInterface::class);
        $this->transfer->expects($this->once())->method('getBody')->willReturn(self::TEST_DATA);
        $this->spingoApiClient = $this->createMock(SpingoApiClient::class);
        $this->spingoClient = new SpingoClient($this->spingoApiClient);
    }

    /**
     * @throws ConverterException
     * @throws ClientException
     */
    public function testPlaceOrder(): void
    {
        $this->spingoApiClient->expects($this->once())->method('submit')->willReturn(json_encode(self::TEST_DATA));
        $result = $this->spingoClient->placeRequest($this->transfer);
        $this->assertEquals(self::TEST_DATA, $result);
    }

    /**
     * @throws ConverterException
     * @throws ClientException
     */
    public function testPlaceOrderWithException(): void
    {
        $this->spingoApiClient->expects($this->once())->method('submit')->willThrowException(new SpingoApiException());
        $result = $this->spingoClient->placeRequest($this->transfer);
        $this->assertEquals([], $result);
    }
}
