<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Config\DataConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use Magento\Catalog\Model\Product;

class BestSellers implements ProductHydratorInterface
{
    /**
     * @var DataConfigInterface
     */
    private $dataConfig;

    public function __construct(DataConfigInterface $dataConfig)
    {
        $this->dataConfig = $dataConfig;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $data = [];
        $data['sold_qty'] = (int)$product->getSoldQuantity() * $this->dataConfig->getSoldQtyMultiplier();

        return $data;
    }
}
