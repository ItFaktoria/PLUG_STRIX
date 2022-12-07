<?php

declare(strict_types=1);

namespace Faktoria\FactoriaAdminUi\Provider;

use Faktoria\FaktoriaApi\Api\FaktoriaConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class FaktoriaConfigProvider implements FaktoriaConfigProviderInterface
{
    private const IS_SANDBOX = 'faktoria/general/is_sandbox';
    private const API_KEY = 'faktoria/general/api_key';
    private const MERCHANT_ID = 'faktoria/general/merchant_id';
    private const CONTRACT_ID = 'faktoria/general/contract_id';

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
}
