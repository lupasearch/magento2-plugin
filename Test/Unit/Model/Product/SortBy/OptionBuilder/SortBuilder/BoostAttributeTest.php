<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\SortBy\OptionBuilder\SortBuilder;

use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\BoostAttributeProvider;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionBuilder\SortBuilder\BoostAttribute;
use LupaSearch\LupaSearchPlugin\Model\Product\SortBy\OptionParametersInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BoostAttributeTest extends TestCase
{
    private const SEARCHNODE_ID = 'bf_test';

    /**
     * @var BoostAttribute
     */
    private $object;

    /**
     * @var MockObject
     */
    private $boostAttributeProvider;

    public function testBuild(): void
    {
        $optionParameters = $this->createOptionParameters('Test', 'test', 'DESC');

        $this->boostAttributeProvider
            ->expects(self::exactly(2))
            ->method('getLupaSearchId')
            ->willReturn(self::SEARCHNODE_ID);

        $this->assertEquals(
            ['field' => self::SEARCHNODE_ID, 'order' => 'DESC'],
            $this->object->build($optionParameters),
        );
    }

    public function testBuildEmpty(): void
    {
        $optionParameters = $this->createOptionParameters('Test', 'test', 'DESC');

        $this->boostAttributeProvider
            ->expects(self::exactly(1))
            ->method('getLupaSearchId')
            ->willReturn(null);

        $this->assertEmpty($this->object->build($optionParameters));
    }

    protected function setUp(): void
    {
        $this->boostAttributeProvider = $this->createMock(BoostAttributeProvider::class);
        $this->object = new BoostAttribute($this->boostAttributeProvider);
    }

    protected function createOptionParameters(string $label, string $code, string $direction): OptionParametersInterface
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
