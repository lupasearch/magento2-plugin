<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Hydrator\Category;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\ParentIdsProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\CategoriesProviderInterface;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @var Category
     */
    private $object;

    /**
     * @var MockObject
     */
    private $categoriesProvider;

    /**
     * @var MockObject
     */
    private $product;

    /**
     * @var MockObject
     */
    private $productConfig;

    /**
     * @var MockObject
     */
    private $parentIdsProvider;

    public function testExtract(): void
    {
        $expected = [
            'category_id' => 17,
            'category_ids' => [16, 17],
            'categories' => [0 => 'Test1'],
            'category' => 'Root > Test0 > Test1',
            'sp_5' => [0 => 'Test1'],
        ];
        $categoryIds = [
            1 => 16,
            9 => 17,
        ];
        $storeId = '1';

        $this->categoriesProvider
            ->expects(self::exactly(5))
            ->method('getNameById')
            ->withConsecutive(
                [16, 1],
                [17, 1],
                [1, 1],
                [2, 1],
                [17, 1],
            )
            ->willReturnOnConsecutiveCalls(
                '',
                'Test1',
                'Root',
                'Test0',
                'Test1',
            );

        $this->parentIdsProvider
            ->expects(self::exactly(1))
            ->method('getById')
            ->with()
            ->willReturn([1, 2]);

        $this->product
            ->expects(self::any())
            ->method('getCategoryIds')
            ->willReturn($categoryIds);

        $this->product
            ->expects(self::any())
            ->method('getCategoryId')
            ->willReturn(17);

        $this->product
            ->expects(self::any())
            ->method('getStoreId')
            ->willReturn($storeId);

        $result = $this->object->extract($this->product);
        $this->assertCount(5, $result);
        $this->assertEquals($expected['category_id'], $result['category_id']);
        $this->assertEquals($expected['category_ids'], $result['category_ids']);
        $this->assertEquals($expected['categories'], $result['categories']);
        $this->assertEquals($expected['category'], $result['category']);
        $this->assertEquals($expected['sp_5'], $result['sp_5']);
    }

    protected function setUp(): void
    {
        $this->categoriesProvider = $this->createMock(CategoriesProviderInterface::class);
        $this->productConfig = $this->createMock(ProductConfigInterface::class);
        $this->parentIdsProvider = $this->createMock(ParentIdsProviderInterface::class);
        $this->product = $this->createMock(Product::class);

        $this->productConfig
            ->expects(self::once())
            ->method('getCategoriesSearchWeight')
            ->willReturn(5);

        $this->object = new Category($this->categoriesProvider, $this->productConfig, $this->parentIdsProvider);
    }
}
