<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaWebApi\Service;

use Faktoria\FaktoriaApi\Api\FaktoriaOrderChangeStatusServiceInterface;
use Faktoria\FaktoriaWebApi\Api\FaktoriaConfirmNotifyInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;

class FaktoriaConfirmNotify implements FaktoriaConfirmNotifyInterface
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
     * @var FaktoriaOrderChangeStatusServiceInterface
     */
    private $faktoriaOrderChangeStatusService;

    public function __construct(
        OrderPaymentRepositoryInterface $orderPaymentRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FaktoriaOrderChangeStatusServiceInterface $faktoriaOrderChangeStatusService
    ) {
        $this->orderPaymentRepository = $orderPaymentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->faktoriaOrderChangeStatusService = $faktoriaOrderChangeStatusService;
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
        $this->faktoriaOrderChangeStatusService->execute((int)$payment->getParentId(), $statusCode);

        return json_encode(['code' => 1, 'description' => 'OK']);
    }
}
