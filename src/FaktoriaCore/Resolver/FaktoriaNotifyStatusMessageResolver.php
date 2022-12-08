<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Resolver;

use Faktoria\FaktoriaApi\Api\FaktoriaNotifyStatusMessageResolverInterface;

class FaktoriaNotifyStatusMessageResolver implements FaktoriaNotifyStatusMessageResolverInterface
{
    public function resolve(string $statusCode): string
    {
        switch ($statusCode) {
            case '200':
                return (string)__('FAKTORIA - [%1] Positive decision.', $statusCode);
            case '400':
                return (string)__('FAKTORIA - [%1] Application terminated due to technical errors.', $statusCode);
            case '401':
                return (string)__('FAKTORIA - [%1] Refusal of funding.', $statusCode);
            case '501':
                return (string)__('FAKTORIA - [%1] Client\'s resignation.', $statusCode);
            case '502':
                return (string)__('FAKTORIA - [%1] Session timed out.', $statusCode);
            default:
                return (string)__('FAKTORIA - [%1] Unsupported status code.', $statusCode);
        }
    }
}
