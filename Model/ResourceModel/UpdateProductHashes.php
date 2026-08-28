<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Exception;
use Magento\Framework\App\ResourceConnection;

class UpdateProductHashes
{
    private const COLUMN_UPDATED_AT = 'updated_at';

    private ResourceConnection $resourceConnection;

    private string $tableName;

    public function __construct(
        ResourceConnection $resourceConnection,
        string $tableName = GetProductHashesByProductIds::TABLE_NAME
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->tableName = $tableName;
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
        $tableName = $connection->getTableName($this->tableName);

        $connection->insertOnDuplicate(
            $tableName,
            $hashesData,
            [GetProductHashesByProductIds::COLUMN_HASH, self::COLUMN_UPDATED_AT],
        );
    }
}
