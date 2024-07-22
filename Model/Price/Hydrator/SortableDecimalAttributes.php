<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Price\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\AttributeValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SortableDecimalAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Validator\AttributeValidatorInterface;
use Magento\Catalog\Model\Product;

class SortableDecimalAttributes implements ProductHydratorInterface
{
    private AttributeValidatorInterface $attributeValidator;

    private AttributeValueProviderInterface $attributeValueProvider;

    private SortableDecimalAttributesProviderInterface $sortableDecimalAttributesProvider;

    public function __construct(
        AttributeValidatorInterface $attributeValidator,
        AttributeValueProviderInterface $attributeValueProvider,
        SortableDecimalAttributesProviderInterface $sortableDecimalAttributesProvider
    ) {
        $this->attributeValidator = $attributeValidator;
        $this->attributeValueProvider = $attributeValueProvider;
        $this->sortableDecimalAttributesProvider = $sortableDecimalAttributesProvider;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $values = [];

        $attributes = $this->sortableDecimalAttributesProvider->getByAttributeSetId(
            (int)$product->getAttributeSetId(),
        );

        foreach ($attributes as $attribute) {
            if (!$this->attributeValidator->validate($attribute)) {
                continue;
            }

            $value = $this->attributeValueProvider->getValue($product, $attribute);

            if (!$value) {
                continue;
            }

            $values[self::ATTRIBUTE_PREFIX . $attribute->getAttributeCode()] = $value;
        }

        return $values;
    }
}
