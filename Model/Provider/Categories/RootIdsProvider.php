<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider\Categories;

use Magento\Catalog\Model\Category;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

use function array_map;
use function array_unique;

class RootIdsProvider implements RootIdsProviderInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var int[]|null
     */
    private $rootCategoryIds;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        if (null !== $this->rootCategoryIds) {
            return $this->rootCategoryIds;
        }

        $this->rootCategoryIds = array_unique(
            array_map(
                [$this, 'getRootCategoryId'],
                $this->storeManager->getStores(),
            ),
        );
        $this->rootCategoryIds[] = Category::ROOT_CATEGORY_ID;
        $this->rootCategoryIds[] = Category::TREE_ROOT_ID;

        return $this->rootCategoryIds;
    }

    private function getRootCategoryId(StoreInterface $store): int
    {
        if (!$store instanceof Store) {
            return Category::TREE_ROOT_ID + 1;
        }

        /** @psalm-suppress RedundantCastGivenDocblockType */
        return (int)$store->getRootCategoryId();
    }
}
