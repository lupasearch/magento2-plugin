<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Product\Attributes\SearchableAttributesProvider;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\ContainsProductCodeAttributeMapInterface;
use LupaSearch\LupaSearchPlugin\Model\ResourceModel\Product\Attribute as AttributeResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SearchableAttributesProviderTest extends TestCase
{
    /**
     * @var SearchableAttributesProvider
     */
    private $object;

    /**
     * @var MockObject
     */
    private $containsProductCodeAttributeMap;

    /**
     * @var MockObject
     */
    private $attributeResource;

    /**
     * @var string[]
     */
    private $containsProductCodeAttributeList = [
        'title' => 'name',
        'x_sku' => 'sku',
    ];

    public function testGetList(): void
    {
        $expected = [
            'sp_3' => 3,
            'sp_5' => 5,
            'sp_10' => 10,
            'title' => 10,
            'x_sku' => 9,
        ];

        $this->attributeResource
            ->expects(self::once())
            ->method('getAllSearchWeights')
            ->willReturn([3, 5, 10]);

        $this->attributeResource
            ->expects(self::once())
            ->method('fetchAllSearchableAttributes')
            ->willReturn($this->getAttributeListData());

        $result = $this->object->getList();
        $this->assertEquals($expected, $result);
    }

    protected function setUp(): void
    {
        $this->containsProductCodeAttributeMap = $this->createMock(ContainsProductCodeAttributeMapInterface::class);
        $this->attributeResource = $this->createMock(AttributeResource::class);

        $this->containsProductCodeAttributeMap
            ->expects(self::any())
            ->method('getList')
            ->willReturn($this->containsProductCodeAttributeList);

        $this->object = new SearchableAttributesProvider(
            $this->containsProductCodeAttributeMap,
            $this->attributeResource,
        );
    }

    /**
     * @return array<array<int|string>>
     */
    private function getAttributeListData(): array
    {
        $attributeList = [];

        $attributeList[] = [
            'search_weight' => 5,
            'attribute_code' => 'test1',
            'attribute_id' => 55,
        ];

        $attributeList[] = [
            'search_weight' => 7,
            'attribute_code' => 'test5',
            'attribute_id' => 66,
        ];

        $attributeList[] = [
            'search_weight' => 9,
            'attribute_code' => 'sku',
            'attribute_id' => 12,
        ];

        $attributeList[] = [
            'search_weight' => 10,
            'attribute_code' => 'name',
            'attribute_id' => 33,
        ];

        return $attributeList;
    }
}
