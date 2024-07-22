<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeTypeProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

use function array_values;
use function is_array;
use function trim;

class FilterableAttributeValueProvider implements AttributeValueProviderInterface
{
    private AttributeTypeProviderInterface $attributeTypeProvider;

    public function __construct(AttributeTypeProviderInterface $attributeTypeProvider)
    {
        $this->attributeTypeProvider = $attributeTypeProvider;
    }

    /**
     * @inheritDoc
     * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
     */
    public function getValue(Product $product, AbstractAttribute $attribute)
    {
        $attributeCode = $attribute->getAttributeCode();
        $data = $product->getData($attributeCode);

        if (empty(trim((string)$data))) {
            return null;
        }

        switch ($this->attributeTypeProvider->getByCode((string)$attribute->getAttributeCode())) {
            case AttributeTypeProviderInterface::TYPE_BOOLEAN:
                return (bool)$data;

            case AttributeTypeProviderInterface::TYPE_INT:
                return (int)$data;

            case AttributeTypeProviderInterface::TYPE_LONG:
            case AttributeTypeProviderInterface::TYPE_FLOAT:
            case AttributeTypeProviderInterface::TYPE_DOUBLE:
                return (float)$data;

            case AttributeTypeProviderInterface::TYPE_STRING:
                return $this->getStringValue($product, $attribute);

            case AttributeTypeProviderInterface::TYPE_TEXT:
                return $this->getTextValue($product, $attribute);

            case AttributeTypeProviderInterface::TYPE_DATE:
                return (string)$data;

            default:
                return null;
        }
    }

    /**
     * @return string[]|string|null
     */
    private function getStringValue(Product $product, AbstractAttribute $attribute)
    {
        $oldDataObject = $attribute->getDataObject();
        $attribute->setDataObject($product);
        $value = $attribute->getFrontend()->getValue($product);
        $attribute->setDataObject($oldDataObject);

        if (null === $value || false === $value) {
            return null;
        }

        return !is_array($value) ? (string)$value : array_values($value);
    }

    /**
     * @return array<string>|string|null
     */
    private function getTextValue(Product $product, AbstractAttribute $attribute)
    {
        $oldDataObject = $attribute->getDataObject();
        $attribute->setDataObject($product);
        $value = $attribute->getFrontend()->getValue($product);
        $attribute->setDataObject($oldDataObject);

        if (null === $value || false === $value) {
            return null;
        }

        return !is_array($value) ? (string)$value : $value;
    }
}
