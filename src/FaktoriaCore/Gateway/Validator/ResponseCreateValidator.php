<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Gateway\Validator;

use InvalidArgumentException;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;

class ResponseCreateValidator extends AbstractValidator
{
    private const VALIDATION_SUBJECT_RESPONSE = 'response';
    
    public function validate(array $validationSubject): ResultInterface
    {
        if (!isset($validationSubject[static::VALIDATION_SUBJECT_RESPONSE]) ||
            !is_array($validationSubject[static::VALIDATION_SUBJECT_RESPONSE])) {
            throw new InvalidArgumentException('Response does not exist');
        }

        $response = $validationSubject[static::VALIDATION_SUBJECT_RESPONSE];
        if ($this->isSuccessfulTransaction($response)) {
            return $this->createResult(true, []);
        }

        return $this->createResult(false, [__('Faktoria rejected the transaction.')]);
    }
    
    private function isSuccessfulTransaction(array $response): bool
    {
        return false;
    }
}
