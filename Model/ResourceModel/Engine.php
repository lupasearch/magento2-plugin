<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Magento\Catalog\Model\Product\Visibility;
use Magento\CatalogSearch\Model\ResourceModel\EngineInterface;
use Magento\Framework\Indexer\ScopeResolver\IndexScopeResolver;

use function implode;
use function is_array;

class Engine implements EngineInterface
{
    private Visibility $catalogProductVisibility;

    private IndexScopeResolver $indexScopeResolver;

    public function __construct(Visibility $catalogProductVisibility, IndexScopeResolver $indexScopeResolver)
    {
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->indexScopeResolver = $indexScopeResolver;
    }

    public function isAvailable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getAllowedVisibility(): array
    {
        return $this->catalogProductVisibility->getVisibleInSiteIds();
    }

    public function allowAdvancedIndex(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function processAttributeValue($attribute, $value)
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function prepareEntityIndex($index, $separator = ' '): array
    {
        $indexData = [];

        foreach ($index as $attributeId => $value) {
            $indexData[$attributeId] = is_array($value) ? implode($separator, $value) : $value;
        }

        return $indexData;
    }
}
