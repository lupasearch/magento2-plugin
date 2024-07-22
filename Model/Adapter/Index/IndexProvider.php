<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Adapter\Index;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Search\Request\Dimension;
use Magento\Framework\Search\RequestInterface;
use Magento\Search\Model\Query;
use Magento\Search\Model\QueryInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreDimensionProvider;

class IndexProvider implements IndexProviderInterface
{
    private ScopeConfigInterface $scopeConfig;

    private string $configPath;

    private string $suggestionConfigPath;

    public function __construct(
        ScopeConfigInterface $config,
        string $configPath = '',
        string $suggestionConfigPath = ''
    ) {
        $this->scopeConfig = $config;
        $this->configPath = $configPath;
        $this->suggestionConfigPath = $suggestionConfigPath;
    }

    public function getId(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue($this->configPath, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getIdByRequest(RequestInterface $request): string
    {
        return $this->getId($this->getStoreId($request));
    }

    public function getSuggestionId(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue($this->suggestionConfigPath, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getSuggestionIdByQuery(QueryInterface $query): string
    {
        return $this->getSuggestionId($query instanceof Query ? (int)$query->getStoreId() : Store::DISTRO_STORE_ID);
    }

    private function getStoreId(RequestInterface $request): int
    {
        $scope = $request->getDimensions()[StoreDimensionProvider::DIMENSION_NAME] ?? null;

        return $scope instanceof Dimension ? (int)$scope->getValue() : 0;
    }
}
