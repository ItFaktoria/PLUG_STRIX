<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaWebApi\Model;

use Faktoria\FaktoriaWebApi\Api\Data\FaktoriaConfirmNotifyItemInterface;
use Magento\Framework\DataObject;

class FaktoriaConfirmNotifyItem extends DataObject implements FaktoriaConfirmNotifyItemInterface
{
    public function getSymbol(): string
    {
        return $this->getData('symbol');
    }

    public function setSymbol(string $symbol): void
    {
        $this->setData('symbol', $symbol);
    }
    
    public function getValue(): string
    {
        return $this->getData('value');
    }
    
    public function setValue(string $value): void
    {
        $this->setData('value', $value);
    }
}
