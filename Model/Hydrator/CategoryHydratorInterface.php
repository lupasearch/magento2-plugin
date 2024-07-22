<?php

namespace LupaSearch\LupaSearchPlugin\Model\Hydrator;

use Magento\Catalog\Api\Data\CategoryInterface;

interface CategoryHydratorInterface
{
    /**
     * @param CategoryInterface $category
     * @return array<string|int|float|array<string>>
     */
    public function extract(CategoryInterface $category): array;
}
