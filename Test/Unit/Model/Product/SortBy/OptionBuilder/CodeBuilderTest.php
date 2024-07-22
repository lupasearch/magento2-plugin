<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\CodeBuilder;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CodeBuilderTest extends TestCase
{
    /**
     * @var CodeBuilder
     */
    private $object;

    public function testBuild(): void
    {
        $optionParameters = $this->createOptionParameters('test', 'DESC');

        $this->assertEquals('test_desc', $this->object->build($optionParameters));
    }

    public function testBuildNoDirection(): void
    {
        $optionParameters = $this->createOptionParameters('test', '');

        $this->assertEquals('test', $this->object->build($optionParameters));
    }

    public function testBuildDirectionWithWhiteSpace(): void
    {
        $optionParameters = $this->createOptionParameters('test', ' DESC ');

        $this->assertEquals('test_desc', $this->object->build($optionParameters));
    }

    protected function setUp(): void
    {
        $this->object = new CodeBuilder();
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
