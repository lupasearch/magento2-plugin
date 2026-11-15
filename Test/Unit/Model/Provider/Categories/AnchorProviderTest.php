<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Provider\Categories;

use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\AnchorProvider;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\EntityManager\MetadataPool;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AnchorProviderTest extends TestCase
{
    private AnchorProvider $object;

    private MockObject $select;

    private MockObject $connection;

    /**
     * @var array<string, string>
     */
    private array $joinConditions = [];

    public function testAnchorFlagIsCheckedOnAncestor(): void
    {
        $this->connection->expects(self::once())
            ->method('fetchAll')
            ->with($this->select)
            ->willReturn([
                ['id' => '5', 'anchor_id' => '3'],
                ['id' => '5', 'anchor_id' => '2'],
                ['id' => '4', 'anchor_id' => '2'],
            ]);

        $this->assertEquals([5 => [3, 2], 4 => [2]], $this->object->getAll());

        $condition = $this->joinConditions['attr'];

        $this->assertStringContainsString('`attr`.`row_id` = `e`.`row_id`', $condition);
        $this->assertStringNotContainsString('`child`.`row_id`', $condition);
        $this->assertStringContainsString('`attr`.`attribute_id` = 42', $condition);
        $this->assertStringContainsString('`attr`.`store_id` = 0', $condition);
        $this->assertStringContainsString('`attr`.`value` = 1', $condition);
    }

    public function testGetByCategoryIdsFiltersByDescendant(): void
    {
        $this->select->expects(self::once())
            ->method('where')
            ->with('child.entity_id IN(?)', [5, 4]);

        $this->connection->expects(self::once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => '5', 'anchor_id' => '3'],
                ['id' => '5', 'anchor_id' => '2'],
                ['id' => '4', 'anchor_id' => '2'],
            ]);

        $this->assertEquals([3, 2], $this->object->getByCategoryIds([5, 4]));
    }

    protected function setUp(): void
    {
        $resourceConnection = $this->createMock(ResourceConnection::class);
        $eavConfig = $this->createMock(EavConfig::class);
        $metadataPool = $this->createMock(MetadataPool::class);
        $attribute = $this->createMock(AbstractAttribute::class);
        $metadata = $this->createMock(EntityMetadataInterface::class);
        $this->select = $this->createMock(Select::class);
        $this->connection = $this->createMock(AdapterInterface::class);

        $attribute->method('getBackendTable')->willReturn('catalog_category_entity_int');
        $attribute->method('getAttributeId')->willReturn(42);
        $eavConfig->method('getAttribute')->willReturn($attribute);
        $metadata->method('getLinkField')->willReturn('row_id');
        $metadataPool->method('getMetadata')->willReturn($metadata);

        $resourceConnection->method('getConnection')->willReturn($this->connection);
        $this->connection->method('getTableName')->willReturnArgument(0);
        $this->connection->method('select')->willReturn($this->select);

        $this->select->method('from')->willReturnSelf();
        $this->select->method('getConnection')->willReturn($this->connection);
        $this->select->method('joinInner')->willReturnCallback(
            function (array $name, $condition) {
                $this->joinConditions[(string)key($name)] = (string)$condition;

                return $this->select;
            }
        );

        $this->object = new AnchorProvider($resourceConnection, $eavConfig, $metadataPool);
    }
}
