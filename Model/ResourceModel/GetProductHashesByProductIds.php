<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Exception;
use Magento\Framework\App\ResourceConnection;

class GetProductHashesByProductIds
{
    public const TABLE_NAME = 'lupasearch_product_hash';
    public const COLUMN_PRODUCT_ID = 'product_id';
    public const COLUMN_STORE_ID = 'store_id';
    public const COLUMN_HASH = 'hash';

    private ResourceConnection $resourceConnection;

    private string $tableName;

    public function __construct(ResourceConnection $resourceConnection, string $tableName = self::TABLE_NAME)
    {
        $this->resourceConnection = $resourceConnection;
        $this->tableName = $tableName;
    }

    /**
     * @param array<int> $productIds
     * @return array<string>
     * @throws Exception
     */
    public function execute(array $productIds, int $storeId): array
    {
        if (!$productIds) {
            return [];
        }

        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select();

        $select->from($connection->getTableName($this->tableName), [self::COLUMN_PRODUCT_ID, self::COLUMN_HASH]);
        $select->where(self::COLUMN_PRODUCT_ID . ' IN (?)', $productIds);
        $select->where(self::COLUMN_STORE_ID . ' = ?', $storeId);

        return $connection->fetchPairs($select);
    }
}
