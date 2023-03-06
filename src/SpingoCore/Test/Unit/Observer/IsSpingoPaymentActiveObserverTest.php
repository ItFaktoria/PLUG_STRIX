<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Api\Data\CurrencyInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoApi\Api\SpingoConnectionConfigProviderInterface;
use Spingo\SpingoApi\Api\SpingoPaymentGrandTotalThresholdServiceInterface;
use Spingo\SpingoApi\Api\SpingoPaymentVatIdCheckServiceInterface;
use Spingo\SpingoCore\Observer\IsSpingoPaymentActiveObserver;
use Spingo\SpingoCore\Service\SpingoPaymentCurrencyCheckService;

class IsSpingoPaymentActiveObserverTest extends TestCase
{
    /**
     * @var Observer|(Observer&MockObject)|MockObject
     */
    private $observer;

    /**
     * @var MockObject|SpingoPaymentGrandTotalThresholdServiceInterface
     */
    private $spingoPaymentGrandTotalThresholdService;

    /**
     * @var MockObject|SpingoPaymentCurrencyCheckService
     */
    private $spingoPaymentCurrencyCheckService;

    /**
     * @var MockObject|SpingoPaymentVatIdCheckServiceInterface
     */
    private $spingoPaymentVatIdCheckService;

    /**
     * @var MockObject|SpingoConnectionConfigProviderInterface
     */
    private $spingoConnectionConfigProvider;

    /**
     * @var MockObject|DataObject
     */
    private $dataObject;

    /**
     * @var MockObject|Adapter
     */
    private $adapter;

    /**
     * @var IsSpingoPaymentActiveObserver
     */
    private $isSpingoPaymentActiveObserver;

    protected function setUp(): void
    {
        $this->observer = $this->createMock(Observer::class);
        $this->spingoPaymentGrandTotalThresholdService = $this->createMock(
            SpingoPaymentGrandTotalThresholdServiceInterface::class
        );
        $this->spingoPaymentCurrencyCheckService = $this->createMock(SpingoPaymentCurrencyCheckService::class);
        $this->spingoPaymentVatIdCheckService = $this->createMock(SpingoPaymentVatIdCheckServiceInterface::class);
        $this->spingoConnectionConfigProvider = $this->createMock(SpingoConnectionConfigProviderInterface::class);
        $this->dataObject = $this->createMock(DataObject::class);
        $this->adapter = $this->createMock(Adapter::class);
        $quote = $this->createMock(Quote::class);
        $currency = $this->createMock(CurrencyInterface::class);
        $address = $this->createMock(Address::class);
        $quote->expects($this->atMost(1))->method('getCurrency')->willReturn($currency);
        $quote->expects($this->atMost(1))->method('getBillingAddress')->willReturn($address);
        $this->observer->expects($this->exactly(3))->method('getData')->withConsecutive(['result', null])
            ->willReturnOnConsecutiveCalls(
                $this->dataObject,
                $this->adapter,
                $quote
            );
        $this->isSpingoPaymentActiveObserver = new IsSpingoPaymentActiveObserver(
            $this->spingoPaymentGrandTotalThresholdService,
            $this->spingoPaymentCurrencyCheckService,
            $this->spingoPaymentVatIdCheckService,
            $this->spingoConnectionConfigProvider
        );
    }

    public function testExecute(): void
    {
        $this->adapter->expects($this->once())->method('getCode')->willReturn('spingo_payment');
        $this->dataObject->expects($this->once())->method('getData')->with('is_available')->willReturn(true);
        $this->dataObject->expects($this->once())->method('setData')->with('is_available');
        $this->spingoPaymentGrandTotalThresholdService->expects($this->once())
            ->method('isAvailableByConfigurationThreshold')->willReturn(true);
        $this->spingoPaymentCurrencyCheckService->expects($this->once())->method('isAvailableByCurrency')
            ->willReturn(true);
        $this->spingoPaymentVatIdCheckService->expects($this->once())->method('isAvailableByVatId')->willReturn(true);
        $this->spingoConnectionConfigProvider->expects($this->once())->method('isActive');
        $this->isSpingoPaymentActiveObserver->execute($this->observer);
    }

    public function testExecuteNotAvailable(): void
    {
        $this->adapter->expects($this->once())->method('getCode')->willReturn('spingo_payment');
        $this->dataObject->expects($this->once())->method('getData')->with('is_available')->willReturn(false);
        $this->assertNever();
    }

    public function testExecuteDifferentPaymentMethod(): void
    {
        $this->adapter->expects($this->once())->method('getCode')->willReturn('test_test');
        $this->dataObject->expects($this->never())->method('getData')->with('is_available')->willReturn(false);
        $this->assertNever();
    }

    private function assertNever(): void
    {
        $this->dataObject->expects($this->never())->method('setData')->with('is_available');
        $this->spingoPaymentGrandTotalThresholdService->expects($this->never())
            ->method('isAvailableByConfigurationThreshold');
        $this->spingoPaymentCurrencyCheckService->expects($this->never())->method('isAvailableByCurrency');
        $this->spingoPaymentVatIdCheckService->expects($this->never())->method('isAvailableByVatId');
        $this->spingoConnectionConfigProvider->expects($this->never())->method('isActive');
        $this->isSpingoPaymentActiveObserver->execute($this->observer);
    }
}
