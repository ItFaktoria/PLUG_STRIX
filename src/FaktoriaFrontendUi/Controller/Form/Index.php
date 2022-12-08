<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaFrontendUi\Controller\Form;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order\Payment;
use Throwable;

class Index implements HttpGetActionInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    public function __construct(Session $checkoutSession, ResultFactory $resultFactory)
    {
        $this->checkoutSession = $checkoutSession;
        $this->resultFactory = $resultFactory;
    }

    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            /** @var $payment Payment */
            $payment = $this->checkoutSession->getLastRealOrder()->getPayment();
            if ($payment === null) {
                throw new LocalizedException(__('Payment not found.'));
            }
            $paymentInformation = $payment->getAdditionalInformation();
            if (
                is_array($paymentInformation) &&
                array_key_exists('FKT_FPAY_IN_URL.URL_APPLICATION', $paymentInformation)
            ) {
                $resultRedirect->setUrl($paymentInformation['FKT_FPAY_IN_URL.URL_APPLICATION']);
            }
        } catch (Throwable $e) {
            $resultRedirect->setPath('checkout/cart');
        }

        return $resultRedirect;
    }
}
