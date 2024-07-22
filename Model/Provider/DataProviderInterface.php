<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Traversable;

interface DataProviderInterface
{
    /**
     * @param int $storeId
     * @return int[]
     */
    public function getAllIds(int $storeId): array;

    /**
     * @param int[] $ids
     * @param int $storeId
     * @return Traversable<Category|Product>
     */
    public function getByIds(array $ids, int $storeId): Traversable;
}
