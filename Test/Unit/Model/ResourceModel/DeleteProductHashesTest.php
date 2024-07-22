<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\ResourceModel;

use LupaSearch\LupaSearchPlugin\Model\ResourceModel\DeleteProductHashes;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

class DeleteProductHashesTest extends TestCase
{
    private ResourceConnection $resourceConnectionMock;

    private DeleteProductHashes $deleteProductHashes;

    public function testExecuteNoIds(): void
    {
        $this->resourceConnectionMock->expects($this->never())->method('getConnection');

        $this->deleteProductHashes->execute([], 1);
    }

    public function testExecute(): void
    {
        $productIds = [1, 2, 3];

        $connectionMock = $this->createMock(AdapterInterface::class);
        $connectionMock->expects($this->once())->method('getTableName')->willReturn('table_name');

        $connectionMock->expects($this->once())
            ->method('delete')
            ->with('table_name', ['product_id IN (?)' => $productIds, 'store_id = ?' => 1]);

        $this->resourceConnectionMock->expects($this->once())->method('getConnection')->willReturn($connectionMock);

        $this->deleteProductHashes->execute($productIds, 1);
    }

    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->createMock(ResourceConnection::class);
        $this->deleteProductHashes = new DeleteProductHashes($this->resourceConnectionMock);
    }
}
