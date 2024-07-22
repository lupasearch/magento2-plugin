<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class DataConfig implements DataConfigInterface
{
    private const XML_CONFIG_PATH_SOLD_QTY_MULTIPLIER = 'lupasearch/data/sold_qty_multiplier';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getSoldQtyMultiplier(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_CONFIG_PATH_SOLD_QTY_MULTIPLIER);
    }
}
