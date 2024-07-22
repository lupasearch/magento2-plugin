<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Exception;
use Magento\Framework\App\ResourceConnection;

class UpdateProductHashes
{
    private const COLUMN_UPDATED_AT = 'updated_at';

    private ResourceConnection $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param array<array<int|string>> $hashesData
     * @throws Exception
     */
    public function execute(array $hashesData): void
    {
        if (!$hashesData) {
            return;
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName(GetProductHashesByProductIds::TABLE_NAME);

        $connection->insertOnDuplicate(
            $tableName,
            $hashesData,
            [GetProductHashesByProductIds::COLUMN_HASH, self::COLUMN_UPDATED_AT],
        );
    }
}
