<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;

class PositionProvider implements PositionProviderInterface
{
    private ResourceConnection $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritDoc
     */
    public function getByProductId(int $id): array
    {
        return $this->getByProductIds([$id])[$id] ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getByProductIds(array $ids): array
    {
        $select = $this->getSelect();
        $select->where('product_id IN(?)', $ids);

        return $this->prepareData($select);
    }

    /**
     * @return array<int, array<int, int>>
     */
    private function prepareData(Select $select): array
    {
        $positions = [];

        foreach ($select->getConnection()->fetchAll($select) as $row) {
            $positions[(int)$row['product_id']][(int)$row['category_id']] = (int)$row['position'];
        }

        return $positions;
    }

    private function getSelect(): Select
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $this->resourceConnection->getConnection()->select();
        $select->from($connection->getTableName('catalog_category_product'), ['product_id', 'category_id', 'position']);

        return $select;
    }
}
