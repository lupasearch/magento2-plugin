<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class HashCheckConfig implements HashCheckConfigInterface
{
    private ScopeConfigInterface $scopeConfig;

    private string $xmlConfigPath;

    public function __construct(ScopeConfigInterface $scopeConfig, string $xmlConfigPath)
    {
        $this->scopeConfig = $scopeConfig;
        $this->xmlConfigPath = $xmlConfigPath;
    }

    public function isHashCheckEnabled(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(
            $this->xmlConfigPath,
            ScopeInterface::SCOPE_STORES,
            $storeId,
        );
    }
}