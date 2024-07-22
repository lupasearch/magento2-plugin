<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class IndexConfig implements IndexConfigInterface
{
    private const XML_CONFIG_PATH_ENABLED = 'lupasearch/index/enabled';
    private const XML_CONFIG_PATH_BATCH_SIZE = 'lupasearch/index/batch_size';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_ENABLED, ScopeInterface::SCOPE_STORES, $storeId);
    }

    public function getBatchSize(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_CONFIG_PATH_BATCH_SIZE);
    }
}
