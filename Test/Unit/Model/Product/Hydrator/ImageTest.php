<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Test\Unit\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Product\Hydrator\Image;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    private $object;

    /**
     * @var MockObject
     */
    private $product;

    public function testExtract(): void
    {
        $this->product
            ->expects(self::once())
            ->method('getImage')
            ->willReturn('/test.jpg');

        $result = $this->object->extract($this->product);

        $this->assertTrue($result['has_images']);
        $this->assertEquals('media/catalog/product/test.jpg', $result['image_url']);
    }

    public function testExtractNoSelection(): void
    {
        $this->product
            ->expects(self::once())
            ->method('getImage')
            ->willReturn('no_selection');

        $result = $this->object->extract($this->product);

        $this->assertFalse($result['has_images']);
        $this->assertNull($result['image_url']);
    }

    public function testExtractEmpty(): void
    {
        $this->product
            ->expects(self::once())
            ->method('getImage')
            ->willReturn('');

        $result = $this->object->extract($this->product);

        $this->assertFalse($result['has_images']);
        $this->assertNull($result['image_url']);
    }

    protected function setUp(): void
    {
        $this->product = $this->createMock(Product::class);
        $this->object = new Image();
    }
}
