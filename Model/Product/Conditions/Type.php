<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Conditions;

use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;

use function array_intersect;
use function array_keys;
use function array_unique;

class Type implements CollectionModifierInterface
{
    private ProductType $productType;

    /**
     * @var string[]
     */
    private array $types;

    /**
     * @param string[] $types
     */
    public function __construct(ProductType $productType, array $types = [])
    {
        $this->productType = $productType;
        $this->types = $types;
    }

    public function apply(AbstractDb $collection): void
    {
        if (empty($this->types)) {
            return;
        }

        $types = array_intersect(array_keys($this->productType->getTypes()), $this->types);

        if (empty($types)) {
            return;
        }

        $types = array_unique($types);

        $collection->addAttributeToFilter('type_id', $types);
    }
}
