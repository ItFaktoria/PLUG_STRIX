<?php

declare(strict_types=1);

namespace Spingo\SpingoAdminUi\Provider;

use Spingo\SpingoApi\Api\SpingoConnectionConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class SpingoConnectionConfigProvider implements SpingoConnectionConfigProviderInterface
{
    private const IS_ACTIVE = 'spingo_payment/general/is_active';
    private const IS_SANDBOX = 'spingo_payment/connection/is_sandbox';
    private const IS_SEND_NOTIFICATION = 'spingo_payment/connection/is_send_notification';
    private const API_KEY = 'spingo_payment/connection/api_key';
    private const SANDBOX_API_KEY = 'spingo_payment/connection/sandbox_api_key';
    private const MERCHANT_ID = 'spingo_payment/connection/merchant_id';
    private const CONTRACT_ID = 'spingo_payment/connection/contract_id';
    private const RETURN_URL = 'spingo_payment/connection/return_url';
    private const CANCEL_URL = 'spingo_payment/connection/cancel_url';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    public function __construct(ScopeConfigInterface $scopeConfig, EncryptorInterface $encryptor)
    {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    public function isActive(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(self::IS_ACTIVE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $storeId);
    }

    public function isSandbox(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(self::IS_SANDBOX, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $storeId);
    }

    public function isSendNotification(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(self::IS_SEND_NOTIFICATION, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $storeId);
    }

    public function getApiKey(int $storeId): string
    {
        return $this->encryptor->decrypt(
            (string)$this->scopeConfig->getValue(self::API_KEY, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $storeId)
        );
    }

    public function getSandboxApiKey(int $storeId): string
    {
        return $this->encryptor->decrypt(
            (string)$this->scopeConfig->getValue(
                self::SANDBOX_API_KEY,
                ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                $storeId
            )
        );
    }

    public function getMerchantId(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::MERCHANT_ID,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }

    public function getContractId(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CONTRACT_ID,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }
    
    public function getReturnUrl(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::RETURN_URL,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }
    
    public function getCancelUrl(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CANCEL_URL,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $storeId
        );
    }
}
