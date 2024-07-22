<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use Magento\Catalog\Model\Product;
use Magento\Directory\Model\PriceCurrency;

class Price implements ProductHydratorInterface
{
    /**
     * @var PriceCurrency
     */
    private $priceCurrency;

    public function __construct(PriceCurrency $priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $data = [];
        $data['price'] = $this->round((float)$product->getFinalPrice());
        $data['old_price'] = $this->round((float)$product->getPrice());
        $data['discount'] = $this->getDiscount($product);
        $data['discount_percent'] = $this->getDiscountPercent($product);

        return $data;
    }

    protected function getDiscountPercent(Product $product): float
    {
        if (
            $product->getFinalPrice() <= 0 ||
            $product->getPrice() <= 0 ||
            $product->getFinalPrice() >= $product->getPrice()
        ) {
            return 0.00;
        }

        $discount = 100 - $product->getFinalPrice() * 100 / $product->getPrice();
        $discount = round($discount);

        if ($discount < 10) {
            return 0.00;
        }

        return $discount;
    }

    protected function getDiscount(Product $product): float
    {
        if (
            $product->getFinalPrice() <= 0 ||
            $product->getPrice() <= 0 ||
            $product->getFinalPrice() >= $product->getPrice()
        ) {
            return 0.00;
        }

        return $this->round(abs($product->getFinalPrice() - $product->getPrice())) * -1;
    }

    protected function round(float $price): float
    {
        return $this->priceCurrency->roundPrice($price);
    }
}
