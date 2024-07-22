<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Normalizer\DateNormalizerInterface;
use LupaSearch\LupaSearchPlugin\Model\Normalizer\HtmlNormalizerInterface;
use Magento\Catalog\Model\Product;

class General implements ProductHydratorInterface
{
    private HtmlNormalizerInterface $htmlNormalizer;

    private DateNormalizerInterface $dateNormalizer;

    public function __construct(HtmlNormalizerInterface $htmlNormalizer, DateNormalizerInterface $dateNormalizer)
    {
        $this->htmlNormalizer = $htmlNormalizer;
        $this->dateNormalizer = $dateNormalizer;
    }

    /**
     * @inheritDoc
     */
    public function extract(Product $product): array
    {
        return [
            'title' => $this->getTitle($product),
            'sku' => $this->getSku($product),
            'description' => $this->getDescription($product),
            'short_description' => $this->getShortDescription($product),
            'is_new' => $this->isNew($product),
            'type_id' => $this->getTypeId($product),
            'set_id' => $this->getSetId($product),
            'created' => $this->getCreated($product),
        ];
    }

    private function getTitle(Product $product): string
    {
        return (string)$product->getName();
    }

    private function getSku(Product $product): string
    {
        return (string)$product->getSku();
    }

    private function isNew(Product $product): bool
    {
        return (bool)$product->getData('is_new');
    }

    private function getShortDescription(Product $product): string
    {
        return $this->htmlNormalizer->normalize((string)$product->getShortDescription());
    }

    private function getDescription(Product $product): string
    {
        return $this->htmlNormalizer->normalize((string)$product->getDescription());
    }

    private function getTypeId(Product $product): string
    {
        return (string)$product->getTypeId();
    }

    private function getSetId(Product $product): int
    {
        return (int)$product->getAttributeSetId();
    }

    private function getCreated(Product $product): string
    {
        return $this->dateNormalizer->normalize((string)$product->getCreatedAt());
    }
}
