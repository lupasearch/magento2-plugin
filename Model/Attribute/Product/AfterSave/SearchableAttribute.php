<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Attribute\Product\AfterSave;

use LupaSearch\LupaSearchPlugin\Model\Attribute\Product\AttributeAfterSaveAbstract;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class SearchableAttribute extends AttributeAfterSaveAbstract
{
    public function process(Attribute $attribute): void
    {
        if (
            (int)$attribute->getOrigData(Attribute::IS_SEARCHABLE) === (int)$attribute->getIsSearchable() &&
            (int)$attribute->getOrigData('search_weight') === (int)$attribute->getSearchWeight()
        ) {
            return;
        }

        if (1 === (int)$attribute->getIsSearchable() && 0 === (int)$attribute->getOrigData('search_weight')) {
            $this->reindex($attribute);
        }

        $this->generateQueries();
    }
}
