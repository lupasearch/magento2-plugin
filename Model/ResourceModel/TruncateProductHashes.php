<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class TruncateProductHashes
{
    private ResourceConnection $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function execute(): void
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName(GetProductHashesByProductIds::TABLE_NAME);

        $connection->truncateTable($tableName);
    }
}
