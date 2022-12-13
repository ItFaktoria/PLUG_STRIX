<?php

declare(strict_types=1);

namespace Spingo\SpingoFrontendUi\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Model\Quote;
use Spingo\SpingoApi\Api\SpingoPaymentCurrencyCheckServiceInterface;
use Spingo\SpingoApi\Api\SpingoPaymentGrandTotalThresholdServiceInterface;

class IsSpingoPaymentActiveObserver implements ObserverInterface
{
    /**
     * @var SpingoPaymentGrandTotalThresholdServiceInterface
     */
    private $spingoPaymentGrandTotalThresholdService;

    /**
     * @var SpingoPaymentCurrencyCheckServiceInterface
     */
    private $spingoPaymentCurrencyCheckService;

    public function __construct(
        SpingoPaymentGrandTotalThresholdServiceInterface $spingoPaymentGrandTotalThresholdService,
        SpingoPaymentCurrencyCheckServiceInterface $spingoPaymentCurrencyCheckService
    ) {
        $this->spingoPaymentGrandTotalThresholdService = $spingoPaymentGrandTotalThresholdService;
        $this->spingoPaymentCurrencyCheckService = $spingoPaymentCurrencyCheckService;
    }

    public function execute(Observer $observer): void
    {
        /** @var DataObject $checkResult */
        $checkResult = $observer->getData('result');
        /** @var Adapter $methodInstance */
        $methodInstance = $observer->getData('method_instance');
        /** @var Quote $quote */
        $quote = $observer->getData('quote');
        if ($methodInstance->getCode() !== 'spingo_payment' || $checkResult->getData('is_available') === false) {
            return;
        }
        $isAvailableTotal = $this->spingoPaymentGrandTotalThresholdService->isAvailableByConfigurationThreshold(
            (float)$quote->getGrandTotal(),
            (int)$quote->getStoreId()
        );
        $isAvailableCurrency = $this->spingoPaymentCurrencyCheckService->isAvailableByCurrency(
            (string)$quote->getCurrency()->getQuoteCurrencyCode()
        );
        $checkResult->setData('is_available', $isAvailableTotal && $isAvailableCurrency);
    }
}
