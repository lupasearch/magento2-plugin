<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Attributes;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

interface SortableDecimalAttributesProviderInterface
{
    /**
     * @return Attribute[]
     */
    public function getByAttributeSetId(int $attributeSetId): array;
}
