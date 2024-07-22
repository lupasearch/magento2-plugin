<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Hydrator;

use LupaSearch\LupaSearchPlugin\Model\Config\Queries\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Hydrator\ProductHydratorInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SearchableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\AnchorProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\ParentIdsProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Categories\PositionProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\CategoriesProviderInterface;
use Magento\Catalog\Model\Product;

use function array_filter;
use function array_map;
use function array_merge;
use function array_unique;
use function array_values;
use function implode;

class Category implements ProductHydratorInterface
{
    private const HIERARCHICAL_SEPARATOR = ' > ';

    private CategoriesProviderInterface $categoriesProvider;

    private ProductConfigInterface $productConfig;

    private ParentIdsProviderInterface $parentIdsProvider;

    private PositionProviderInterface $positionProvider;

    private AnchorProviderInterface $anchorProvider;

    public function __construct(
        CategoriesProviderInterface $categoriesProvider,
        ProductConfigInterface $productConfig,
        ParentIdsProviderInterface $parentIdsProvider,
        PositionProviderInterface $positionProvider,
        AnchorProviderInterface $anchorProvider
    ) {
        $this->categoriesProvider = $categoriesProvider;
        $this->productConfig = $productConfig;
        $this->parentIdsProvider = $parentIdsProvider;
        $this->positionProvider = $positionProvider;
        $this->anchorProvider = $anchorProvider;
    }

    /**
     * @return array<string, int|string|array|null>
     */
    public function extract(Product $product): array
    {
        $categoriesWeight = $this->productConfig->getCategoriesSearchWeight();

        $data = [];
        $data['category_id'] = $this->getCategoryId($product);
        $data['category_ids'] = $this->getCategoryIds($product);
        $data['categories'] = $this->getCategories($product);
        $data['category'] = $this->getCategory($product);
        $data['position'] = $this->getPosition($product);
        $data[SearchableAttributesProviderInterface::ATTRIBUTE_PREFIX . $categoriesWeight] = $data['categories'];

        return $data;
    }

    /**
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    private function getCategoryId(Product $product): ?int
    {
        $id = $product->getCategoryId();

        return null !== $id ? (int)$id : null;
    }

    private function getCategory(Product $product): ?string
    {
        $catId = $this->getCategoryId($product);

        if (empty($catId)) {
            return null;
        }

        $ids = $this->parentIdsProvider->getById($catId);
        $ids[] = $catId;
        $storeId = $this->getStoreId($product);
        $names = [];

        foreach ($ids as $id) {
            $names[] = $this->categoriesProvider->getNameById($id, $storeId);
        }

        return implode(self::HIERARCHICAL_SEPARATOR, array_filter($names));
    }

    /**
     * @return string[]
     */
    private function getCategories(Product $product): array
    {
        $data = [];
        $storeId = $this->getStoreId($product);

        foreach ($this->getCategoryIds($product) as $id) {
            $data[] = $this->categoriesProvider->getNameById($id, $storeId);
        }

        return array_values(array_filter($data));
    }

    /**
     * @return array<string, int>
     */
    private function getPosition(Product $product): array
    {
        return array_map(
            static function (int $id): string {
                return 'category_' . $id;
            },
            $this->positionProvider->getByProductId((int)$product->getId()),
        );
    }

    /**
     * @return int[]
     */
    private function getCategoryIds(Product $product): array
    {
        $ids = array_map('intval', $product->getCategoryIds());

        return array_values(array_unique(array_merge($ids, $this->anchorProvider->getByCategoryIds($ids))));
    }

    private function getStoreId(Product $product): int
    {
        /** @psalm-suppress RedundantCastGivenDocblockType */
        return (int)$product->getStoreId();
    }
}
