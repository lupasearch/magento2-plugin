<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\TitleBuilder;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TitleBuilderTest extends TestCase
{
    /**
     * @var TitleBuilder
     */
    private $object;

    public function testBuild(): void
    {
        $object = new TitleBuilder(
            false,
            [
                'name' => 'name',
                'title' => 'title',
            ],
        );

        $optionParameters = $this->createOptionParameters('Test', 'test', 'DESC');

        $this->assertEquals('Test (descending)', $object->build($optionParameters));

        $optionParameters = $this->createOptionParameters('Test', 'name', 'DESC');

        $this->assertEquals('Test (Z-A)', $object->build($optionParameters));
    }

    public function testBuildUseSymbol(): void
    {
        $object = new TitleBuilder(
            true,
            [
                'name' => 'name',
                'title' => 'title',
            ],
        );

        $optionParameters = $this->createOptionParameters('Test', 'test', 'DESC');

        $this->assertEquals('Test ↑', $object->build($optionParameters));

        $optionParameters = $this->createOptionParameters('Test', 'title', 'ASC');

        $this->assertEquals('Test (A-Z)', $object->build($optionParameters));
    }

    public function testBuildUnknownDDirection(): void
    {
        $object = new TitleBuilder(
            true,
            [
                'name' => 'name',
                'title' => 'title',
            ],
        );

        $optionParameters = $this->createOptionParameters('Test', 'test', '');

        $this->assertEquals('Test ', $object->build($optionParameters));
    }

    protected function createOptionParameters(string $label, string $code, string $direction): MockObject
    {
        $optionParameters = $this->createMock(OptionParametersInterface::class);

        $optionParameters
            ->expects(self::any())
            ->method('getLabel')
            ->willReturn($label);

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
