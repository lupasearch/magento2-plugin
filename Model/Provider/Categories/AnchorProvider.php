<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

use Magento\Catalog\Api\Data\CategoryAttributeInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;
use Magento\Framework\DB\Sql\Expression;
use Magento\Framework\EntityManager\MetadataPool;

use function array_merge;
use function array_unique;
use function array_values;
use function implode;
use function sprintf;

class AnchorProvider implements AnchorProviderInterface
{
    private const ATTRIBUTE_CODE = 'is_anchor';

    private ResourceConnection $resourceConnection;

    private EavConfig $eavConfig;

    private MetadataPool $metadataPool;

    private ?string $linkField = null;

    public function __construct(
        ResourceConnection $resourceConnection,
        EavConfig $eavConfig,
        MetadataPool $metadataPool
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->eavConfig = $eavConfig;
        $this->metadataPool = $metadataPool;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->prepareData($this->getSelect());
    }

    /**
     * @inheritDoc
     */
    public function getByCategoryId(int $id): array
    {
        return $this->getByCategoryIds([$id]) ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getByCategoryIds(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $select = $this->getSelect();
        $select->where('child.entity_id IN(?)', $ids);
        $ids = $this->prepareData($select);

        return $ids ? array_values(array_unique(array_merge(...$ids))) : [];
    }

    /**
     * @return array<int, array<int, int>>
     */
    private function prepareData(Select $select): array
    {
        $data = [];

        foreach ($select->getConnection()->fetchAll($select) as $row) {
            $data[(int)$row['id']][] = (int)$row['anchor_id'];
        }

        return $data;
    }

    private function getSelect(): Select
    {
        $attribute = $this->eavConfig->getAttribute(CategoryAttributeInterface::ENTITY_TYPE_CODE, self::ATTRIBUTE_CODE);
        $connection = $this->resourceConnection->getConnection();
        $categoryTable = $connection->getTableName('catalog_category_entity');
        $select = $this->resourceConnection->getConnection()->select();
        $select->from(['e' => $categoryTable], ['anchor_id' => 'entity_id']);
        $select->joinInner(
            ['child' => $categoryTable],
            new Expression('child.path LIKE CONCAT(e.path, \'/%\')'),
            ['id' => 'child.entity_id'],
        );
        $select->joinInner(
            ['attr' => $connection->getTableName($attribute->getBackendTable())],
            $this->getAttributeCondition((int)$attribute->getAttributeId()),
            [],
        );

        return $select;
    }

    private function getAttributeCondition(int $attributeId): string
    {
        return sprintf(
            implode(
                ' AND ',
                [
                    '`attr`.`%1$s` = `child`.`%1$s`',
                    '`attr`.`attribute_id` = ' . $attributeId,
                    '`attr`.`%1$s` = `child`.`%1$s`',
                    '`attr`.`store_id` = 0',
                    '`attr`.`value` = 1',
                ]
            ),
            $this->getLinkField(),
        );
    }

    private function getLinkField(): string
    {
        if (null !== $this->linkField) {
            return $this->linkField;
        }

        $this->linkField = $this->metadataPool
            ->getMetadata(CategoryInterface::class)
            ->getLinkField();

        return $this->linkField;
    }
}
