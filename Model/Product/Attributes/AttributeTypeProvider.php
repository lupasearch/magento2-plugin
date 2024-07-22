<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeTypeProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\DB\Select;

class AttributeTypeProvider implements AttributeTypeProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $productAttributeCollectionFactory;

    /**
     * @var string[]|null
     */
    private $types;

    public function __construct(CollectionFactory $productAttributeCollectionFactory)
    {
        $this->productAttributeCollectionFactory = $productAttributeCollectionFactory;
    }

    public function get(AbstractAttribute $attribute): string
    {
        return $this->resolveType($attribute->getData());
    }

    public function getByCode(string $attributeCode): string
    {
        $this->loadBackendTypes();

        return $this->types[$attributeCode] ?? 'string';
    }

    private function loadBackendTypes(): void
    {
        if (null !== $this->types) {
            return;
        }

        $productAttributes = $this->productAttributeCollectionFactory->create();
        $select = $productAttributes->getSelect();
        $select->reset(Select::COLUMNS);
        $select->columns(['attribute_code', 'backend_type', 'frontend_input']);

        $this->types = [];

        foreach ($productAttributes->getConnection()->fetchAll($select) as $attributeData) {
            $this->types[$attributeData['attribute_code'] ?? ''] = $this->resolveType($attributeData);
        }
    }

    /**
     * @param string[] $attributeData
     */
    private function resolveType(array $attributeData): string
    {
        $backendType = $attributeData['backend_type'] ?? '';

        switch ($backendType) {
            case 'datetime':
                return AttributeTypeProviderInterface::TYPE_DATE;

            case 'int':
                return $this->getIntType($attributeData);

            case 'integer':
                return AttributeTypeProviderInterface::TYPE_INT;

            case 'decimal':
                return AttributeTypeProviderInterface::TYPE_FLOAT;

            case 'text':
                return AttributeTypeProviderInterface::TYPE_TEXT;

            case 'static':
                return $this->getStaticType($attributeData);

            case 'varchar':
            default:
                return AttributeTypeProviderInterface::TYPE_STRING;
        }
    }

    /**
     * @param string[] $attributeData
     */
    private function getStaticType(array $attributeData): string
    {
        $frontendInput = $attributeData['frontend_input'] ?? '';

        switch ($frontendInput) {
            case 'boolean':
                return AttributeTypeProviderInterface::TYPE_BOOLEAN;

            case 'date':
                return AttributeTypeProviderInterface::TYPE_DATE;

            case 'select':
            case 'gallery':
            case 'hidden':
            case 'multiline':
            case 'text':
            default:
                return AttributeTypeProviderInterface::TYPE_STRING;
        }
    }

    /**
     * @param string[] $attributeData
     */
    private function getIntType(array $attributeData): string
    {
        $frontendInput = $attributeData['frontend_input'] ?? '';

        switch ($frontendInput) {
            case 'boolean':
                return AttributeTypeProviderInterface::TYPE_BOOLEAN;

            case 'text':
                return AttributeTypeProviderInterface::TYPE_INT;

            case 'select':
            default:
                return AttributeTypeProviderInterface::TYPE_STRING;
        }
    }
}
