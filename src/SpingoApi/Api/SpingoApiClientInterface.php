<?php

declare(strict_types=1);

namespace Spingo\SpingoApi\Api;

use Spingo\SpingoCore\Exception\SpingoApiException;

interface SpingoApiClientInterface
{
    /**
     * @throws SpingoApiException
     */
    public function submit(array $params): string;
}
