<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Category\Boost;

use LupaSearch\LupaSearchPlugin\Model\Category\Boost\CategoryName;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;

class CategoryNameTest extends TestCase
{
    public function testGetKeywords(): void
    {
        foreach ([[], [16 => 'Shoes', 17 => 'Bags']] as $values) {
            $select = $this->createStub(Select::class);
            $connection = $this->createMock(AdapterInterface::class);
            $connection->expects(self::once())
                ->method('fetchPairs')
                ->with($select)
                ->willReturn($values);

            $collection = $this->createStub(Collection::class);
            $collection->method('getConnection')->willReturn($connection);
            $collection->method('getSelect')->willReturn($select);

            $object = $this->createPartialMock(CategoryName::class, ['getCollection']);
            $object->expects(self::once())->method('getCollection')->willReturn($collection);

            $this->assertSame($values, $object->getKeywords());
        }
    }

    public function testGetKeywordsWithoutCollection(): void
    {
        $object = $this->createPartialMock(CategoryName::class, ['getCollection']);
        $object->expects(self::once())->method('getCollection')->willReturn(null);

        $this->assertSame([], $object->getKeywords());
    }
}
