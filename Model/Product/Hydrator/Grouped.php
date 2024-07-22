<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\HydratorPool;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;

use function array_merge_recursive;

class Grouped implements ProductHydratorInterface
{
    private const REQUIRED_ATTRIBUTES = [
        'name',
        'price',
        'special_price',
        'special_from_date',
        'special_to_date',
        'tax_class_id',
        'image'
    ];

    private HydratorPool $hydratorPool;

    private Status $productStatus;

    public function __construct(HydratorPool $hydratorPool, Status $productStatus)
    {
        $this->hydratorPool = $hydratorPool;
        $this->productStatus = $productStatus;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        if (GroupedType::TYPE_CODE !== $product->getTypeId()) {
            return [];
        }

        $collection = $product->getTypeInstance(true)->getAssociatedProductCollection($product);
        $collection->addAttributeToSelect(self::REQUIRED_ATTRIBUTES);
        $collection->addFilterByRequiredOptions();
        $collection->addStoreFilter((int)$product->getStoreId());
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getSaleableStatusIds()]);

        $data = [];

        foreach ($collection as $simpleProduct) {
            foreach ($this->hydratorPool->getAll() as $hydrator) {
                $data[] = $hydrator->extract($simpleProduct);
            }
        }

        return $data ? array_merge_recursive(...$data) : [];
    }
}
