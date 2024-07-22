<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\ResourceModel;

use LupaSearch\LupaSearchPlugin\Model\ResourceModel\UpdateProductHashes;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

class UpdateProductHashesTest extends TestCase
{
    private ResourceConnection $resourceConnectionMock;

    private UpdateProductHashes $updateProductHashes;

    public function testExecuteNoData(): void
    {
        $connectionMock = $this->createMock(AdapterInterface::class);

        $this->resourceConnectionMock->expects($this->never())->method('getConnection');

        $connectionMock->expects($this->never())->method('getTableName');
        $connectionMock->expects($this->never())->method('insertOnDuplicate');

        $this->updateProductHashes->execute([]);
    }

    public function testExecute(): void
    {
        $connectionMock = $this->createMock(AdapterInterface::class);

        $this->resourceConnectionMock->expects($this->once())->method('getConnection')->willReturn($connectionMock);

        $connectionMock->expects($this->once())->method('getTableName')->willReturn('table_name');
        $connectionMock->expects($this->once())->method('insertOnDuplicate');

        $this->updateProductHashes->execute([['data']]);
    }

    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->createMock(ResourceConnection::class);

        $this->updateProductHashes = new UpdateProductHashes($this->resourceConnectionMock);
    }
}
