<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Gateway\Request;

use InvalidArgumentException;
use LogicException;
use Magento\Checkout\Model\Session;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Spingo\SpingoApi\Api\SpingoSubmitParameterResolverInterface;
use Throwable;

class SpingoApiSubmitRequest implements BuilderInterface
{
    /**
     * @var SpingoSubmitParameterResolverInterface
     */
    private $spingoSubmitParameterResolver;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    public function __construct(
        SpingoSubmitParameterResolverInterface $spingoSubmitParameterResolver,
        Session $checkoutSession,
        CartRepositoryInterface $cartRepository
    ) {
        $this->spingoSubmitParameterResolver = $spingoSubmitParameterResolver;
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
    }

    public function build(array $buildSubject): array
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new InvalidArgumentException('Payment data object should be provided');
        }
        $buildPayment = $buildSubject['payment'];
        if (!$buildPayment->getPayment() instanceof OrderPaymentInterface) {
            throw new LogicException('Order payment should be provided.');
        }
        try {
            $this->checkoutSession->getQuote()->reserveOrderId();
            $this->cartRepository->save($this->checkoutSession->getQuote());
            $order = $buildPayment->getOrder();

            return $this->spingoSubmitParameterResolver->resolve(
                $this->checkoutSession->getQuote()->getReservedOrderId(),
                (float)$order->getGrandTotalAmount(),
                $this->checkoutSession->getQuote()->getBillingAddress()->getVatId(),
                (int)$order->getStoreId()
            );
        } catch (Throwable $e) {
            return [];
        }
    }
}
