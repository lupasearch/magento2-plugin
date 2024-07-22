<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Price\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\AttributeValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableDecimalAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Validator\AttributeValidatorInterface;
use Magento\Catalog\Model\Product;

class SearchableDecimalAttributes implements ProductHydratorInterface
{
    private AttributeValueProviderInterface $attributeValueProvider;

    private SearchableDecimalAttributesProviderInterface $searchableDecimalAttributesProvider;

    private AttributeValidatorInterface $attributeValidator;

    public function __construct(
        AttributeValueProviderInterface $attributeValueProvider,
        SearchableDecimalAttributesProviderInterface $searchableDecimalAttributesProvider,
        AttributeValidatorInterface $attributeValidator
    ) {
        $this->attributeValueProvider = $attributeValueProvider;
        $this->searchableDecimalAttributesProvider = $searchableDecimalAttributesProvider;
        $this->attributeValidator = $attributeValidator;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        $values = [];

        $attributes = $this->searchableDecimalAttributesProvider->getByAttributeSetId(
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

            $values[SearchableAttributesProviderInterface::ATTRIBUTE_PREFIX . $attribute->getAttributeId()] = $value;
        }

        return $values;
    }
}
