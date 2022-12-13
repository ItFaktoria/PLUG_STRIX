<?php

declare(strict_types=1);

namespace Spingo\SpingoAdminUi\Test\Unit\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoAdminUi\Provider\SpingoCartConfigProvider;

class SpingoCartConfigProviderTest extends TestCase
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var SpingoCartConfigProvider
     */
    private $spingoCartConfigProvider;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->spingoCartConfigProvider = $objectManager->getObject(
            SpingoCartConfigProvider::class,
            [
                'scopeConfig'=> $this->scopeConfig
            ]
        );
    }

    public function testGetMinOrderTotal(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('100');
        $minOrderTotal = $this->spingoCartConfigProvider->getMinOrderTotal(1);
        $this->assertIsFloat($minOrderTotal);
        $this->assertEquals(100, $minOrderTotal);
    }

    public function testGeMaxOrderTotal(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('100');
        $maxOrderTotal = $this->spingoCartConfigProvider->getMaxOrderTotal(1);
        $this->assertIsFloat($maxOrderTotal);
        $this->assertEquals(100, $maxOrderTotal);
    }
}
