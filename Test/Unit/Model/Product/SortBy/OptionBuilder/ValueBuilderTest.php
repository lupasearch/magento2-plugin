<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\ValueBuilder;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValueBuilderTest extends TestCase
{
    /**
     * @var ValueBuilder
     */
    private $object;

    public function testBuild(): void
    {
        $optionParameters = $this->createOptionParameters('test', 'DESC');

        $this->assertEquals(['field' => 'test', 'order' => 'DESC'], $this->object->build($optionParameters));
    }

    public function testOpposite(): void
    {
        $object = new ValueBuilder(['discount' => 'discount']);
        $optionParameters = $this->createOptionParameters('discount', 'DESC');

        $this->assertEquals(['field' => 'discount', 'order' => 'ASC'], $object->build($optionParameters));
    }

    public function testBuildAsc(): void
    {
        $optionParameters = $this->createOptionParameters('test', 'asc');

        $this->assertEquals(['field' => 'test', 'order' => 'asc'], $this->object->build($optionParameters));
    }

    public function testBuildUnknownDirection(): void
    {
        $optionParameters = $this->createOptionParameters('test', 'fake');

        $this->assertEquals(['field' => 'test', 'order' => 'fake'], $this->object->build($optionParameters));
    }

    protected function setUp(): void
    {
        $this->object = new ValueBuilder();
    }

    protected function createOptionParameters(string $code, string $direction): MockObject
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
            ->willReturn($direction);

        return $optionParameters;
    }
}
