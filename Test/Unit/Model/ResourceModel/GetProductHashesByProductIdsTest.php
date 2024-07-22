<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\ResourceModel;

use LupaSearch\LupaSearchPlugin\Model\ResourceModel\GetProductHashesByProductIds;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;

class GetProductHashesByProductIdsTest extends TestCase
{
    private ResourceConnection $resourceConnectionMock;

    private GetProductHashesByProductIds $getProductHashesByProductIds;

    public function testExecuteNoProductIds(): void
    {
        $connectionMock = $this->createMock(AdapterInterface::class);

        $this->resourceConnectionMock->expects($this->never())->method('getConnection');

        $connectionMock->expects($this->never())->method('getTableName');
        $connectionMock->expects($this->never())->method('fetchPairs');

        $result = $this->getProductHashesByProductIds->execute([], 1);

        $this->assertEquals([], $result);
    }

    public function testExecute(): void
    {
        $connectionMock = $this->createMock(AdapterInterface::class);
        $selectMock = $this->createMock(Select::class);

        $this->resourceConnectionMock->expects($this->once())->method('getConnection')->willReturn($connectionMock);

        $connectionMock->expects($this->once())->method('getTableName')->willReturn('table_name');
        $connectionMock->expects($this->once())->method('fetchPairs')->willReturn(['data']);
        $connectionMock->expects($this->once())->method('select')->willReturn($selectMock);

        $selectMock->expects($this->once())->method('from')->willReturnSelf();
        $selectMock->expects($this->exactly(2))->method('where')->willReturnSelf();

        $result = $this->getProductHashesByProductIds->execute([1], 1);

        $this->assertEquals(['data'], $result);
    }

    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->createMock(ResourceConnection::class);
        $this->getProductHashesByProductIds = new GetProductHashesByProductIds($this->resourceConnectionMock);
    }
}
