<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel\Product;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Configurable extends AbstractDb
{
    /**
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributeCodes(int $productId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select();
        $select->from(['main_table' => $this->getMainTable()], ['eav.attribute_code']);
        $select->where('main_table.product_id = ?', $productId);
        $select->join(
            ['eav' => $this->getTable('eav_attribute')],
            'main_table.attribute_id = eav.attribute_id',
            [],
        );

        return $connection->fetchCol($select);
    }

    // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    protected function _construct(): void
    {
        $this->_init('catalog_product_super_attribute', 'product_super_attribute_id');
    }
}
