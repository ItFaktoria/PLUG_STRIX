<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaCore\Gateway\Validator;

use InvalidArgumentException;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;

class FaktoriaApiResponseValidator extends AbstractValidator
{
    public function validate(array $validationSubject): ResultInterface
    {
        if (!isset($validationSubject['response']) || !is_array($validationSubject['response'])) {
            throw new InvalidArgumentException('Response does not exist');
        }
        $response = $validationSubject['response'];
        if ($this->isSuccessfulTransaction($response)) {
            return $this->createResult(true, []);
        }

        return $this->createResult(false, [__('Faktoria rejected the transaction.')]);
    }

    private function isSuccessfulTransaction(array $response): bool
    {
        return array_key_exists('applicationNumber', $response) && array_key_exists('fieldInfos', $response) &&
            isset($response['fieldInfos'][0]);
    }
}
