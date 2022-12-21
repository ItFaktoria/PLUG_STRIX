<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Service;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoApi\Api\SpingoNotifyOrderStatusProviderInterface;
use Spingo\SpingoApi\Api\SpingoNotifyStatusMessageResolverInterface;
use Spingo\SpingoCore\Service\SpingoOrderChangeStatusService;

class SpingoOrderChangeStatusServiceTest extends TestCase
{
    /**
     * @var MockObject|Order
     */
    private $order;

    /**
     * @var MockObject|OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var MockObject|SpingoNotifyStatusMessageResolverInterface
     */
    private $spingoNotifyStatusMessageResolver;

    /**
     * @var MockObject|SpingoNotifyOrderStatusProviderInterface
     */
    private $spingoNotifyOrderStatusProvider;

    /**
     * @var SpingoOrderChangeStatusService
     */
    private $spingoOrderChangeStatusService;

    protected function setUp(): void
    {
        $this->order = $this->createMock(Order::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->orderRepository->expects($this->once())->method('get')->willReturn($this->order);
        $this->spingoNotifyStatusMessageResolver = $this->createMock(SpingoNotifyStatusMessageResolverInterface::class);
        $this->spingoNotifyOrderStatusProvider = $this->createMock(SpingoNotifyOrderStatusProviderInterface::class);
        $this->spingoOrderChangeStatusService = new SpingoOrderChangeStatusService(
            $this->orderRepository,
            $this->spingoNotifyStatusMessageResolver,
            $this->spingoNotifyOrderStatusProvider
        );
    }

    public function testExecuteProcess(): void
    {
        $this->order->expects($this->once())->method('isCanceled')->willReturn(false);
        $this->order->expects($this->once())->method('addCommentToStatusHistory');
        $this->order->expects($this->exactly(2))->method('getTotalDue')->willReturn(100);
        $this->order->expects($this->once())->method('setTotalPaid');
        $this->order->expects($this->once())->method('setBaseTotalPaid');
        $this->order->expects($this->once())->method('setStatus');
        $this->order->expects($this->once())->method('setState');
        $this->order->expects($this->never())->method('cancel');
        $this->orderRepository->expects($this->once())->method('save');
        $this->spingoNotifyStatusMessageResolver->expects($this->once())->method('resolve');
        $this->spingoNotifyOrderStatusProvider->expects($this->once())->method('provide')->willReturn(
            Order::STATE_PROCESSING
        );
        $this->spingoOrderChangeStatusService->execute(1, '200');
    }

    public function testExecuteCancel(): void
    {
        $this->order->expects($this->once())->method('isCanceled')->willReturn(false);
        $this->order->expects($this->once())->method('addCommentToStatusHistory');
        $this->order->expects($this->once())->method('getTotalDue')->willReturn(100);
        $this->order->expects($this->never())->method('setTotalPaid');
        $this->order->expects($this->never())->method('setBaseTotalPaid');
        $this->order->expects($this->once())->method('setStatus');
        $this->order->expects($this->once())->method('setState');
        $this->order->expects($this->once())->method('cancel');
        $this->orderRepository->expects($this->once())->method('save');
        $this->spingoNotifyStatusMessageResolver->expects($this->once())->method('resolve');
        $this->spingoNotifyOrderStatusProvider->expects($this->once())->method('provide')->willReturn(
            Order::STATE_CANCELED
        );
        $this->spingoOrderChangeStatusService->execute(1, '401');
    }

    public function testExecuteOrderWasCanceled(): void
    {
        $this->order->expects($this->once())->method('isCanceled')->willReturn(true);
        $this->order->expects($this->never())->method('addCommentToStatusHistory');
        $this->order->expects($this->never())->method('getTotalDue')->willReturn(100);
        $this->order->expects($this->never())->method('setTotalPaid');
        $this->order->expects($this->never())->method('setBaseTotalPaid');
        $this->order->expects($this->never())->method('setStatus');
        $this->order->expects($this->never())->method('setState');
        $this->order->expects($this->never())->method('cancel');
        $this->orderRepository->expects($this->never())->method('save');
        $this->spingoNotifyStatusMessageResolver->expects($this->never())->method('resolve');
        $this->spingoNotifyOrderStatusProvider->expects($this->never())->method('provide')->willReturn(
            Order::STATE_CANCELED
        );
        $this->spingoOrderChangeStatusService->execute(1, '200');
    }
}
