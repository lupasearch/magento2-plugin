<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

interface AttributesProviderInterface
{
    /**
     * @return array<Attribute|string>
     */
    public function getList(): array;

    public function getAttributeId(Attribute $attribute): string;
}
