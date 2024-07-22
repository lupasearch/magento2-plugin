<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;
use LupaSearch\LupaSearchPlugin\Model\Product\HydratorPool;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    /**
     * @var Hydrator
     */
    private $object;

    /**
     * @var MockObject
     */
    private $productHydratorPool;

    public function testExtract(): void
    {
        $hydratorList = [];

        $hydratorList[0] = $this->createMock(ProductHydratorInterface::class);
        $hydratorList[0]->expects(self::once())
            ->method('extract')
            ->willReturn(
                [
                    'category_id' => 16,
                    'category_ids' => [0 => 16, 1 => 17],
                    'categories' => [0 => 'Test1'],
                    'sp_5' => [0 => 'Test1'],
                ],
            );

        $hydratorList[1] = $this->createMock(ProductHydratorInterface::class);
        $hydratorList[1]->expects(self::once())
            ->method('extract')
            ->willReturn(
                [
                    'sp_3' => [0 => 'X'],
                    'sp_5' => [0 => 'Test2'],
                    'sp_8' => [0 => 'Y'],
                ],
            );

        $hydratorList[3] = null;

        $expected = [
            'category_id' => 16,
            'category_ids' => [0 => 16, 1 => 17],
            'categories' => [0 => 'Test1'],
            'sp_5' => [0 => 'Test1', 1 => 'Test2'],
            'sp_8' => [0 => 'Y'],
            'sp_3' => [0 => 'X'],
        ];

        $this->productHydratorPool
            ->expects(self::once())
            ->method('getAll')
            ->willReturn($hydratorList);

        $this->assertEquals($expected, $this->object->extract($this->product));
    }

    protected function setUp(): void
    {
        $this->productHydratorPool = $this->createMock(HydratorPool::class);
        $this->product = $this->createMock(Product::class);
        $this->object = new Hydrator($this->productHydratorPool);
    }
}
