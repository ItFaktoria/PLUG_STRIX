<?php

declare(strict_types=1);

namespace Spingo\SpingoCore\Test\Unit\Gateway\Validator;

use InvalidArgumentException;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoCore\Gateway\Validator\SpingoApiResponseValidator;

class SpingoApiResponseValidatorTest extends TestCase
{
    /**
     * @var MockObject|ResultInterfaceFactory
     */
    private $resultFactory;

    /**
     * @var SpingoApiResponseValidator
     */
    private $spingoApiResponseValidator;

    protected function setUp(): void
    {
        $this->resultFactory = $this->createMock(ResultInterfaceFactory::class);
        $this->spingoApiResponseValidator = new SpingoApiResponseValidator($this->resultFactory);
    }

    public function testValidate(): void
    {
        $validateResult = $this->createMock(ResultInterface::class);
        $this->resultFactory->expects($this->once())->method('create')->willReturn($validateResult);
        $result = $this->spingoApiResponseValidator->validate(['response' => []]);
        $this->assertEquals($validateResult, $result);
    }

    public function testValidateException(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException());
        $this->expectExceptionMessage('Response does not exist');
        $this->spingoApiResponseValidator->validate([]);
    }
}
