<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Gateway\Response;

use InvalidArgumentException;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;

class SpingoApiResponseHandler implements HandlerInterface
{
    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new InvalidArgumentException('Payment data object should be provided');
        }
        /** @var PaymentDataObjectInterface $payment */
        $handlingPayment = $handlingSubject['payment'];
        $payment = $handlingPayment->getPayment();
        $payment->setAdditionalInformation('FKT_FPAY_IN_URL.URL_APPLICATION', $response['fieldInfos'][0]);
        $payment->setAdditionalInformation('applicationNumber', $response['applicationNumber']);
    }
}
