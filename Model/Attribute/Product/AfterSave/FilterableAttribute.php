<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Attribute\Product\AfterSave;

use LupaSearch\LupaSearchPlugin\Model\Attribute\Product\AttributeAfterSaveAbstract;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class FilterableAttribute extends AttributeAfterSaveAbstract
{
    public function process(Attribute $attribute): void
    {
        $originalFilterable = (int)$attribute->getOrigData(Attribute::IS_FILTERABLE);
        $originalFilterableInSearch = (int)$attribute->getOrigData(Attribute::IS_FILTERABLE_IN_SEARCH);

        if (
            $originalFilterable === (int)$attribute->getIsFilterable() &&
            $originalFilterableInSearch === (int)$attribute->getIsFilterableInSearch()
        ) {
            return;
        }

        if ($attribute->getIsFilterable() || $attribute->getIsFilterableInSearch()) {
            $this->reindex($attribute);
        }

        $this->generateQueries();
    }
}
