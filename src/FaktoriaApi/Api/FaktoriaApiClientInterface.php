<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaApi\Api;

use Faktoria\FaktoriaCore\Exception\FaktoriaApiException;

interface FaktoriaApiClientInterface
{
    /**
     * @throws FaktoriaApiException
     */
    public function submit(array $params): string;
}
