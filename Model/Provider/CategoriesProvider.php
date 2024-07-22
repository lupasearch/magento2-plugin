<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use LupaSearch\LupaSearchPlugin\Model\Category\CollectionBuilder;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Store\Model\Store;
use Traversable;

class CategoriesProvider implements CategoriesProviderInterface, ProviderCacheInterface
{
    /**
     * @var string[]
     */
    protected array $categoriesMap = [];

    private CollectionBuilder $collectionBuilder;

    public function __construct(CollectionBuilder $collectionBuilder)
    {
        $this->collectionBuilder = $collectionBuilder;
    }

    /**
     * @inheritDoc
     */
    public function getAllIds(int $storeId): array
    {
        $collection = $this->createCollection($storeId);

        return $collection->getAllIds();
    }

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids, int $storeId): Traversable
    {
        $ids = !empty($ids) ? $ids : [0];

        $collection = $this->createCollection($storeId);
        $collection->addAttributeToFilter('entity_id', ['in' => $ids]);

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function getIdNameMap(int $storeId): array
    {
        if (isset($this->categoriesMap[$storeId])) {
            return $this->categoriesMap[$storeId];
        }

        $this->categoriesMap[$storeId] = [];
        $collection = $this->createCollection($storeId);
        $collection->removeAllFieldsFromSelect();
        $collection->addAttributeToSelect(['entity_id', 'name'], 'inner');

        foreach ($collection->getData() as $row) {
            $this->categoriesMap[$storeId][(int)($row['entity_id'])] = $row['name'] ?? '';
        }

        return $this->categoriesMap[$storeId];
    }

    public function getNameById(int $id, int $storeId): string
    {
        $map = $this->getIdNameMap($storeId);

        return $map[$id] ?? '';
    }

    /**
     * @inheritDoc
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public function warmup(array $ids, ?int $storeId = null): void
    {
        $this->getIdNameMap($storeId ?? Store::DEFAULT_STORE_ID);
    }

    public function flush(): void
    {
        $this->categoriesMap = [];
    }

    private function createCollection(int $storeId): Collection
    {
        $collection = $this->collectionBuilder->build();
        $collection->setStoreId($storeId);
        $collection->setProductStoreId($storeId);

        return $collection;
    }
}
