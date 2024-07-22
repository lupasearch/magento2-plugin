<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\ResourceModel;

use LupaSearch\LupaSearchPlugin\Model\ResourceModel\TruncateProductHashes;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;

class TruncateProductHashesTest extends TestCase
{
    private ResourceConnection $resourceConnectionMock;

    private TruncateProductHashes $truncateProductHashes;

    public function testExecute(): void
    {
        $connectionMock = $this->createMock(AdapterInterface::class);

        $this->resourceConnectionMock->expects($this->once())->method('getConnection')->willReturn($connectionMock);

        $connectionMock->expects($this->once())->method('getTableName')->willReturn('table_name');
        $connectionMock->expects($this->once())->method('truncateTable')->with('table_name');

        $this->truncateProductHashes->execute();
    }

    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->createMock(ResourceConnection::class);
        $this->truncateProductHashes = new TruncateProductHashes($this->resourceConnectionMock);
    }
}
