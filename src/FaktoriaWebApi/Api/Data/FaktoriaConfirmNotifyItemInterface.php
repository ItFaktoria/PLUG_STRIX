<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaWebApi\Api\Data;

interface FaktoriaConfirmNotifyItemInterface
{
    /**
     * @return string
     */
    public function getSymbol(): string;

    /**
     * @param string $symbol
     * @return void
     */
    public function setSymbol(string $symbol): void;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void;
}
