<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder\Relevance;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\TestCase;

class RelevanceTest extends TestCase
{
    /**
     * @var Relevance
     */
    private $object;

    public function testBuild(): void
    {
        $this->assertEquals(
            ['field' => '_relevance', 'order' => 'DESC'],
            $this->object->build($this->createOptionParameters('test')),
        );
    }

    protected function setUp(): void
    {
        $this->object = new Relevance();
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
