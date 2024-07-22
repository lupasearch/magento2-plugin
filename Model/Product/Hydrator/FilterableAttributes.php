<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\AttributeValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\FilterableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Validator\AttributeValidatorInterface;
use Magento\Catalog\Model\Product;

class FilterableAttributes implements ProductHydratorInterface
{
    /**
     * @var AttributeValidatorInterface
     */
    private $attributeValidator;

    /**
     * @var FilterableAttributesProviderInterface
     */
    private $filterableAttributesProvider;

    /**
     * @var AttributeValueProviderInterface
     */
    private $attributeValueProvider;

    public function __construct(
        AttributeValidatorInterface $attributeValidator,
        FilterableAttributesProviderInterface $filterableAttributesProvider,
        AttributeValueProviderInterface $attributeValueProvider
    ) {
        $this->attributeValidator = $attributeValidator;
        $this->filterableAttributesProvider = $filterableAttributesProvider;
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

            if (null === $value) {
                continue;
            }

            $values[$this->filterableAttributesProvider->getAttributeId($attribute)] = $value;
        }

        return $values;
    }
}
