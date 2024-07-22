<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\AttributeValueProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\ContainsProductCodeAttributeMapInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Validator\AttributeValidatorInterface;
use Magento\Catalog\Model\Product;

use function array_flip;

class SearchableAttributes implements ProductHydratorInterface
{
    private AttributeValidatorInterface $attributeValidator;

    private AttributeValueProviderInterface $attributeValueProvider;

    private SearchableAttributesProviderInterface $searchableAttributesProvider;

    private ContainsProductCodeAttributeMapInterface $containsProductCodeAttributeMap;

    public function __construct(
        AttributeValidatorInterface $attributeValidator,
        AttributeValueProviderInterface $attributeValueProvider,
        SearchableAttributesProviderInterface $searchableAttributesProvider,
        ContainsProductCodeAttributeMapInterface $containsProductCodeAttributeMap
    ) {
        $this->attributeValueProvider = $attributeValueProvider;
        $this->attributeValidator = $attributeValidator;
        $this->searchableAttributesProvider = $searchableAttributesProvider;
        $this->containsProductCodeAttributeMap = $containsProductCodeAttributeMap;
    }

    /**
     * @inheritDoc
     * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
     */
    public function extract(Product $product): array
    {
        $values = [];
        $attributes = $product->getAttributes();
        $skipAttributeCodes = array_flip($this->containsProductCodeAttributeMap->getList());

        foreach ($attributes as $attribute) {
            if (
                !$this->attributeValidator->validate($attribute) ||
                isset($skipAttributeCodes[$attribute->getAttributeCode()])
            ) {
                continue;
            }

            $weight = $this->searchableAttributesProvider->getSearchWeight($attribute->getAttributeCode());

            if ($weight <= 0) {
                continue;
            }

            $value = $this->attributeValueProvider->getValue($product, $attribute);

            if (!$value) {
                continue;
            }

            $values[SearchableAttributesProviderInterface::ATTRIBUTE_PREFIX . $attribute->getAttributeCode()] = $value;
            $values[SearchableAttributesProviderInterface::ATTRIBUTE_PREFIX . $weight][] = $value;
        }

        return $values;
    }
}
