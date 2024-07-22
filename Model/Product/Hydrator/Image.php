<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use Magento\Catalog\Model\Product;

class Image implements ProductHydratorInterface
{
    public const NOT_SELECTED_IMAGE = 'no_selection';

    public const IMAGE_PREFIX = 'media/catalog/product';

    /**
     * @phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification
     */
    public function extract(Product $product): array
    {
        $imageUrl = $this->getImageUrl($product);
        $data = [];
        $data['has_images'] = null !== $imageUrl;
        $data['image_url'] = $imageUrl;

        return $data;
    }

    private function getImageUrl(Product $product): ?string
    {
        $url = $product->getImage();

        if (empty($url) || self::NOT_SELECTED_IMAGE === $url) {
            return null;
        }

        return self::IMAGE_PREFIX . $url;
    }
}
