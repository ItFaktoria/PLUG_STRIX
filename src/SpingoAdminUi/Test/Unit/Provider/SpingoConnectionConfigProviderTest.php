<?php

declare(strict_types=1);

namespace Spingo\SpingoAdminUi\Test\Unit\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Spingo\SpingoAdminUi\Provider\SpingoCartConfigProvider;
use Spingo\SpingoAdminUi\Provider\SpingoConnectionConfigProvider;

class SpingoConnectionConfigProviderTest extends TestCase
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
     * @var SpingoConnectionConfigProvider
     */
    private $spingoConnectionConfigProvider;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->encryptor = $this->createMock(EncryptorInterface::class);
        $this->spingoConnectionConfigProvider = $objectManager->getObject(
            SpingoConnectionConfigProvider::class,
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
        $isActive = $this->spingoConnectionConfigProvider->isActive(1);
        $this->assertIsBool($isActive);
    }

    public function testIsSandbox(): void
    {
        $this->scopeConfig->expects($this->once())->method('isSetFlag')->willReturn(true);
        $this->encryptor->expects($this->never())->method('decrypt');
        $isSandbox = $this->spingoConnectionConfigProvider->isSandbox(1);
        $this->assertIsBool($isSandbox);
    }

    public function isSendNotification(): void
    {
        $this->scopeConfig->expects($this->once())->method('isSetFlag')->willReturn(true);
        $this->encryptor->expects($this->never())->method('decrypt');
        $isSendNotification = $this->spingoConnectionConfigProvider->isSendNotification(1);
        $this->assertIsBool($isSendNotification);
    }

    public function testGetApiKey(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->once())->method('decrypt')->willReturn('1');
        $apiKey = $this->spingoConnectionConfigProvider->getApiKey(1);
        $this->assertIsString($apiKey);
    }

    public function testGetSandboxApiKey(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->once())->method('decrypt')->willReturn('1');
        $apiSandboxKey = $this->spingoConnectionConfigProvider->getSandboxApiKey(1);
        $this->assertIsString($apiSandboxKey);
    }

    public function testGetMerchantId(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $merchantId = $this->spingoConnectionConfigProvider->getMerchantId(1);
        $this->assertIsString($merchantId);
    }

    public function testGetContractId(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $contractId = $this->spingoConnectionConfigProvider->getContractId(1);
        $this->assertIsString($contractId);
    }

    public function testGetReturnUrl(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $returnUrl = $this->spingoConnectionConfigProvider->getReturnUrl(1);
        $this->assertIsString($returnUrl);
    }

    public function testGetCancelUrl(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $cancelUrl = $this->spingoConnectionConfigProvider->getCancelUrl(1);
        $this->assertIsString($cancelUrl);
    }
    public function testIsLogCartRequest(): void
    {
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn('1');
        $this->encryptor->expects($this->never())->method('decrypt');
        $isLogCartRequest = $this->spingoConnectionConfigProvider->isLogCartRequest(1);
        $this->assertIsBool($isLogCartRequest);
    }
}
