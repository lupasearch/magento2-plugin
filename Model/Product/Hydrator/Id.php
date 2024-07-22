<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use Magento\Catalog\Model\Product;

class Id implements ProductHydratorInterface
{
    /**
     * @phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification
     */
    public function extract(Product $product): array
    {
        $data = [];
        $data['id'] = (int)$product->getId();

        return $data;
    }
}
