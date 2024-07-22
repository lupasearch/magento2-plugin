<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class Eav
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int[] $attributeIds
     * @return array<int, int>
     */
    public function getSetIdsByAttributeIds(array $attributeIds): array
    {
        if (empty($attributeIds)) {
            return [];
        }

        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName('eav_entity_attribute');

        $select = $connection->select();
        $select->from($table, ['attribute_set_id', 'attribute_id']);
        $select->where('attribute_id IN(?)', $attributeIds);

        $result = [];

        foreach ($connection->fetchAll($select) as $row) {
            $result[(int)$row['attribute_id']][] = (int)$row['attribute_set_id'];
        }

        return $result;
    }
}
