<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaFrontendUi\Provider;

use Magento\Checkout\Model\ConfigProviderInterface;

class FaktoriaCheckoutConfigProvider implements ConfigProviderInterface
{
    public function getConfig()
    {
        return [
            'payment' => [
                'faktoria_payment' => [
                    'isActive' => true
                ]
            ]
        ];
    }
}
