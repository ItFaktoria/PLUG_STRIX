<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Resolver;

use Spingo\SpingoApi\Api\SpingoNotifyStatusMessageResolverInterface;

class SpingoNotifyStatusMessageResolver implements SpingoNotifyStatusMessageResolverInterface
{
    public function resolve(string $statusCode): string
    {
        $message = '';
        switch ($statusCode) {
            case '200':
                $message = (string)__('SPINGO - [%1] Positive decision.', $statusCode);
                break;
            case '400':
                $message = (string)__('SPINGO - [%1] Application terminated due to technical errors.', $statusCode);
                break;
            case '401':
                $message = (string)__('SPINGO - [%1] Refusal of funding.', $statusCode);
                break;
            case '501':
                $message = (string)__('SPINGO - [%1] Client\'s resignation.', $statusCode);
                break;
            case '502':
                $message = (string)__('SPINGO - [%1] Session timed out.', $statusCode);
                break;
            default:
                $message = (string)__('SPINGO - [%1] Unsupported status code.', $statusCode);
                break;
        }

        return $message;
    }
}
