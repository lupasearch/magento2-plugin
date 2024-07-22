<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\BoostAttributesProviderInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

use function array_map;

class BoostAttributesProvider implements BoostAttributesProviderInterface
{
    /**
     * @var CollectionFactory
     */
    private $attributeCollectionFactory;

    /**
     * @var string[]|null
     */
    private $list;

    /**
     * @var float[]|null
     */
    private $boostFieldCoefficients;

    /**
     * @var ProductConfigInterface
     */
    private $productConfig;

    public function __construct(
        CollectionFactory $productAttributeCollectionFactory,
        ProductConfigInterface $productConfig
    ) {
        $this->attributeCollectionFactory = $productAttributeCollectionFactory;
        $this->productConfig = $productConfig;
    }

    /**
     * @inheritDoc
     */
    public function getList(): array
    {
        if (null !== $this->list) {
            return $this->list;
        }

        $this->list = [];

        foreach ($this->getCollection() as $attribute) {
            $this->list[$this->getAttributeId($attribute)] = $attribute->getAttributeCode();
        }

        return $this->list;
    }

    public function getCoefficient(string $attributeCode): float
    {
        if (null !== $this->boostFieldCoefficients) {
            return $this->boostFieldCoefficients[$attributeCode] ?? self::DEFAULT_COEFFICIENT;
        }

        $this->boostFieldCoefficients = array_map('floatval', $this->productConfig->getBoostFieldCoefficients());

        return $this->boostFieldCoefficients[$attributeCode] ?? self::DEFAULT_COEFFICIENT;
    }

    /**
     * @inheritDoc
     */
    public function getCoefficients(): array
    {
        $coefficients = [];

        foreach ($this->getList() as $id => $attributeCode) {
            $coefficients[$id] = $this->getCoefficient($attributeCode);
        }

        return $coefficients;
    }

    public function getAttributeId(Attribute $attribute): string
    {
        return self::ATTRIBUTE_PREFIX . (int)$attribute->getAttributeId();
    }

    protected function getCollection(): Collection
    {
        $productAttributes = $this->attributeCollectionFactory->create();
        $productAttributes->addFieldToFilter('attribute_code', ['in' => $this->productConfig->getBoostFields()]);

        return $productAttributes;
    }
}
