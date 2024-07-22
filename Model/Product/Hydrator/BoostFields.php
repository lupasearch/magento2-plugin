<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\BoostAttributesProviderInterface;
use Magento\Catalog\Model\Product;

class BoostFields implements ProductHydratorInterface
{
    private const DEFAULT_BOOST = 0;

    /**
     * @var BoostAttributesProviderInterface
     */
    private $boostAttributesProvider;

    public function __construct(BoostAttributesProviderInterface $boostAttributesProvider)
    {
        $this->boostAttributesProvider = $boostAttributesProvider;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $result = [];

        foreach ($this->boostAttributesProvider->getList() as $id => $attributeCode) {
            $result[$id] = $this->getValue($product, $attributeCode);
        }

        return $result;
    }

    private function getValue(Product $product, string $attributeCode): float
    {
        return (float)($product->getData($attributeCode) ?? self::DEFAULT_BOOST);
    }
}
