<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Service;

use Faktoria\FaktoriaApi\Api\FaktoriaNotifyOrderStatusProviderInterface;
use Faktoria\FaktoriaApi\Api\FaktoriaNotifyStatusMessageResolverInterface;
use Faktoria\FaktoriaApi\Api\FaktoriaOrderChangeStatusServiceInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class FaktoriaOrderChangeStatusService implements FaktoriaOrderChangeStatusServiceInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var FaktoriaNotifyStatusMessageResolverInterface
     */
    private $faktoriaNotifyStatusMessageResolver;

    /**
     * @var FaktoriaNotifyOrderStatusProviderInterface
     */
    private $faktoriaNotifyOrderStatusProvider;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        FaktoriaNotifyStatusMessageResolverInterface $faktoriaNotifyStatusMessageResolver,
        FaktoriaNotifyOrderStatusProviderInterface $faktoriaNotifyOrderStatusProvider
    ) {
        $this->orderRepository = $orderRepository;
        $this->faktoriaNotifyStatusMessageResolver = $faktoriaNotifyStatusMessageResolver;
        $this->faktoriaNotifyOrderStatusProvider = $faktoriaNotifyOrderStatusProvider;
    }

    public function execute(int $orderId, string $statusCode): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->get($orderId);
        $orderComment = $this->faktoriaNotifyStatusMessageResolver->resolve($statusCode);
        $orderStatus = $this->faktoriaNotifyOrderStatusProvider->provide($statusCode);
        $order->addCommentToStatusHistory($orderComment, $orderStatus);
        $order->setStatus($orderStatus);
        $order->setState($orderStatus);
        if ($orderStatus === Order::STATE_CLOSED) {
            $order->cancel();
        }
        $this->orderRepository->save($order);
    }
}
