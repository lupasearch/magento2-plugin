<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Category;

use LupaSearch\LupaSearchPlugin\Model\Hydrator\CategoryHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Normalizer\StringNormalizerInterface;
use LupaSearch\LupaSearchPlugin\Model\Normalizer\UrlNormalizerInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\LocalizedException;

class Hydrator implements CategoryHydratorInterface
{
    /**
     * @var UrlNormalizerInterface
     */
    protected $urlNormalizer;

    /**
     * @var StringNormalizerInterface
     */
    private $stringNormalizer;

    public function __construct(UrlNormalizerInterface $urlNormalizer, StringNormalizerInterface $stringNormalizer)
    {
        $this->urlNormalizer = $urlNormalizer;
        $this->stringNormalizer = $stringNormalizer;
    }

    /**
     * @inheritDoc
     */
    public function extract(CategoryInterface $category): array
    {
        $imageUrl = $this->getImageUrl($category);
        $hasImages = !empty($imageUrl);

        return [
            'id' => $this->getId($category),
            'title' => $this->getTitle($category),
            'url' => $this->getUrl($category),
            'image_url' => $imageUrl,
            'has_images' => $hasImages,
            'description' => $this->getDescription($category),
        ];
    }

    protected function getImageUrl(CategoryInterface $category): string
    {
        try {
            return (string)($category->getImageUrl() ?: '');
        } catch (LocalizedException $exception) {
            return '';
        }
    }

    protected function getId(CategoryInterface $category): int
    {
        return (int)$category->getId();
    }

    protected function getUrl(CategoryInterface $category): string
    {
        return $this->urlNormalizer->normalize((string)$category->getUrl());
    }

    protected function getTitle(CategoryInterface $category): string
    {
        return $this->stringNormalizer->normalize((string)$category->getName());
    }

    protected function getDescription(CategoryInterface $category): string
    {
        return $this->stringNormalizer->normalize((string)$category->getDescription());
    }
}
