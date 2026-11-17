<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder\Created;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\TestCase;

class CreatedTest extends TestCase
{
    /**
     * @var Created
     */
    private $object;

    public function testBuild(): void
    {
        $this->assertEquals(
            ['field' => 'created', 'order' => 'DESC'],
            $this->object->build($this->createOptionParameters('test')),
        );
    }

    protected function setUp(): void
    {
        $this->object = new Created();
    }

    private function createOptionParameters(string $code): OptionParametersInterface
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
            ->willReturn(OptionParametersInterface::ASC);

        return $optionParameters;
    }
}
