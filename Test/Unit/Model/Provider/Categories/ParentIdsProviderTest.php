<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Provider\Categories;

use LupaSearch\LupaSearchPlugin\Model\Category\CollectionBuilder;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\ParentIdsProvider;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\RootIdsProviderInterface;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ParentIdsProviderTest extends TestCase
{
    /**
     * @var ParentIdsProvider
     */
    private $object;

    /**
     * @var MockObject
     */
    private $collectionBuilder;

    /**
     * @var MockObject
     */
    private $rootIdsProvider;

    /**
     * @var MockObject
     */
    private $collection;

    /**
     * @var MockObject
     */
    private $select;

    /**
     * @var MockObject
     */
    private $connection;

    public function testGetAll(): void
    {
        $expected = [
            6666 => [
                6,
                66,
                666,
            ],
            1234 => [
                3,
            ],
        ];
        $rows = [
            '6666' => '6/66/666/6666',
            '1234' => '1/2/3/1234',
        ];

        $this->connection->expects(self::once())
            ->method('fetchPairs')
            ->with($this->select)
            ->willReturn($rows);

        $this->assertEquals($expected, $this->object->getAll());
    }

    public function testGetByIds(): void
    {
        $expected = [
            6666 => [
                6,
                66,
                666,
            ],
            1234 => [
                3,
            ],
        ];
        $rows = [
            '6666' => '6/66/666/6666',
            '1234' => '1/2/3/1234',
        ];

        $this->connection->expects(self::once())
            ->method('fetchPairs')
            ->with($this->select)
            ->willReturn($rows);

        $this->assertEquals($expected, $this->object->getByIds([6666, 1234]));
    }

    public function testGetById(): void
    {
        $expected = [
            6,
            66,
            666,
        ];
        $rows = [
            '6666' => '6/66/666/6666',
        ];

        $this->connection->expects(self::once())
            ->method('fetchPairs')
            ->with($this->select)
            ->willReturn($rows);

        $this->assertEquals($expected, $this->object->getById(6666));
    }

    protected function setUp(): void
    {
        $this->collectionBuilder = $this->createMock(CollectionBuilder::class);
        $this->rootIdsProvider = $this->createMock(RootIdsProviderInterface::class);
        $this->collection = $this->createMock(Collection::class);
        $this->select = $this->createMock(Select::class);
        $this->connection = $this->createMock(AdapterInterface::class);
        $this->object = new ParentIdsProvider($this->collectionBuilder, $this->rootIdsProvider);

        $this->collectionBuilder->expects(self::any())
            ->method('build')
            ->willReturn($this->collection);

        $this->collection->expects(self::any())
            ->method('getSelect')
            ->willReturn($this->select);

        $this->select->expects(self::any())
            ->method('getConnection')
            ->willReturn($this->connection);

        $this->rootIdsProvider->expects(self::once())
            ->method('get')
            ->willReturn([1, 2]);
    }
}
