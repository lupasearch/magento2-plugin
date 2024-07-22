<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Traversable;

interface ProductsProviderInterface extends DataProviderInterface
{
    /**
     * @return int[]
     */
    public function getAllIds(int $storeId): array;

    /**
     * @param Attribute $attribute
     * @return int[]
     */
    public function getAllIdsByAttribute(Attribute $attribute): array;

    /**
     * @param int[] $ids
     */
    public function getByIds(array $ids, int $storeId): Traversable;
}
