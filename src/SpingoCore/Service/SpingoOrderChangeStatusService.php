<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Service;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Spingo\SpingoApi\Api\SpingoNotifyOrderStatusProviderInterface;
use Spingo\SpingoApi\Api\SpingoNotifyStatusMessageResolverInterface;
use Spingo\SpingoApi\Api\SpingoOrderChangeStatusServiceInterface;

class SpingoOrderChangeStatusService implements SpingoOrderChangeStatusServiceInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SpingoNotifyStatusMessageResolverInterface
     */
    private $spingoNotifyStatusMessageResolver;

    /**
     * @var SpingoNotifyOrderStatusProviderInterface
     */
    private $spingoNotifyOrderStatusProvider;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SpingoNotifyStatusMessageResolverInterface $spingoNotifyStatusMessageResolver,
        SpingoNotifyOrderStatusProviderInterface $spingoNotifyOrderStatusProvider
    ) {
        $this->orderRepository = $orderRepository;
        $this->spingoNotifyStatusMessageResolver = $spingoNotifyStatusMessageResolver;
        $this->spingoNotifyOrderStatusProvider = $spingoNotifyOrderStatusProvider;
    }

    public function execute(int $orderId, string $statusCode): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);
        if ($order->isCanceled()) {
            return;
        }
        $orderComment = $this->spingoNotifyStatusMessageResolver->resolve($statusCode);
        $orderStatus = $this->spingoNotifyOrderStatusProvider->provide($statusCode);
        $order->addCommentToStatusHistory($orderComment);
        if ($order->getTotalDue() > 0 && $orderStatus !== Order::STATE_CANCELED) {
            $order->setTotalPaid($order->getTotalDue());
            $order->setBaseTotalPaid($order->getBaseTotalDue());
            $order->setStatus($orderStatus);
            $order->setState($orderStatus);
        }
        if ($orderStatus === Order::STATE_CANCELED) {
            $order->setStatus($orderStatus);
            $order->setState($orderStatus);
            $order->cancel();
        }
        $this->orderRepository->save($order);
    }
}
