<?php

namespace LupaSearch\LupaSearchPlugin\Model\Attribute\Product;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

interface AttributeAfterSaveInterface
{
    public function process(Attribute $attribute): void;
}
