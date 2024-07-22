<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\Provider;

use LupaSearch\LupaSearchPlugin\Model\Product\Provider\CategoryParentIdsCache;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\ParentIdsProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryParentIdsCacheTest extends TestCase
{
    /**
     * @var CategoryParentIdsCache
     */
    private $object;

    /**
     * @var MockObject
     */
    private $parentIdsProvider;

    public function testWarmup(): void
    {
        $categoryId = 42;

        $this->parentIdsProvider->expects(self::once())
            ->method('getAll')
            ->willReturn(
                [
                    42 => [
                        0,
                        1,
                        2,
                    ],
                    666 => [
                        6,
                        66,
                    ],
                    777 => [
                        7,
                        77,
                    ],
                ],
            );

        $this->assertEquals([], $this->object->getAll());
        $this->assertEquals([], $this->object->getById($categoryId));
        $this->assertEquals([], $this->object->getByIds([666, 777]));
        $this->object->warmup([$categoryId, 666]);
        $this->assertEquals(
            [
                42 => [
                    0,
                    1,
                    2,
                ],
                666 => [
                    6,
                    66,
                ],
                777 => [
                    7,
                    77,
                ],
            ],
            $this->object->getAll(),
        );
        $this->assertEquals(
            [
                0,
                1,
                2,
            ],
            $this->object->getById($categoryId),
        );
        $this->assertEquals(
            [
                666 => [
                    6,
                    66,
                ],
                777 => [
                    7,
                    77,
                ],
            ],
            $this->object->getByIds([666, 777]),
        );
        $this->object->flush();
        $this->assertEquals([], $this->object->getAll());
        $this->assertEquals([], $this->object->getById($categoryId));
        $this->assertEquals([], $this->object->getByIds([666, 777]));
    }

    protected function setUp(): void
    {
        $this->parentIdsProvider = $this->createMock(ParentIdsProviderInterface::class);
        $this->object = new CategoryParentIdsCache($this->parentIdsProvider);
    }
}
