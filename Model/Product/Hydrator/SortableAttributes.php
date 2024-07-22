<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\AttributeValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Validator\AttributeValidatorInterface;
use Magento\Catalog\Model\Product;

class SortableAttributes implements ProductHydratorInterface
{
    private AttributeValidatorInterface $attributeValidator;

    private AttributeValueProviderInterface $attributeValueProvider;

    public function __construct(
        AttributeValidatorInterface $attributeValidator,
        AttributeValueProviderInterface $attributeValueProvider
    ) {
        $this->attributeValidator = $attributeValidator;
        $this->attributeValueProvider = $attributeValueProvider;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $values = [];
        $attributes = $product->getAttributes();

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
