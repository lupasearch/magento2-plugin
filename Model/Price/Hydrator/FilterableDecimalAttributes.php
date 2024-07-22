<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Price\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\AttributeValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\FilterableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\FilterableDecimalAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Validator\AttributeValidatorInterface;
use Magento\Catalog\Model\Product;

class FilterableDecimalAttributes implements ProductHydratorInterface
{
    private FilterableAttributesProviderInterface $filterableAttributesProvider;

    private AttributeValueProviderInterface $attributeValueProvider;

    private FilterableDecimalAttributesProviderInterface $filterableDecimalAttributesProvider;

    private AttributeValidatorInterface $attributeValidator;

    public function __construct(
        FilterableAttributesProviderInterface $filterableAttributesProvider,
        AttributeValueProviderInterface $attributeValueProvider,
        FilterableDecimalAttributesProviderInterface $filterableDecimalAttributesProvider,
        AttributeValidatorInterface $attributeValidator
    ) {
        $this->filterableAttributesProvider = $filterableAttributesProvider;
        $this->attributeValueProvider = $attributeValueProvider;
        $this->filterableDecimalAttributesProvider = $filterableDecimalAttributesProvider;
        $this->attributeValidator = $attributeValidator;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $values = [];

        $attributes = $this->filterableDecimalAttributesProvider->getByAttributeSetId(
            (int)$product->getAttributeSetId(),
        );

        foreach ($attributes as $attribute) {
            if (!$this->attributeValidator->validate($attribute)) {
                continue;
            }

            $value = $this->attributeValueProvider->getValue($product, $attribute);

            if (null === $value) {
                continue;
            }

            $values[$this->filterableAttributesProvider->getAttributeId($attribute)] = $value;
        }

        return $values;
    }
}
