<?php

declare(strict_types=1);

namespace Spingo\SpingoWebApi\Service;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Spingo\SpingoApi\Api\SpingoOrderChangeStatusServiceInterface;
use Spingo\SpingoWebApi\Api\SpingoConfirmNotifyInterface;

class SpingoConfirmNotify implements SpingoConfirmNotifyInterface
{
    /**
     * @var OrderPaymentRepositoryInterface
     */
    private $orderPaymentRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SpingoOrderChangeStatusServiceInterface
     */
    private $spingoOrderChangeStatusService;

    public function __construct(
        OrderPaymentRepositoryInterface $orderPaymentRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SpingoOrderChangeStatusServiceInterface $spingoOrderChangeStatusService
    ) {
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->spingoOrderChangeStatusService = $spingoOrderChangeStatusService;
    }

    public function confirm(string $idNotify, array $items): string
    {
        $applicationNumber = '';
        $statusCode = '';
        foreach ($items as $item) {
            if ($item->getSymbol() === 'ApplicationNumber') {
                $applicationNumber = $item->getValue();
            }
            if ($item->getSymbol() === 'Status') {
                $statusCode = $item->getValue();
            }
        }
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            'additional_information',
            '%applicationNumber":"' . $applicationNumber . '%',
            'like'
        )->create();
        $payment = current($this->orderPaymentRepository->getList($searchCriteria)->getItems());
        if (!$payment) {
            return json_encode(['code' => 0, 'description' => 'NOK']);
        }
        $this->spingoOrderChangeStatusService->execute((int)$payment->getParentId(), $statusCode);

        return json_encode(['code' => 1, 'description' => 'OK']);
    }
}
