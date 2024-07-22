<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\ResourceModel\Product;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\EntityFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

use function array_map;
use function sprintf;

class Attribute extends AbstractDb
{
    private const ADDITIONAL_TABLE = 'additional_table';

    /**
     * @var EntityFactory
     */
    private $eavEntityFactory;

    public function __construct(Context $context, EntityFactory $eavEntityFactory, ?string $connectionName = null)
    {
        parent::__construct($context, $connectionName);

        $this->eavEntityFactory = $eavEntityFactory;
    }

    /**
     * @return int[]
     */
    public function getAllSearchWeights(): array
    {
        $select = $this->createSelect();
        $this->addSearchableFilter($select);
        $select->group(self::ADDITIONAL_TABLE . '.search_weight');
        $select->reset(Select::COLUMNS);
        $select->columns([self::ADDITIONAL_TABLE . '.search_weight']);

        return array_map('intval', $this->getConnection()->fetchCol($select));
    }

    /**
     * @return string[][]
     */
    public function fetchAllSearchableAttributes(): array
    {
        $select = $this->createSelect();
        $this->addSearchableFilter($select);

        return $this->getConnection()->fetchAll($select);
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _construct(): void
    {
        $this->_init('eav_attribute', 'attribute_id');
    }

    private function addSearchableFilter(Select $select): void
    {
        $select->where(
            sprintf('%1$s.is_searchable = 1 OR %1$s.is_visible_in_advanced_search = 1', self::ADDITIONAL_TABLE),
        );
        $select->where(self::ADDITIONAL_TABLE . '.search_weight > 0');
    }

    private function createSelect(): Select
    {
        $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
            Product::ENTITY,
        )->getTypeId();

        $select = $this->getConnection()->select();
        $select->from(['main_table' => $this->getMainTable()]);
        $select->join(
            [self::ADDITIONAL_TABLE => $this->getTable('catalog_eav_attribute')],
            self::ADDITIONAL_TABLE . '.attribute_id = main_table.attribute_id',
        );
        $select->where(
            'main_table.entity_type_id = ?',
            $entityTypeId,
        );

        return $select;
    }
}
