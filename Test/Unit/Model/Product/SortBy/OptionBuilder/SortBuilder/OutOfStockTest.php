<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder\OutOfStock;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OutOfStockTest extends TestCase
{
    /**
     * @var OutOfStock
     */
    private $object;

    /**
     * @var MockObject
     */
    private $productConfig;

    public function testBuild(): void
    {
        $optionParameters = $this->createOptionParameters('test');

        $this->productConfig
            ->expects(self::once())
            ->method('isOutOfStockProductsAtTheEnd')
            ->willReturn(true);

        $this->assertEquals(['field' => 'in_stock', 'order' => 'DESC'], $this->object->build($optionParameters));
    }

    public function testBuildConfigFalse(): void
    {
        $optionParameters = $this->createOptionParameters('test');

        $this->productConfig
            ->expects(self::once())
            ->method('isOutOfStockProductsAtTheEnd')
            ->willReturn(false);

        $this->assertEmpty($this->object->build($optionParameters));
    }

    protected function setUp(): void
    {
        $this->productConfig = $this->createMock(ProductConfigInterface::class);
        $this->object = new OutOfStock($this->productConfig);
    }

    protected function createOptionParameters(string $code): OptionParametersInterface
    {
        $optionParameters = $this->createMock(OptionParametersInterface::class);

        $optionParameters
            ->expects(self::any())
            ->method('getLabel')
            ->willReturn('Test');

        $optionParameters
            ->expects(self::any())
            ->method('getCode')
            ->willReturn($code);

        $optionParameters
            ->expects(self::any())
            ->method('getDirection')
            ->willReturn(OptionParametersInterface::ASC);

        return $optionParameters;
    }
}
