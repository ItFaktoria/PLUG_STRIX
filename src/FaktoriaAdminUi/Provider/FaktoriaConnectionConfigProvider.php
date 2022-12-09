<?php

declare(strict_types=1);

namespace Faktoria\FaktoriaAdminUi\Provider;

use Faktoria\FaktoriaApi\Api\FaktoriaConnectionConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class FaktoriaConnectionConfigProvider implements FaktoriaConnectionConfigProviderInterface
{
    private const IS_ACTIVE = 'faktoria_payment/general/is_active';
    private const IS_SANDBOX = 'faktoria_payment/connection/is_sandbox';
    private const API_KEY = 'faktoria_payment/connection/api_key';
    private const SANDBOX_API_KEY = 'faktoria_payment/connection/sandbox_api_key';
    private const MERCHANT_ID = 'faktoria_payment/connection/merchant_id';
    private const CONTRACT_ID = 'faktoria_payment/connection/contract_id';
    private const RETURN_URL = 'faktoria_payment/connection/return_url';
    private const CANCEL_URL = 'faktoria_payment/connection/cancel_url';

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