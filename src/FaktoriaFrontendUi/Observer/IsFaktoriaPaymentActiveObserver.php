<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaFrontendUi\Observer;

use Faktoria\FaktoriaApi\Api\FaktoriaPaymentGrandTotalThresholdServiceInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Model\Quote;

class IsFaktoriaPaymentActiveObserver implements ObserverInterface
{
    /**
     * @var FaktoriaPaymentGrandTotalThresholdServiceInterface
     */
    private $faktoriaPaymentGrandTotalThresholdService;

    public function __construct(
        FaktoriaPaymentGrandTotalThresholdServiceInterface $faktoriaPaymentGrandTotalThresholdService
    ) {
        $this->faktoriaPaymentGrandTotalThresholdService = $faktoriaPaymentGrandTotalThresholdService;
    }

    public function execute(Observer $observer): void
    {
        /** @var DataObject $checkResult */
        $checkResult = $observer->getData('result');
        /** @var Adapter $methodInstance */
        $methodInstance = $observer->getData('method_instance');
        /** @var Quote $quote */
        $quote = $observer->getData('quote');
        if ($methodInstance->getCode() !== 'faktoria_payment' || $checkResult->getData('is_available') === false) {
            return;
        }
        $isAvailable = $this->faktoriaPaymentGrandTotalThresholdService->isAvailableByConfigurationThreshold(
            (float)$quote->getGrandTotal(),
            (int)$quote->getStoreId()
        );
        $checkResult->setData('is_available', $isAvailable);
    }
}
