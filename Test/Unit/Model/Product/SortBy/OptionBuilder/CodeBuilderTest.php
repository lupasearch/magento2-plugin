<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\CodeBuilder;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
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

    protected function createOptionParameters(string $code, string $direction): OptionParametersInterface
    {
        $optionParameters = $this->createStub(OptionParametersInterface::class);

        $optionParameters
            ->method('getLabel')
            ->willReturn('Test');

        $optionParameters
            ->method('getCode')
            ->willReturn($code);

        $optionParameters
            ->method('getDirection')
            ->willReturn($direction);

        return $optionParameters;
    }
}
