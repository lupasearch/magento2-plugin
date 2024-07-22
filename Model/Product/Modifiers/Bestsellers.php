<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Modifiers;

use LupaSearch\LupaSearchPlugin\Model\Provider\DataModifierInterface;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory;
use Traversable;

class Bestsellers implements DataModifierInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function modify(Traversable $data): void
    {
        $productIds = [];

        foreach ($data as $product) {
            $productIds[] = $product->getId();
        }

        if (empty($productIds)) {
            return;
        }

        $soldQuantities = $this->getQuantities($productIds, (int)$product->getStoreId());

        foreach ($data as $product) {
            $product->setSoldQuantity($soldQuantities[(int)$product->getId()] ?? 0);
        }
    }

    /**
     * @param int[] $ids
     */
    protected function getCollection(array $ids, int $storeId): Collection
    {
        $collection = $this->collectionFactory->create();
        $collection->setPeriod('month');
        $collection->addFieldToFilter('product_id', $ids);
        $collection->addFieldToFilter('store_id', $storeId);

        return $collection;
    }

    /**
     * @param int[] $ids
     * @return int[]
     */
    private function getQuantities(array $ids, int $storeId): array
    {
        $collection = $this->getCollection($ids, $storeId);

        $data = [];

        foreach ($collection as $row) {
            $data[(int)$row['product_id']] = $row['qty_ordered'];
        }

        return $data;
    }
}
