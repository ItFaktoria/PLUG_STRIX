<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Gateway\Request;

use Faktoria\FaktoriaApi\Api\FaktoriaSubmitParameterResolverInterface;
use InvalidArgumentException;
use Magento\Checkout\Model\Session;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use LogicException;
use Throwable;

class FaktoriaApiSubmitRequest implements BuilderInterface
{
    /**
     * @var FaktoriaSubmitParameterResolverInterface
     */
    private $faktoriaSubmitParameterResolver;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    public function __construct(
        FaktoriaSubmitParameterResolverInterface $faktoriaSubmitParameterResolver,
        Session $checkoutSession,
        CartRepositoryInterface $cartRepository
    ) {
        $this->faktoriaSubmitParameterResolver = $faktoriaSubmitParameterResolver;
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

            return $this->faktoriaSubmitParameterResolver->resolve(
                $this->checkoutSession->getQuote()->getReservedOrderId(),
                (float)$order->getGrandTotalAmount(),
                (int)$order->getStoreId()
            );
        } catch (Throwable $e) {
            return [];
        }
    }
}
