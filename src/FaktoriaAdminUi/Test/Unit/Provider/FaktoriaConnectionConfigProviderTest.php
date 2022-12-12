<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaAdminUi\Test\Unit\Provider;

use Faktoria\FaktoriaAdminUi\Provider\FaktoriaCartConfigProvider;
use Faktoria\FaktoriaAdminUi\Provider\FaktoriaConnectionConfigProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class FaktoriaConnectionConfigProviderTest extends TestCase
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var FaktoriaConnectionConfigProvider
     */
    private $faktoriaConnectionConfigProvider;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->encryptor = $this->createMock(EncryptorInterface::class);
        $this->faktoriaConnectionConfigProvider = $objectManager->getObject(
            FaktoriaConnectionConfigProvider::class,
            [
                'scopeConfig'=> $this->scopeConfig,
                'encryptor' => $this->encryptor
            ]
        );
    }

    public function testIsActive(): void
    {
        $this->scopeConfig->expects($this->once())->method('isSetFlag')->willReturn(true);
        $this->encryptor->expects($this->never())->method('decrypt');
        $isActive = $this->faktoriaConnectionConfigProvider->isActive(1);
        $this->assertIsBool($isActive);
    }

    public function testIsSandbox(): void
    {
        $this->scopeConfig->expects($this->once())->method('isSetFlag')->willReturn(true);
        $this->encryptor->expects($this->never())->method('decrypt');
        $isSanbox = $this->faktoriaConnectionConfigProvider->isSandbox(1);
        $this->assertIsBool($isSanbox);
    }

    public function isSendNotification(): void
    {
        $this->scopeConfig->expects($this->once())->method('isSetFlag')->willReturn(true);
        $this->encryptor->expects($this->never())->method('decrypt');
        $isSendNotification = $this->faktoriaConnectionConfigProvider->isSendNotification(1);
        $this->assertIsBool($isSendNotification);
    }

    public function testGetApiKey(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->once())->method('decrypt')->willReturn('1');
        $apiKey = $this->faktoriaConnectionConfigProvider->getApiKey(1);
        $this->assertIsString($apiKey);
    }

    public function testGetSandboxApiKey(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->once())->method('decrypt')->willReturn('1');
        $apiSandboxKey = $this->faktoriaConnectionConfigProvider->getSandboxApiKey(1);
        $this->assertIsString($apiSandboxKey);
    }

    public function testGetMerchantId(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $merchantId = $this->faktoriaConnectionConfigProvider->getMerchantId(1);
        $this->assertIsString($merchantId);
    }

    public function testGetContractId(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $contractId = $this->faktoriaConnectionConfigProvider->getContractId(1);
        $this->assertIsString($contractId);
    }

    public function testGetReturnUrl(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $returnUrl = $this->faktoriaConnectionConfigProvider->getReturnUrl(1);
        $this->assertIsString($returnUrl);
    }

    public function testGetCancelUrl(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $cancelUrl = $this->faktoriaConnectionConfigProvider->getCancelUrl(1);
        $this->assertIsString($cancelUrl);
    }
}
