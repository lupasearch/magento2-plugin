<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Conditions;

use LupaSearch\LupaSearchPlugin\Model\Config\Index\ProductConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;

class ZeroPrice implements CollectionModifierInterface
{
    /**
     * @var ProductConfigInterface
     */
    private $productConfig;

    public function __construct(ProductConfigInterface $productConfig)
    {
        $this->productConfig = $productConfig;
    }

    public function apply(AbstractDb $collection): void
    {
        if ($this->productConfig->isZeroPriceEnabled()) {
            return;
        }

        $collection->getSelect()->where('price_index.min_price > ?', 0.00001);
    }
}
