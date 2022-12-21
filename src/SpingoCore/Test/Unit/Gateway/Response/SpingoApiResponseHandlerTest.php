<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Gateway\Response;

use InvalidArgumentException;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoCore\Gateway\Response\SpingoApiResponseHandler;

class SpingoApiResponseHandlerTest extends TestCase
{
    private const TEST_RESPONSE_DATA = ['fieldInfos' => ['test_url'], 'applicationNumber' => '123456789'];

    /**
     * @var SpingoApiResponseHandler
     */
    private $spingoApiResponseHandler;

    protected function setUp(): void
    {
        $this->spingoApiResponseHandler = new SpingoApiResponseHandler();
    }

    public function testHandle(): void
    {
        $this->spingoApiResponseHandler->handle($this->getTestHandlingSubjectData(), self::TEST_RESPONSE_DATA);
    }

    public function testHandleException(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException());
        $this->expectExceptionMessage('Payment data object should be provided');
        $this->spingoApiResponseHandler->handle([], []);
    }

    private function getTestHandlingSubjectData(): array
    {
        $orderPayment = $this->createMock(OrderPaymentInterface::class);
        $orderPayment->expects($this->atMost(2))->method('setAdditionalInformation');
        $payment = $this->createMock(PaymentDataObjectInterface::class);
        $payment->expects($this->atMost(1))->method('getPayment')->willReturn($orderPayment);

        return ['payment' => $payment];
    }
}
