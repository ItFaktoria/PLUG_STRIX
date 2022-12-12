<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaAdminUi\Test\Unit\Provider;

use Faktoria\FaktoriaAdminUi\Provider\FaktoriaCartConfigProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class FaktoriaCartConfigProviderTest extends TestCase
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var FaktoriaCartConfigProvider
     */
    private $faktoriaCartConfigProvider;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->faktoriaCartConfigProvider = $objectManager->getObject(
            FaktoriaCartConfigProvider::class,
            [
                'scopeConfig'=> $this->scopeConfig
            ]
        );
    }

    public function testGetMinOrderTotal(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('100');
        $minOrderTotal = $this->faktoriaCartConfigProvider->getMinOrderTotal(1);
        $this->assertIsFloat($minOrderTotal);
        $this->assertEquals(100, $minOrderTotal);
    }

    public function testGeMaxOrderTotal(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('100');
        $maxOrderTotal = $this->faktoriaCartConfigProvider->getMaxOrderTotal(1);
        $this->assertIsFloat($maxOrderTotal);
        $this->assertEquals(100, $maxOrderTotal);
    }
}
