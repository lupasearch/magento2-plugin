<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config;

use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

class QueriesConfig implements QueriesConfigInterface
{
    private const XML_CONFIG_PATH_QUERY = 'lupasearch/queries/';

    private const DELIMITER = ',';

    private ScopeConfigInterface $scopeConfig;

    private WriterInterface $configWriter;

    private ReinitableConfigInterface $reinitableConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        ReinitableConfigInterface $reinitableConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->reinitableConfig = $reinitableConfig;
    }

    public function getProduct(?int $scopeCode = 0): ?string
    {
        return $this->getStoreConfig($this->getQueryConfigPath('product/key'), $scopeCode);
    }

    public function getProductCatalog(?int $scopeCode = 0): ?string
    {
        return $this->getStoreConfig($this->getQueryConfigPath('product/catalog_key'), $scopeCode);
    }

    public function getProductSearchBox(?int $scopeCode = 0): ?string
    {
        return $this->getStoreConfig($this->getQueryConfigPath('product/search_box_key'), $scopeCode);
    }

    public function getProductSuggestion(?int $scopeCode = 0): ?string
    {
        return $this->getStoreConfig($this->getQueryConfigPath('product/suggest_key'), $scopeCode);
    }

    public function getCategory(?int $scopeCode = 0): ?string
    {
        return $this->getStoreConfig($this->getQueryConfigPath('category'), $scopeCode);
    }

    public function setProduct(string $query, ?int $scopeId = 0): void
    {
        $this->saveStoreConfig($query, $this->getQueryConfigPath('product/key'), $scopeId);
    }

    public function setProductCatalog(string $query, ?int $scopeId = 0): void
    {
        $this->saveStoreConfig($query, $this->getQueryConfigPath('product/catalog_key'), $scopeId);
    }

    public function setProductSearchBox(string $query, ?int $scopeId = 0): void
    {
        $this->saveStoreConfig($query, $this->getQueryConfigPath('product/search_box_key'), $scopeId);
    }

    public function setProductSuggestion(string $query, ?int $scopeId = 0): void
    {
        $this->saveStoreConfig($query, $this->getQueryConfigPath('product/suggest_key'), $scopeId);
    }

    public function setCategory(string $query, ?int $scopeId = 0): void
    {
        $this->saveStoreConfig($query, $this->getQueryConfigPath('category'), $scopeId);
    }

    public function getBoostFunctionCoefficient(?int $scopeCode = 0): float
    {
        return (float)$this->scopeConfig->getValue(
            $this->getQueryConfigPath('boost_function_coefficient'),
            ScopeInterface::SCOPE_STORES,
            $scopeCode,
        );
    }

    private function getStoreConfig(string $path, int $scopeCode): ?string
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORES, $scopeCode) ?? null;
    }

    private function saveStoreConfig(string $value, string $path, int $scopeId): void
    {
        if ($this->getStoreConfig($path, $scopeId) === $value) {
            return;
        }

        $this->configWriter->save($path, $value, ScopeInterface::SCOPE_STORES, $scopeId);
        $this->reinitableConfig->reinit();
    }

    private function getQueryConfigPath(string $type): string
    {
        return self::XML_CONFIG_PATH_QUERY . $type;
    }
}
