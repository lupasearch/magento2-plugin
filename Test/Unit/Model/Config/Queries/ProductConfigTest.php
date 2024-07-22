<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Config\Queries;

use LupaSearch\LupaSearchPlugin\Model\Config\FieldArrayConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductConfigTest extends TestCase
{
    /**
     * @var ProductConfig
     */
    private $object;

    /**
     * @var MockObject
     */
    private $scopeConfig;

    /**
     * @var MockObject
     */
    private $fieldArrayConfig;

    public function testGetBoostFields(): void
    {
        $expected = ['bf_test'];

        $this->fieldArrayConfig
            ->expects(self::once())
            ->method('getColumn')
            ->with('lupasearch/queries/product/boost_fields', 'attribute_code')
            ->willReturn(['bf_test']);

        $result = $this->object->getBoostFields();
        $this->assertEquals($expected, $result);
    }

    public function testGetCategoriesSearchWeight(): void
    {
        $expected = 8;

        $this->scopeConfig
            ->expects(self::once())
            ->method('getValue')
            ->with('lupasearch/queries/product/categories_search_weight')
            ->willReturn('8');

        $result = $this->object->getCategoriesSearchWeight();
        $this->assertEquals($expected, $result);
    }

    public function testIsOutOfStockProductsAtTheEnd(): void
    {
        $storeId = 5;

        $this->scopeConfig
            ->expects(self::once())
            ->method('isSetFlag')
            ->with(
                'lupasearch/queries/product/out_of_stock_products_at_the_end',
                ScopeInterface::SCOPE_STORES,
                $storeId,
            )
            ->willReturn(true);

        $result = $this->object->isOutOfStockProductsAtTheEnd($storeId);
        $this->assertTrue($result);
    }

    public function testGetBoostFieldCoefficients(): void
    {
        $expected = ['bf_test' => 1];

        $this->fieldArrayConfig
            ->expects(self::once())
            ->method('getPairs')
            ->with('lupasearch/queries/product/boost_fields', 'coefficient', 'attribute_code', 0)
            ->willReturn(['bf_test' => 1]);

        $result = $this->object->getBoostFieldCoefficients();
        $this->assertEquals($expected, $result);
    }

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->fieldArrayConfig = $this->createMock(FieldArrayConfigInterface::class);
        $this->object = new ProductConfig($this->scopeConfig, $this->fieldArrayConfig);
    }
}
