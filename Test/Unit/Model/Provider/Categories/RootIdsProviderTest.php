<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Provider\Categories;

use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\RootIdsProvider;
use Magento\Catalog\Model\Category;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function array_diff;

class RootIdsProviderTest extends TestCase
{
    /**
     * @var RootIdsProvider
     */
    private $object;

    /**
     * @var MockObject
     */
    private $storeManager;

    public function testGet(): void
    {
        $expected = [
            Category::ROOT_CATEGORY_ID,
            Category::TREE_ROOT_ID,
            20,
            30,
        ];

        $storeList = [];

        foreach ([20, 30, 20] as $key => $rootId) {
            $storeList[$key] = $this->createMock(Store::class);
            $storeList[$key]->expects(self::once())
                ->method('getRootCategoryId')
                ->willReturn($rootId);
        }

        $this->storeManager
            ->expects(self::once())
            ->method('getStores')
            ->willReturn($storeList);

        $this->object->get();
        $result = $this->object->get();
        $this->assertEmpty(array_diff($result, $expected));
    }

    protected function setUp(): void
    {
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->object = new RootIdsProvider($this->storeManager);
    }
}
