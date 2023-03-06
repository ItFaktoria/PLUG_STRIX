<?php

declare(strict_types=1);

namespace Spingo\SpingoWebApi\Api;

interface SpingoConfirmNotifyInterface
{
    /**
     * @param string $idNotify
     * @param \Spingo\SpingoWebApi\Api\Data\SpingoConfirmNotifyItemInterface[] $items
     * @return string
     */
    public function confirm(string $idNotify, array $items): string;
}
