<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config\Queries;

use LupaSearch\LupaSearchPlugin\Model\Config\FieldArrayConfigInterface;
use Magento\AdvancedSearch\Model\SuggestedQueriesInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

use function array_map;

class ProductConfig implements ProductConfigInterface
{
    private const XML_CONFIG_GROUP = 'lupasearch/queries/product/';

    private ScopeConfigInterface $scopeConfig;

    private FieldArrayConfigInterface $fieldArrayConfig;

    public function __construct(ScopeConfigInterface $scopeConfig, FieldArrayConfigInterface $fieldArrayConfig)
    {
        $this->scopeConfig = $scopeConfig;
        $this->fieldArrayConfig = $fieldArrayConfig;
    }

    public function isOutOfStockProductsAtTheEnd(?int $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            $this->getConfigPath('out_of_stock_products_at_the_end'),
            ScopeInterface::SCOPE_STORES,
            $scopeCode,
        );
    }

    /**
     * @inheritDoc
     */
    public function getBoostFields(): array
    {
        return $this->fieldArrayConfig->getColumn(
            $this->getConfigPath('boost_fields'),
            'attribute_code',
        );
    }

    /**
     * @inheritDoc
     */
    public function getBoostFieldCoefficients(): array
    {
        return array_map('intval', $this->fieldArrayConfig->getPairs(
            $this->getConfigPath('boost_fields'),
            'coefficient',
            'attribute_code',
            0,
        ));
    }

    public function getCategoriesSearchWeight(): int
    {
        return (int)$this->scopeConfig->getValue(
            $this->getConfigPath('categories_search_weight'),
        );
    }

    public function getSearchSuggestionCount(?int $scopeCode = null): int
    {
        return (int)$this->scopeConfig->getValue(
            SuggestedQueriesInterface::SEARCH_SUGGESTION_COUNT,
            ScopeInterface::SCOPE_STORES,
            $scopeCode,
        );
    }

    public function isResultsCountForEachSuggestionEnabled(?int $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            SuggestedQueriesInterface::SEARCH_SUGGESTION_COUNT_RESULTS_ENABLED,
            ScopeInterface::SCOPE_STORES,
            $scopeCode,
        );
    }

    public function isSearchSuggestionsEnabled(?int $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            SuggestedQueriesInterface::SEARCH_SUGGESTION_ENABLED,
            ScopeInterface::SCOPE_STORES,
            $scopeCode,
        );
    }

    private function getConfigPath(string $type): string
    {
        return self::XML_CONFIG_GROUP . $type;
    }
}
