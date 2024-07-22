<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Exception;
use Magento\Framework\App\ResourceConnection;

class DeleteProductHashes
{
    private ResourceConnection $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param array<int> $productIds
     * @throws Exception
     */
    public function execute(array $productIds, int $storeId): void
    {
        if (!$productIds) {
            return;
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName(GetProductHashesByProductIds::TABLE_NAME);

        $connection->delete(
            $tableName,
            [
                GetProductHashesByProductIds::COLUMN_PRODUCT_ID . ' IN (?)' => $productIds,
                GetProductHashesByProductIds::COLUMN_STORE_ID . ' = ?' => $storeId,
            ],
        );
    }
}
