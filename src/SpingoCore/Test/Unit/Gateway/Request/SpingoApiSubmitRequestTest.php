<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Gateway\Request;

use Exception;
use InvalidArgumentException;
use LogicException;
use Magento\Checkout\Model\Session;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoApi\Api\SpingoSubmitParameterResolverInterface;
use Spingo\SpingoCore\Gateway\Request\SpingoApiSubmitRequest;

class SpingoApiSubmitRequestTest extends TestCase
{
    private const TEST_DATA = ['test' => 'test'];

    /**
     * @var MockObject|SpingoSubmitParameterResolverInterface
     */
    private $spingoSubmitParameterResolver;

    /**
     * @var MockObject|Session
     */
    private $checkoutSession;

    /**
     * @var MockObject|CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var SpingoApiSubmitRequest
     */
    private $spingoApiSubmitRequest;

    protected function setUp(): void
    {
        $this->spingoSubmitParameterResolver = $this->createMock(SpingoSubmitParameterResolverInterface::class);
        $this->checkoutSession = $this->createMock(Session::class);
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->spingoApiSubmitRequest = new SpingoApiSubmitRequest(
            $this->spingoSubmitParameterResolver,
            $this->checkoutSession,
            $this->cartRepository
        );
    }

    public function testBuild(): void
    {
        $quote = $this->createMock(Quote::class);
        $billingAddress = $this->createMock(Address::class);
        $billingAddress->expects($this->once())->method('getVatId')->willReturn('123456789');
        $quote->expects($this->once())->method('reserveOrderId')->willReturn($quote);
        $quote->expects($this->once())->method('getReservedOrderId')->willReturn('12345');
        $quote->expects($this->once())->method('getBillingAddress')->willReturn($billingAddress);
        $this->checkoutSession->expects($this->exactly(4))->method('getQuote')->willReturn($quote);
        $this->cartRepository->expects($this->once())->method('save');
        $this->spingoSubmitParameterResolver->expects($this->once())->method('resolve')->willReturn(self::TEST_DATA);
        $result = $this->spingoApiSubmitRequest->build($this->getTestData());
        $this->assertEquals(self::TEST_DATA, $result);
    }

    public function testBuildWithException(): void
    {
        $this->spingoSubmitParameterResolver->expects($this->any())->method('resolve')->willThrowException(
            new Exception('test')
        );
        $result = $this->spingoApiSubmitRequest->build($this->getTestData());
        $this->assertEquals([], $result);
    }

    public function testBuildWithoutPayment(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException());
        $this->expectExceptionMessage('Payment data object should be provided');
        $this->spingoApiSubmitRequest->build($this->getTestData(false));
    }

    public function testBuildWithoutOrderPayment(): void
    {
        $this->expectExceptionObject(new LogicException());
        $this->expectExceptionMessage('Order payment should be provided.');
        $this->spingoApiSubmitRequest->build($this->getTestData(true, false));
    }

    private function getTestData(bool $withPayment = true, bool $withOrderPayment = true): array
    {
        $order = $this->createMock(OrderAdapterInterface::class);
        $orderPayment = $this->createMock(OrderPaymentInterface::class);
        $payment = $this->createMock(PaymentDataObjectInterface::class);
        if (!$withPayment) {
            $payment->expects($this->never())->method('getPayment');

            return [];
        }
        if ($withOrderPayment) {
            $payment->expects($this->atMost(1))->method('getPayment')->willReturn($orderPayment);
            $order->expects($this->atMost(1))->method('getGrandTotalAmount')->willReturn('123');
            $order->expects($this->atMost(1))->method('getStoreId')->willReturn('1');
            $payment->expects($this->atMost(1))->method('getOrder')->willReturn($order);
        }
        if (!$withOrderPayment) {
            $payment->expects($this->never())->method('getOrder');
        }

        return ['payment' => $payment];
    }
}
