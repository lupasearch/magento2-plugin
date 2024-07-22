<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ProductConfig implements ProductConfigInterface
{
    private const XML_CONFIG_PATH_ENABLE_PRODUCT_CONTENT_HASH_CHECK
        = 'lupasearch/index/product/enable_product_content_hash_check';

    private const XML_CONFIG_PATH_ATTRIBUTE_MAX_PRODUCT_SIZE = 'lupasearch/index/product/attribute_max_product_size';
    private const XML_CONFIG_PATH_INDEX_PRODUCT_ZERO_PRICE = 'lupasearch/index/product/zero_price';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isHashCheckEnabled(int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_CONFIG_PATH_ENABLE_PRODUCT_CONTENT_HASH_CHECK,
            ScopeInterface::SCOPE_STORES,
            $storeId,
        );
    }

    public function getAttributeMaxProductSize(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_CONFIG_PATH_ATTRIBUTE_MAX_PRODUCT_SIZE);
    }

    public function isZeroPriceEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_CONFIG_PATH_INDEX_PRODUCT_ZERO_PRICE);
    }
}
