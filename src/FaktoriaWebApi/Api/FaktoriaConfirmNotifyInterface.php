<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaWebApi\Api;

interface FaktoriaConfirmNotifyInterface
{
    /**
     * @param string $idNotify
     * @param \Faktoria\FaktoriaWebApi\Api\Data\FaktoriaConfirmNotifyItemInterface[] $items
     * @return string
     */
    public function confirm(string $idNotify, array $items): string;
}
