<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Traversable;

interface DataModifierInterface
{
    /**
     * @param Traversable<Category|Product> $data
     */
    public function modify(Traversable $data): void;
}
