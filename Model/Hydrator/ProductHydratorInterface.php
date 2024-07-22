<?php

namespace LupaSearch\LupaSearchPlugin\Model\Hydrator;

use Magento\Catalog\Model\Product;

interface ProductHydratorInterface
{
    public const ATTRIBUTE_PREFIX = 'attr_';

    /**
     * @return array<string|int|float|array<string>>
     */
    public function extract(Product $product): array;
}
