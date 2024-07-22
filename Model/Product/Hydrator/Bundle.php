<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\HydratorPool;
use Magento\Bundle\Model\Product\Type;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

use function array_filter;
use function array_merge;
use function array_merge_recursive;
use function in_array;

class Bundle implements ProductHydratorInterface
{
    private const SKIP_ATTRIBUTES = [
        'status',
        'visibility',
        'price',
        'special_price',
        'special_from_date',
        'special_to_date',
        'msrp',
        'msrp_display_actual_price_type',
        'price_type',
        'price_view',
        'tax_class_id',
    ];

    /**
     * @var string[]|null
     */
    private ?array $attributes = null;

    private Config $config;

    private HydratorPool $hydratorPool;

    private CollectionFactory $productCollectionFactory;

    public function __construct(
        HydratorPool $hydratorPool,
        Config $config,
        CollectionFactory $productCollectionFactory,
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->hydratorPool = $hydratorPool;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        if (Type::TYPE_CODE !== $product->getTypeId()) {
            return [];
        }

        $childrenIds = $product->getTypeInstance(true)->getChildrenIds($product->getId(), false);
        $childrenIds = $childrenIds ? array_merge(...$childrenIds) : [];

        if (empty($childrenIds)) {
            return [];
        }

        $collection = $this->getCollection($product);
        $collection->addIdFilter($childrenIds);
        $data = [];

        foreach ($collection as $simpleProduct) {
            foreach ($this->hydratorPool->getAll() as $hydrator) {
                $data[] = $hydrator->extract($simpleProduct);
            }
        }

        return $data ? array_merge_recursive(...$data) : [];
    }

    private function getCollection(Product $product): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect($this->getAttributes());
        $collection->setFlag('product_children', true);
        $collection->addFilterByRequiredOptions();
        $collection->addStoreFilter((int)$product->getStoreId());

        return $collection;
    }

    /**
     * @return string[]
     */
    private function getAttributes(): array
    {
        if (null !== $this->attributes) {
            return $this->attributes;
        }

        $this->attributes = array_filter($this->config->getProductAttributes(), static function ($attribute): bool {
            return !in_array($attribute, self::SKIP_ATTRIBUTES, true);
        });

        return $this->attributes;
    }
}
