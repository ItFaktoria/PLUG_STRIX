<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Resolver;

use PHPUnit\Framework\TestCase;
use Spingo\SpingoCore\Resolver\SpingoNotifyStatusMessageResolver;

class SpingoNotifyStatusMessageResolverTest extends TestCase
{
    /**
     * @dataProvider testData
     */
    public function testResolve(string $code, string $message): void
    {
        $spingoNotifyStatusMessageResolver = new SpingoNotifyStatusMessageResolver();
        $result = $spingoNotifyStatusMessageResolver->resolve($code);
        $this->assertEquals($message, $result);
    }

    private function testData(): array
    {
        return [
            [
                '200',
                'SPINGO - [200] Positive decision.'
            ],
            [
                '400',
                'SPINGO - [400] Application terminated due to technical errors.'
            ],
            [
                '401',
                'SPINGO - [401] Refusal of funding.'
            ],
            [
                '501',
                'SPINGO - [501] Client\'s resignation.'
            ],
            [
                '502',
                'SPINGO - [502] Session timed out.'
            ],
            [
                'undefined',
                'SPINGO - [undefined] Unsupported status code.'
            ]
        ];
    }
}
