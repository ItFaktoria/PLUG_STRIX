<?php

declare(strict_types=1);

namespace Spingo\SpingoWebApi\Api;

interface SpingoConfirmNotifyInterface
{
    /**
     * @param string $IdNotify
     * @param \Spingo\SpingoWebApi\Api\Data\SpingoConfirmNotifyItemInterface[] $Items
     * @return string
     */
    public function confirm(string $IdNotify, array $Items): string;
}
