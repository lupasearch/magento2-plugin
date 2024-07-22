<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder\Value;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\ValueBuilder;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValueTest extends TestCase
{
    /**
     * @var Value
     */
    private $object;

    /**
     * @var MockObject
     */
    private $valueBuilder;

    public function testBuild(): void
    {
        $optionParameters = $this->createOptionParameters('test');

        $this->valueBuilder
            ->expects(self::once())
            ->method('build')
            ->with($optionParameters)
            ->willReturn(['field' => 'test', 'order' => 'ASC']);

        $this->assertEquals(['field' => 'test', 'order' => 'ASC'], $this->object->build($optionParameters));
    }

    public function testBuildConfigFalse(): void
    {
        $this->assertEmpty($this->object->build($this->createOptionParameters('position')));
    }

    protected function setUp(): void
    {
        $this->valueBuilder = $this->createMock(ValueBuilder::class);
        $this->object = new Value($this->valueBuilder);
    }

    private function createOptionParameters(string $code): OptionParametersInterface
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
