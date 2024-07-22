<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\BoostAttributesProvider;
use ArrayIterator;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BoostAttributesProviderTest extends TestCase
{
    /**
     * @var BoostAttributesProvider
     */
    private $object;

    /**
     * @var MockObject
     */
    private $attributeCollectionFactory;

    /**
     * @var MockObject
     */
    private $productConfig;

    /**
     * @var MockObject
     */
    private $collection;

    public function testGetCoefficient(): void
    {
        $expected = 16.1;

        $this->productConfig
            ->expects(self::once())
            ->method('getBoostFieldCoefficients')
            ->willReturn(['test' => '16.1']);

        $this->object->getCoefficient('test');
        $result = $this->object->getCoefficient('test');
        $this->assertEquals($expected, $result);
    }

    public function testGetCoefficientDefault(): void
    {
        $expected = 1.0;

        $this->productConfig
            ->expects(self::once())
            ->method('getBoostFieldCoefficients')
            ->willReturn(['test1' => '16.1']);

        $this->object->getCoefficient('test');
        $result = $this->object->getCoefficient('test');
        $this->assertEquals($expected, $result);
    }

    public function testGetList(): void
    {
        $expected = [
            'bf_4545' => 'test1',
            'bf_1234' => 'test3',
        ];

        $this->attributeCollectionFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($this->collection);

        $this->mockCollection();

        $this->object->getList();
        $result = $this->object->getList();
        $this->assertEquals($expected, $result);
    }

    public function testGetCoefficients(): void
    {
        $expected = [
            'bf_4545' => 16.1,
            'bf_1234' => 6.0,
        ];

        $this->productConfig
            ->expects(self::once())
            ->method('getBoostFieldCoefficients')
            ->willReturn(['test1' => '16.1', 'test3' => '6']);

        $this->attributeCollectionFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($this->collection);

        $this->mockCollection();

        $result = $this->object->getCoefficients();
        $this->assertEquals($expected, $result);
    }

    public function testGetAttributeId(): void
    {
        $attribute = $this->createMock(Attribute::class);
        $attribute
            ->expects(self::once())
            ->method('getAttributeId')
            ->willReturn('19');

        $this->assertEquals('bf_19', $this->object->getAttributeId($attribute));
    }

    protected function setUp(): void
    {
        $this->attributeCollectionFactory = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->productConfig = $this->createMock(ProductConfigInterface::class);
        $this->collection = $this->createMock(Collection::class);
        $this->object = new BoostAttributesProvider($this->attributeCollectionFactory, $this->productConfig);
    }

    private function mockCollection(): void
    {
        $attributeList = [];

        foreach (['4545' => 'test1', '1234' => 'test3'] as $attributeId => $attributeCode) {
            $attribute = $this->createMock(Attribute::class);
            $attribute->expects(self::once())->method('getAttributeCode')->willReturn($attributeCode);
            $attribute->expects(self::once())->method('getAttributeId')->willReturn($attributeId);
            $attributeList[] = $attribute;
        }

        $this->collection
            ->expects(self::once())
            ->method('getIterator')
            ->willReturn(new ArrayIterator($attributeList));
    }
}
