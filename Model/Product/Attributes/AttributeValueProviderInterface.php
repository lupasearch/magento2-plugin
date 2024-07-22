<?php

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

interface AttributeValueProviderInterface
{
    /**
     * @param Product $product
     * @param AbstractAttribute $attribute
     * @return string|float|bool|array<string>|null
     */
    public function getValue(Product $product, AbstractAttribute $attribute);
}
