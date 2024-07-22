<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Normalizer\StringNormalizerInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

use function is_string;

class AttributeValueProvider implements AttributeValueProviderInterface
{
    private StringNormalizerInterface $stringNormalizer;

    public function __construct(StringNormalizerInterface $stringNormalizer)
    {
        $this->stringNormalizer = $stringNormalizer;
    }

    public function getValue(Product $product, AbstractAttribute $attribute): ?string
    {
        $attributeCode = $attribute->getAttributeCode();

        if (null === $product->getData($attributeCode)) {
            return null;
        }

        $oldDataObject = $attribute->getDataObject();
        $attribute->setDataObject($product);
        $value = $attribute->getFrontend()->getValue($product);
        $attribute->setDataObject($oldDataObject);

        if (!is_string($value) || '' === $value) {
            return null;
        }

        return $this->stringNormalizer->normalize($value);
    }
}
