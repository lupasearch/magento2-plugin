<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilderInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;

class OutOfStock implements SortBuilderInterface
{
    private const FIELD_IN_STOCK = 'in_stock';

    /**
     * @var ProductConfigInterface
     */
    private $productConfig;

    public function __construct(ProductConfigInterface $productConfig)
    {
        $this->productConfig = $productConfig;
    }

    /**
     * @inheritDoc
     */
    public function build(OptionParametersInterface $parameters): array
    {
        if (
            !$this->productConfig->isOutOfStockProductsAtTheEnd() ||
            self::FIELD_IN_STOCK === $parameters->getCode()
        ) {
            return [];
        }

        return ['field' => self::FIELD_IN_STOCK, 'order' => OptionParametersInterface::DESC];
    }
}
